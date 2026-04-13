<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::whereIn('group', ['general', 'social', 'payment'])->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'site_name', 'site_description', 'site_email', 'site_phone',
            'site_address', 'whatsapp_number', 'copyright_text',
            'social_instagram', 'social_facebook', 'social_youtube', 'social_tiktok',
            'payment_iyzico_active', 'payment_bank_transfer_active', 'payment_cash_on_delivery_active',
            'iyzico_api_key', 'iyzico_secret_key', 'iyzico_base_url',
            'free_shipping_limit', 'default_shipping_cost', 'contact_email', 'contact_phone', 'contact_address', 'contact_map_iframe'
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->get($field, ''));
        }

        // Toggle alanları (checkbox)
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');

        // Logo yükleme
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            $path = $request->file('site_logo')->store('site', 'public');
            Setting::set('site_logo', $path);
        }

        // Favicon yükleme
        if ($request->hasFile('favicon')) {
            $old = Setting::get('favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('favicon')->store('site', 'public');
            Setting::set('favicon', $path);
        }

        // Banka hesapları
        if ($request->has('bank_accounts')) {
            Setting::set('bank_accounts', json_encode($request->bank_accounts));
        }

        return redirect()->back()->with('success', 'Ayarlar kaydedildi.');
    }

    public function seo()
    {
        $settings = Setting::where('group', 'seo')->pluck('value', 'key');
        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        $fields = ['seo_title_template', 'seo_description_template', 'google_analytics_id', 'meta_pixel_id'];
        foreach ($fields as $field) {
            Setting::set($field, $request->get($field, ''));
        }

        // Robots.txt güncelle
        if ($request->filled('robots_txt')) {
            file_put_contents(public_path('robots.txt'), $request->robots_txt);
        }

        return redirect()->back()->with('success', 'SEO ayarları kaydedildi.');
    }

    public function mails()
    {
        $settings = Setting::where('group', 'mail')->pluck('value', 'key');
        return view('admin.settings.mails', compact('settings'));
    }

    public function updateMails(Request $request)
    {
        $fields = ['mail_order_subject', 'mail_shipped_subject'];
        foreach ($fields as $field) {
            Setting::set($field, $request->get($field, ''));
        }
        return redirect()->back()->with('success', 'Mail şablonları kaydedildi.');
    }
}
