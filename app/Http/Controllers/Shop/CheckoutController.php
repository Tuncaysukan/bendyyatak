<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $coupon        = session('coupon');
        $subtotal      = array_sum(array_column($cart, 'total'));
        $freeLimit     = (float) Setting::get('free_shipping_limit', 2000);
        $shippingCost  = $subtotal >= $freeLimit ? 0 : (float) Setting::get('default_shipping_cost', 149);
        $discount      = $coupon ? $coupon['discount'] : 0;
        $total         = $subtotal + $shippingCost - $discount;

        $paymentIyzico      = Setting::get('payment_iyzico_active', '1') === '1';
        $paymentBankTransfer = Setting::get('payment_bank_transfer_active', '1') === '1';
        $paymentCashOnDel   = Setting::get('payment_cash_on_delivery_active', '1') === '1';
        $bankAccounts       = json_decode(Setting::get('bank_accounts', '[]'), true);

        return view('shop.checkout', compact(
            'cart', 'coupon', 'subtotal', 'shippingCost', 'discount', 'total',
            'paymentIyzico', 'paymentBankTransfer', 'paymentCashOnDel', 'bankAccounts'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email',
            'phone'            => 'required|string|max:20',
            'city'             => 'required|string',
            'district'         => 'required|string',
            'address'          => 'required|string',
            'payment_method'   => 'required|in:iyzico,bank_transfer,cash_on_delivery',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $coupon       = session('coupon');
        $subtotal     = array_sum(array_column($cart, 'total'));
        $freeLimit    = (float) Setting::get('free_shipping_limit', 2000);
        $shippingCost = $subtotal >= $freeLimit ? 0 : (float) Setting::get('default_shipping_cost', 149);
        $discount     = $coupon ? $coupon['discount'] : 0;
        $total        = $subtotal + $shippingCost - $discount;

        DB::transaction(function () use ($request, $cart, $coupon, $subtotal, $shippingCost, $discount, $total) {
            $order = Order::create([
                'order_no'            => Order::generateOrderNo(),
                'user_id'             => auth()->id(),
                'customer_name'       => $request->first_name . ' ' . $request->last_name,
                'customer_email'      => $request->email,
                'customer_phone'      => $request->phone,
                'shipping_first_name' => $request->first_name,
                'shipping_last_name'  => $request->last_name,
                'shipping_phone'      => $request->phone,
                'shipping_city'       => $request->city,
                'shipping_district'   => $request->district,
                'shipping_address'    => $request->address,
                'shipping_zip'        => $request->zip ?? null,
                'payment_method'      => $request->payment_method,
                'payment_status'      => 'pending',
                'subtotal'            => $subtotal,
                'shipping_cost'       => $shippingCost,
                'discount_amount'     => $discount,
                'total'               => $total,
                'coupon_id'           => $coupon ? $coupon['id'] : null,
                'coupon_code'         => $coupon ? $coupon['code'] : null,
                'customer_note'       => $request->note,
                'status'              => 'pending',
            ]);

            // Sipariş kalemleri
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'       => $item['name'],
                    'variant_name'       => $item['variant_name'] ?? null,
                    'product_image'      => $item['image'] ?? null,
                    'unit_price'         => $item['price'],
                    'quantity'           => $item['quantity'],
                    'total_price'        => $item['total'],
                ]);
            }

            // İlk durum logu
            OrderStatusLog::create([
                'order_id'        => $order->id,
                'status'          => 'pending',
                'note'            => 'Sipariş oluşturuldu.',
                'notify_customer' => true,
            ]);

            // Kupon kullanım sayısını arttır
            if ($coupon) {
                Coupon::where('id', $coupon['id'])->increment('used_count');
            }

            // Sepeti temizle
            session()->forget(['cart', 'coupon']);

            // Iyzico ödeme yönlendirmesi
            if ($order->payment_method === 'iyzico') {
                session()->put('pending_order_no', $order->order_no);
                // Iyzico entegrasyonu sonraki aşamada eklenecek
                // return redirect()->to($iyzicoCheckoutUrl);
            }

            session()->put('last_order_no', $order->order_no);
        });

        $orderNo = session()->pull('last_order_no');
        
        if ($request->payment_method !== 'iyzico') {
            $order = \App\Models\Order::where('order_no', $orderNo)->first();
            try {
                \Illuminate\Support\Facades\Mail::to('info@bendyyatak.com')->send(new \App\Mail\NewOrderMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Sipariş maili gönderilemedi: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('checkout.success', $orderNo);
    }

    public function success($orderNo)
    {
        $order = Order::where('order_no', $orderNo)
                       ->with('items')
                       ->firstOrFail();
        return view('shop.checkout-success', compact('order'));
    }

    public function failed()
    {
        return view('shop.checkout-failed');
    }

    public function callback(Request $request)
    {
        // Iyzico callback işlemi
        $orderNo = session()->pull('pending_order_no');
        if (!$orderNo) return redirect()->route('home');

        $order = Order::where('order_no', $orderNo)->first();
        if (!$order) return redirect()->route('home');

        // Iyzico status kontrolü burada yapılacak
        $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);

        try {
            \Illuminate\Support\Facades\Mail::to('info@bendyyatak.com')->send(new \App\Mail\NewOrderMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Sipariş maili gönderilemedi: ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $orderNo);
    }
}
