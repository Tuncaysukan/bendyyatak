<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track()
    {
        return view('shop.order-track');
    }

    public function trackPost(Request $request)
    {
        $request->validate([
            'order_no' => 'required|string',
            'email'    => 'required|email',
        ]);

        $order = Order::where('order_no', $request->order_no)
                      ->where('customer_email', $request->email)
                      ->with('items', 'statusLogs')
                      ->first();

        if (!$order) {
            return back()->withErrors(['order_no' => 'Sipariş bulunamadı. Sipariş numarası ve e-postayı kontrol edin.']);
        }

        return view('shop.order-track', compact('order'));
    }
}
