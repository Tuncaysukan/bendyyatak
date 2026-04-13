<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstallmentPlan;
use App\Models\Setting;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    public function index()
    {
        $plans = InstallmentPlan::orderBy('sort_order')->get();
        return view('admin.payment.installments', compact('plans'));
    }

    public function create()
    {
        return view('admin.payment.installment-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name'         => 'required|string',
            'installment_count' => 'required|integer|min:2|max:36',
            'interest_rate'     => 'required|numeric|min:0',
        ]);
        InstallmentPlan::create($request->all());
        return redirect()->route('admin.payment.index')->with('success', 'Taksit planı eklendi.');
    }

    public function edit(InstallmentPlan $plan)
    {
        return view('admin.payment.installment-edit', compact('plan'));
    }

    public function update(Request $request, InstallmentPlan $plan)
    {
        $plan->update([
            'interest_rate' => $request->interest_rate,
            'is_active'     => $request->has('is_active'),
        ]);
        return redirect()->route('admin.payment.index')->with('success', 'Güncellendi.');
    }

    public function toggle(InstallmentPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'Durum güncellendi.');
    }

    public function destroy(InstallmentPlan $plan)
    {
        $plan->delete();
        return redirect()->back()->with('success', 'Silindi.');
    }
}
