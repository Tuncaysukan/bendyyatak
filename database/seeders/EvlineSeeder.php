<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EvlineSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            "categories" => [
                [
                    "name" => "YATAK",
                    "image_url" => "https://evline.com.tr/storage/552/BOHRScphkNDIy1YARfGfoj9zDnrkvK-metaQmHFn2zEsWtzxLF6LTIuanBn-.jpg"
                ],
                [
                    "name" => "BAZA & BAŞLIK",
                    "image_url" => "https://evline.com.tr/storage/874/01KKP0GD92CQA7ZV6S23K2GF84.png"
                ],
                [
                    "name" => "SETLER",
                    "image_url" => "https://evline.com.tr/storage/78/CUaIY5FJn4jLW1p3vyrjuCu5oz8Rpd-metaMS5wbmc=-.png"
                ]
            ],
            "products" => [
                [
                    "name" => "Elite Yatak",
                    "category" => "YATAK",
                    "price" => "9220",
                    "description" => "7 Bölgeli Comfort Tech Packet Yay Sistemi, 27cm yükseklik, orta yumuşak konfor seviyesi. Antibakteriyel ve çift taraflı kullanıma uygundur.",
                    "image_url" => "https://evline.com.tr/storage/894/conversions/product-55e12caf-6d0f-4b5f-9f7f-ba626c4c72ef-md.jpg"
                ],
                [
                    "name" => "Lior Yatak",
                    "category" => "YATAK",
                    "price" => "9880",
                    "description" => "Pocket yay teknolojisi ile vücut ağırlığını dengeler. Her vücut tipine uygun konfor sağlayan 25cm yüksekliğinde ortopedik yatak.",
                    "image_url" => "https://evline.com.tr/storage/307/conversions/lior-yatak-1694522419Nj-md.jpg"
                ],
                [
                    "name" => "Bamboo Yatak",
                    "category" => "YATAK",
                    "price" => "14030",
                    "description" => "Doğal bambu lifli kumaş yapısı sayesinde nefes alır. Yüksek konfor ve ortopedik destek sunan premium yatak.",
                    "image_url" => "https://evline.com.tr/storage/89/conversions/FkkWP07YlXWVWfMXMfR0K94uL43Nff-metaYmBamJvby5wbmc==--md.jpg"
                ],
                [
                    "name" => "Black Style Yatak",
                    "category" => "YATAK",
                    "price" => "14840",
                    "description" => "Modern paket yay sistemi ve şık siyah kenar tasarımı. Uyku kalitesini artıran gelişmiş destek katmanları.",
                    "image_url" => "https://evline.com.tr/storage/892/conversions/product-fdc15fdd-b47e-4022-b843-9ef18278a263-md.jpg"
                ],
                [
                    "name" => "Terapi Baza Başlık",
                    "category" => "BAZA & BAŞLIK",
                    "price" => "10200",
                    "description" => "%100 çelik konstrüksiyon, geniş iç hacimli sandıklı baza. Emniyet kilitli amortisör sistemi ve silinebilir kumaş.",
                    "image_url" => "https://evline.com.tr/storage/874/conversions/01KKP0GD92CQA7ZV6S23K2GF84-md.jpg"
                ],
                [
                    "name" => "Zen Baza Başlık",
                    "category" => "BAZA & BAŞLIK",
                    "price" => "27470",
                    "description" => "Lüks dikiş detaylı başlık ve dayanıklı metal iskelet yapısı. Geniş depolama alanı sunan modern tasarım.",
                    "image_url" => "https://evline.com.tr/storage/917/conversions/01K67K49H7V2HTYQJFTF4C74N6-md.jpg"
                ],
                [
                    "name" => "King Baza Başlık",
                    "category" => "BAZA & BAŞLIK",
                    "price" => "11370",
                    "description" => "Klasik ve sağlam tasarım, yüksek ayak yapısı ile temizlik kolaylığı sağlar. Şık ve dayanıklı başlık dahil.",
                    "image_url" => "https://evline.com.tr/storage/914/conversions/01JK2N46P7V2HTYQJFTF4C74N6-md.jpg"
                ],
                [
                    "name" => "Bohem Set",
                    "category" => "SETLER",
                    "price" => "23090",
                    "description" => "Sandıklı Demonte Baza + Sevilla Yatak + Bohem Başlık. Tamamı metal konstrüksiyon, otomatik kilitli amortisör sistemi.",
                    "image_url" => "https://evline.com.tr/storage/693/conversions/product-5b0340d1-ec29-449c-8573-550f12af789f-md.jpg"
                ],
                [
                    "name" => "Anka Set",
                    "category" => "SETLER",
                    "price" => "31600",
                    "description" => "Full ortopedik yatak ve geniş depolama alanlı baza seti. Uzun ömürlü kullanım için yüksek kaliteli malzemelerle üretilmiştir.",
                    "image_url" => "https://evline.com.tr/storage/878/conversions/01K9J8P7V2HTYQJFTF4C74N6-md.jpg"
                ],
                [
                    "name" => "Terapi Set",
                    "category" => "SETLER",
                    "price" => "17190",
                    "description" => "Ekonomik ve konforlu komple uyku çözümü. Terapi baza ve uyumlu konforlu yatak kombinasyonu.",
                    "image_url" => "https://evline.com.tr/storage/880/conversions/01KKP0GD92CQA7ZV6S23K2GF84-md.jpg"
                ]
            ]
        ];

        $categoryMap = [];

        foreach ($data['categories'] as $catData) {
            $imagePath = $this->downloadImage($catData['image_url'], 'categories');
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                [
                    'name' => $catData['name'],
                    'image' => $imagePath,
                    'is_active' => true,
                    'show_on_slider' => true
                ]
            );
            $categoryMap[$catData['name']] = $category->id;
        }

        foreach ($data['products'] as $prodData) {
            $product = Product::create([
                'category_id' => $categoryMap[$prodData['category']],
                'name' => $prodData['name'],
                'slug' => Str::slug($prodData['name']),
                'price' => $prodData['price'],
                'description' => $prodData['description'],
                'short_description' => Str::limit($prodData['description'], 100),
                'is_active' => true,
                'is_featured' => true,
                'is_new_arrival' => collect([true, false])->random(),
                'is_bestseller' => collect([true, false])->random(),
            ]);

            $imagePath = $this->downloadImage($prodData['image_url'], 'products');
            if ($imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_primary' => true,
                    'sort_order' => 0
                ]);
            }
        }
    }

    private function downloadImage($url, $folder)
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = $folder . '/' . Str::random(10) . '.' . $extension;
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            // Silence
        }
        return null;
    }
}
