<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart     = $this->getCart();
        $coupon   = session('coupon');
        $shipping = $this->calculateShipping(array_sum(array_column($cart, 'total')));

        return view('shop.cart', compact('cart', 'coupon', 'shipping'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::find($request->variant_id) : null;

        $price = $product->price + ($variant ? $variant->extra_price : 0);
        $cartKey = $request->product_id . '-' . ($request->variant_id ?? '0');

        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
            $cart[$cartKey]['total']     = $cart[$cartKey]['quantity'] * $price;
        } else {
            $cart[$cartKey] = [
                'product_id'   => $product->id,
                'variant_id'   => $request->variant_id,
                'name'         => $product->name,
                'variant_name' => $variant ? $variant->name : null,
                'price'        => $price,
                'quantity'     => $request->quantity,
                'total'        => $price * $request->quantity,
                'image'        => $product->primaryImageUrl,
                'slug'         => $product->slug,
            ];
        }
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Ürün sepete eklendi!',
            'count'   => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = max(1, (int) $request->quantity);
            $cart[$request->key]['total']    = $cart[$request->key]['quantity'] * $cart[$request->key]['price'];
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Ürün sepetten çıkarıldı.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return redirect()->back()->withErrors(['code' => 'Geçersiz veya süresi dolmuş kupon.']);
        }

        $cart     = $this->getCart();
        $subtotal = array_sum(array_column($cart, 'total'));
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return redirect()->back()->withErrors(['code' => 'Minimum sepet tutarı: ₺' . number_format($coupon->min_order_amount, 2)]);
        }

        session()->put('coupon', [
            'id'       => $coupon->id,
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]);

        return redirect()->route('cart.index')->with('success', '₺' . number_format($discount, 2) . ' indirim uygulandı!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->route('cart.index')->with('success', 'Kupon kaldırıldı.');
    }

    private function getCart(): array
    {
        return session()->get('cart', []);
    }

    private function calculateShipping(float $subtotal): float
    {
        $freeLimit = (float) Setting::get('free_shipping_limit', 2000);
        if ($subtotal >= $freeLimit) return 0;
        return (float) Setting::get('default_shipping_cost', 149);
    }
}
