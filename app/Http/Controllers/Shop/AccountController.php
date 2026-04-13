<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        return view('shop.account.index', compact('user', 'orders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        return view('shop.account.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load('items', 'statusLogs');
        return view('shop.account.order-detail', compact('order'));
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('shop.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:50',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'phone'      => 'required|string',
            'city'       => 'required|string',
            'district'   => 'required|string',
            'address'    => 'required|string',
        ]);

        if ($request->has('is_default')) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Address::create(array_merge($request->all(), [
            'user_id'    => auth()->id(),
            'is_default' => $request->has('is_default'),
        ]));

        return redirect()->route('account.addresses')->with('success', 'Adres eklendi.');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->update($request->all());
        return redirect()->route('account.addresses')->with('success', 'Adres güncellendi.');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->delete();
        return redirect()->route('account.addresses')->with('success', 'Adres silindi.');
    }

    public function profile()
    {
        return view('shop.account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);
        auth()->user()->update($request->only('name', 'phone'));
        return redirect()->back()->with('success', 'Profil güncellendi.');
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $request->product_id,
            'user_id'    => auth()->id(),
            'name'       => auth()->user()->name,
            'email'      => auth()->user()->email,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'is_approved'=> false,
        ]);

        return redirect()->back()->with('success', 'Yorumunuz onay bekliyor.');
    }
}
