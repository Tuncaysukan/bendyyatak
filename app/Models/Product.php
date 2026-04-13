<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'short_description', 'description',
        'price', 'compare_at_price', 'firmness_level', 'view_count',
        'is_featured', 'is_bestseller', 'is_new_arrival', 'show_on_slider',
        'is_active', 'is_comparable', 'sku',
        'seo_title', 'seo_description', 'og_image',
    ];

    protected function casts(): array
    {
        return [
            'price'            => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_featured'      => 'boolean',
            'is_bestseller'    => 'boolean',
            'is_new_arrival'   => 'boolean',
            'show_on_slider'   => 'boolean',
            'is_active'        => 'boolean',
            'is_comparable'    => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        // Try loaded images relation first (avoids extra query)
        $candidates = $this->relationLoaded('images')
            ? $this->images          // already sorted by sort_order
            : $this->images()->get();

        $primary = $candidates->firstWhere('is_primary', true) ?? $candidates->first();

        if ($primary) {
            $path = storage_path('app/public/' . $primary->image);
            if (file_exists($path)) {
                return asset('storage/' . $primary->image);
            }
        }

        return asset('images/product-placeholder.svg');
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->price) {
            return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }
        return null;
    }

    public function getFirmnessLabelAttribute(): string
    {
        $labels = [
            1 => 'Çok Yumuşak', 2 => 'Çok Yumuşak', 3 => 'Yumuşak',
            4 => 'Hafif Yumuşak', 5 => 'Orta', 6 => 'Hafif Sert',
            7 => 'Orta Sert', 8 => 'Sert', 9 => 'Çok Sert', 10 => 'Extra Sert'
        ];
        return $labels[$this->firmness_level] ?? 'Orta';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
