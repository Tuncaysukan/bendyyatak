<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Genel
            ['key' => 'site_name',          'value' => 'BendyyYatak',                     'group' => 'general'],
            ['key' => 'site_description',   'value' => 'Türkiye\'nin En İyi Yatak Markası', 'group' => 'general'],
            ['key' => 'site_email',         'value' => 'info@bendyyatak.com',               'group' => 'general'],
            ['key' => 'site_phone',         'value' => '0850 000 00 00',                    'group' => 'general'],
            ['key' => 'site_address',       'value' => 'İstanbul, Türkiye',                 'group' => 'general'],
            ['key' => 'whatsapp_number',    'value' => '905000000000',                      'group' => 'general'],
            ['key' => 'maintenance_mode',   'value' => '0',                                 'group' => 'general'],
            ['key' => 'copyright_text',     'value' => '© 2024 BendyyYatak. Tüm hakları saklıdır.', 'group' => 'general'],

            // Sosyal Medya
            ['key' => 'social_instagram',   'value' => 'https://instagram.com/bendyyatak',  'group' => 'social'],
            ['key' => 'social_facebook',    'value' => '',                                   'group' => 'social'],
            ['key' => 'social_youtube',     'value' => '',                                   'group' => 'social'],
            ['key' => 'social_tiktok',      'value' => '',                                   'group' => 'social'],

            // SEO
            ['key' => 'seo_title_template',       'value' => '{sayfa} | BendyyYatak',       'group' => 'seo'],
            ['key' => 'seo_description_template', 'value' => 'BendyyYatak\'ta kaliteli ve uygun fiyatlı yatakları keşfedin.', 'group' => 'seo'],
            ['key' => 'google_analytics_id',      'value' => '',                             'group' => 'seo'],
            ['key' => 'meta_pixel_id',            'value' => '',                             'group' => 'seo'],

            // Kargo
            ['key' => 'free_shipping_limit',      'value' => '2000',                         'group' => 'shipping'],
            ['key' => 'default_shipping_cost',    'value' => '149',                          'group' => 'shipping'],

            // Ödeme
            ['key' => 'iyzico_api_key',           'value' => '',                             'group' => 'payment'],
            ['key' => 'iyzico_secret_key',        'value' => '',                             'group' => 'payment'],
            ['key' => 'iyzico_base_url',          'value' => 'https://sandbox-api.iyzipay.com', 'group' => 'payment'],
            ['key' => 'payment_iyzico_active',    'value' => '1',                            'group' => 'payment'],
            ['key' => 'payment_bank_transfer_active', 'value' => '1',                        'group' => 'payment'],
            ['key' => 'payment_cash_on_delivery_active', 'value' => '1',                     'group' => 'payment'],
            ['key' => 'bank_accounts',            'value' => json_encode([
                ['bank' => 'Ziraat Bankası', 'iban' => 'TR00 0000 0000 0000 0000 0000 00', 'name' => 'BendyyYatak A.Ş.'],
            ]),                                                                                'group' => 'payment'],

            // Mail şablonları
            ['key' => 'mail_order_subject',       'value' => 'Siparişiniz Alındı - #{order_no}', 'group' => 'mail'],
            ['key' => 'mail_shipped_subject',     'value' => 'Siparişiniz Kargoya Verildi - #{order_no}', 'group' => 'mail'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
