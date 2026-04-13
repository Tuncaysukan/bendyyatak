<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Models\Setting;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $zones    = ShippingZone::orderBy('city')->get();
        $settings = Setting::where('group', 'shipping')->pluck('value', 'key');
        return view('admin.shipping.index', compact('zones', 'settings'));
    }

    public function update(Request $request)
    {
        Setting::set('free_shipping_limit',   $request->free_shipping_limit ?? 0);
        Setting::set('default_shipping_cost', $request->default_shipping_cost ?? 0);

        if ($request->has('zones')) {
            foreach ($request->zones as $zoneId => $data) {
                ShippingZone::where('id', $zoneId)->update([
                    'price'                      => $data['price'] ?? 0,
                    'cash_on_delivery_available' => isset($data['cash_on_delivery_available']),
                    'cash_on_delivery_extra'     => $data['cash_on_delivery_extra'] ?? 0,
                    'is_active'                  => isset($data['is_active']),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Kargo ayarları güncellendi.');
    }
}
