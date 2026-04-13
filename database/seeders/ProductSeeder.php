<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $yatakCat = Category::where('slug', 'ortopedik-yatak')->first()
                 ?? Category::where('slug', 'yatak')->first()
                 ?? Category::first();

        if (!$yatakCat) return;

        $products = [
            [
                'name'              => 'Premium Ortopedik Yatak — Spring 3000',
                'price'             => 8990,
                'compare_at_price'  => 12500,
                'firmness_level'    => 6,
                'short_description' => '3000 bağımsız yay sistemi ile vücudunuzu noktasal olarak destekleyen, 120 gün deneme garantili ortopedik yatak.',
                'description'       => "Premium Ortopedik Yatak — Spring 3000, 3000 adet bağımsız yaylı sistem sayesinde vücudunuzu noktasal olarak destekler.\n\nÖzellikler:\n• 3000 bağımsız yay sistemi\n• Visco köpük konfor katmanı\n• Anti-bakteri örtü\n• Çift taraflı kullanım imkânı\n• 120 gece deneme garantisi\n• 5 yıl üretim garantisi",
                'firmness_label'    => 'Orta Sert',
                'is_active'         => true,
                'is_featured'       => true,
                'is_comparable'     => true,
                'view_count'        => rand(120, 500),
            ],
            [
                'name'              => 'Visco Bellek Köpük Yatak — Cloud 7',
                'price'             => 6490,
                'compare_at_price'  => 8990,
                'firmness_level'    => 4,
                'short_description' => 'NASA teknolojisi ile geliştirilmiş visco köpük beden sıcaklığınıza adapte olur.',
                'description'       => "NASA teknolojisi ile üretilen bellek köpük yatak, vücut sıcaklığınıza ve ağırlığınıza göre şekil alarak kusursuz destek sağlar.\n\nÖzellikler:\n• Yüksek yoğunluklu visco köpük\n• Nefes alabilen örgü örtü\n• Anti-alerjik malzeme\n• 120 gece deneme\n• 5 yıl garanti",
                'firmness_label'    => 'Yumuşak',
                'is_active'         => true,
                'is_featured'       => true,
                'is_comparable'     => true,
                'view_count'        => rand(80, 400),
            ],
            [
                'name'              => 'Lateks Doğal Yatak — Green Sleep',
                'price'             => 11990,
                'compare_at_price'  => null,
                'firmness_level'    => 5,
                'short_description' => '%100 doğal lateks, organik pamuk örtü — doğaya ve sağlığınıza saygılı tercih.',
                'description'       => "Doğal lateks yataklar, kauçuk ağaçlarından elde edilen %100 doğal malzeme ile üretilir. Anti-bakteriyel ve hipoalerjenik yapısı ile hassas ciltler için idealdir.\n\n• %100 saf doğal lateks\n• Organik pamuk örtü\n• OEKO-TEX sertifikalı\n• Çevreye duyarlı üretim\n• Ömür boyu destek garantisi",
                'firmness_label'    => 'Orta',
                'is_active'         => true,
                'is_featured'       => true,
                'is_comparable'     => true,
                'view_count'        => rand(60, 300),
            ],
            [
                'name'              => 'Hybrid Yaylı+Visco Yatak — DualCore',
                'price'             => 9990,
                'compare_at_price'  => 13500,
                'firmness_level'    => 7,
                'short_description' => 'Yay sisteminin desteği ile viskonun konforu tek yatak da buluştu.',
                'description'       => "Hybrid teknoloji, bağımsız yay sisteminin güçlü desteği ile bellek köpüğün konforunu bir arada sunar. Her uyku pozisyonu için ideal.\n\n• 2000 adet bağımsız yay\n• 4 cm visco konfor katmanı\n• Nefes alabilen kumaş\n• Tüm uyku pozisyonları için uygun",
                'firmness_label'    => 'Sert',
                'is_active'         => true,
                'is_featured'       => false,
                'is_comparable'     => true,
                'view_count'        => rand(50, 200),
            ],
            [
                'name'              => 'Ekonomik Visco Yatak — SmartSleep',
                'price'             => 3990,
                'compare_at_price'  => 5500,
                'firmness_level'    => 5,
                'short_description' => 'Bütçe dostu fiyata kaliteli visco konfor deneyimi yaşayın.',
                'description'       => "SmartSleep, uygun fiyatıyla visco köpük konforunu herkesin ulaşabileceği bir seviyeye taşıyor.\n\n• Yüksek yoğunluklu visco köpük\n• Konforlu kumaş örtü\n• Tek kişilik ve çift kişilik seçenekler\n• 2 yıl garanti",
                'firmness_label'    => 'Orta',
                'is_active'         => true,
                'is_featured'       => false,
                'is_comparable'     => true,
                'view_count'        => rand(100, 600),
            ],
            [
                'name'              => 'Bebek & Çocuk Yatağı — SafeDream',
                'price'             => 2490,
                'compare_at_price'  => null,
                'firmness_level'    => 8,
                'short_description' => 'Çocukların omurga gelişimini destekleyen, anti-alerjik sert yüzeyli yatak.',
                'description'       => "SafeDream, çocukların sağlıklı omurga gelişimine önem veren ebeveynler için geliştirildi. Anti-bakteriyel ve anti-alerjik yapısıyla güvenli uyku sağlar.\n\n• Anti-alerjik malzeme\n• OEKO-TEX güvenli\n• Sert yüzey (omurga gelişimi için)\n• Organik pamuk örtü\n• 3 yıl garanti",
                'firmness_label'    => 'Sert',
                'is_active'         => true,
                'is_featured'       => false,
                'is_comparable'     => true,
                'view_count'        => rand(40, 150),
            ],
        ];

        $sizes = [
            ['name' => '80x200 cm', 'sku_suffix' => '80200', 'extra_price' => 0,    'stock' => 10],
            ['name' => '90x200 cm', 'sku_suffix' => '90200', 'extra_price' => 200,  'stock' => 8],
            ['name' => '100x200 cm','sku_suffix' => '100200','extra_price' => 400,  'stock' => 12],
            ['name' => '120x200 cm','sku_suffix' => '120200','extra_price' => 600,  'stock' => 6],
            ['name' => '140x200 cm','sku_suffix' => '140200','extra_price' => 800,  'stock' => 7],
            ['name' => '150x200 cm','sku_suffix' => '150200','extra_price' => 1000, 'stock' => 5],
            ['name' => '160x200 cm','sku_suffix' => '160200','extra_price' => 1200, 'stock' => 9],
            ['name' => '180x200 cm','sku_suffix' => '180200','extra_price' => 1500, 'stock' => 4],
            ['name' => '200x200 cm','sku_suffix' => '200200','extra_price' => 1800, 'stock' => 3],
        ];

        $attrs = [
            ['key' => 'Malzeme',      'value' => 'Visco + Yay'],
            ['key' => 'Kaplama',      'value' => 'Organik Pamuk'],
            ['key' => 'Yükseklik',    'value' => '24 cm'],
            ['key' => 'Çevirme',      'value' => 'Çift Taraflı'],
            ['key' => 'Sertifika',    'value' => 'OEKO-TEX, EUROLATEX'],
            ['key' => 'Garanti',      'value' => '5 Yıl'],
            ['key' => 'Menşei',       'value' => 'Türkiye'],
        ];

        foreach ($products as $i => $data) {
            $slugBase = \Illuminate\Support\Str::slug($data['name'], '-');
            $slug     = $slugBase;
            $c = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $c++;
            }
            $sku = 'BY-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $product = Product::create([
                'category_id'         => $yatakCat->id,
                'name'                => $data['name'],
                'slug'                => $slug,
                'sku'                 => $sku,
                'price'               => $data['price'],
                'compare_at_price'    => $data['compare_at_price'],
                'firmness_level'      => $data['firmness_level'],
                'short_description'   => $data['short_description'],
                'description'         => $data['description'],
                'is_active'           => $data['is_active'],
                'is_featured'         => $data['is_featured'],
                'is_comparable'       => $data['is_comparable'],
                'view_count'          => $data['view_count'],
            ]);

            // (No placeholder image row — model accessor returns SVG placeholder automatically)


            // Varyantlar (boyutlar)
            foreach ($sizes as $j => $size) {
                ProductVariant::create([
                    'product_id'  => $product->id,
                    'name'        => $size['name'],
                    'sku'         => $sku . '-' . $size['sku_suffix'],
                    'extra_price' => $size['extra_price'],
                    'stock'       => $size['stock'],
                    'sort_order'  => $j,
                ]);
            }

            // Özellikler
            foreach ($attrs as $k => $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'key'        => $attr['key'],
                    'value'      => $data['firmness_level'] >= 6 ? str_replace('Visco + Yay', 'Yay + Visco', $attr['value']) : $attr['value'],
                    'sort_order' => $k,
                ]);
            }
        }
    }
}
