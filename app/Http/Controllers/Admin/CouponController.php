<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('category')->latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.coupons.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'              => 'required|string|unique:coupons,code',
            'type'              => 'required|in:percentage,fixed',
            'value'             => 'required|numeric|min:0',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'usage_limit'       => 'nullable|integer|min:1',
            'usage_per_user'    => 'nullable|integer|min:1',
            'category_id'       => 'nullable|exists:categories,id',
            'starts_at'         => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:starts_at',
        ]);

        Coupon::create(array_merge($request->all(), [
            'is_active' => $request->has('is_active'),
            'code'      => strtoupper($request->code),
        ]));
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon oluşturuldu.');
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.coupons.edit', compact('coupon', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'             => 'required|string|unique:coupons,code,' . $coupon->id,
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
        ]);

        $coupon->update(array_merge($request->all(), [
            'is_active' => $request->has('is_active'),
            'code'      => strtoupper($request->code),
        ]));
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon güncellendi.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon silindi.');
    }
}
