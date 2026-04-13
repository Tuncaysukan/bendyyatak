<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::withCount('orders')
                        ->latest()
                        ->paginate(25);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $orders = Order::where('user_id', $customer->id)->latest()->get();
        $totalSpent = $orders->where('payment_status', 'paid')->sum('total');
        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent'));
    }
}
