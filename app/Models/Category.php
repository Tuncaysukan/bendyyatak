<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image',
        'is_featured', 'is_bestseller', 'show_on_slider',
        'seo_title', 'seo_description', 'sort_order', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'is_bestseller'  => 'boolean',
            'show_on_slider' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/category-placeholder.jpg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
