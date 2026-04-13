<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentChannelController extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('group', ['payment'])->pluck('value', 'key');
        $plans = \App\Models\InstallmentPlan::orderBy('sort_order')->get();
        return view('admin.payment.index', compact('settings', 'plans'));
    }

    public function update(Request $request)
    {
        $fields = [
            'iyzico_api_key', 'iyzico_secret_key', 'iyzico_base_url',
        ];
        foreach ($fields as $field) {
            Setting::set($field, $request->get($field, ''));
        }

        Setting::set('payment_iyzico_active',    $request->has('payment_iyzico_active')    ? '1' : '0');
        Setting::set('payment_bank_transfer_active', $request->has('payment_bank_transfer_active') ? '1' : '0');
        Setting::set('payment_cash_on_delivery_active', $request->has('payment_cash_on_delivery_active') ? '1' : '0');

        if ($request->has('bank_accounts')) {
            Setting::set('bank_accounts', json_encode($request->bank_accounts));
        }

        return redirect()->back()->with('success', 'Ödeme kanalları güncellendi.');
    }
}
