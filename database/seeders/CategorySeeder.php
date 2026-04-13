<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Yatak',
                'slug' => 'yatak',
                'description' => 'Kaliteli yataklar ile sağlıklı uyku deneyimi yaşayın.',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'Ortopedik Yatak', 'slug' => 'ortopedik-yatak', 'sort_order' => 1],
                    ['name' => 'Visco Yatak',      'slug' => 'visco-yatak',     'sort_order' => 2],
                    ['name' => 'Yaylı Yatak',      'slug' => 'yayli-yatak',     'sort_order' => 3],
                    ['name' => 'Çocuk Yatağı',     'slug' => 'cocuk-yatagi',    'sort_order' => 4],
                ],
            ],
            [
                'name' => 'Baza',
                'slug' => 'baza',
                'description' => 'Şık ve fonksiyonel bazalar.',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Düz Baza',       'slug' => 'duz-baza',      'sort_order' => 1],
                    ['name' => 'Sandıklı Baza',  'slug' => 'sandikli-baza', 'sort_order' => 2],
                    ['name' => 'Başlıklı Baza',  'slug' => 'baslikli-baza', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Yatak Seti',
                'slug' => 'yatak-seti',
                'description' => 'Baza ve yatak kombinasyonları.',
                'sort_order' => 3,
                'children' => [],
            ],
            [
                'name' => 'Yastık & Yorgan',
                'slug' => 'yastik-yorgan',
                'description' => 'Uyku konforunuzu tamamlayan ürünler.',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Yastık',           'slug' => 'yastik',           'sort_order' => 1],
                    ['name' => 'Yorgan',            'slug' => 'yorgan',           'sort_order' => 2],
                    ['name' => 'Yatak Koruyucu',   'slug' => 'yatak-koruyucu',   'sort_order' => 3],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            $children = $cat['children'] ?? [];
            unset($cat['children']);
            $parent = Category::create($cat);
            foreach ($children as $child) {
                Category::create(array_merge($child, ['parent_id' => $parent->id]));
            }
        }
    }
}
