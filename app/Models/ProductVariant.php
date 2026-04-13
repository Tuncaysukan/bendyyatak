<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'sku', 'extra_price', 'stock', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'extra_price' => 'decimal:2',
            'is_active'   => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->product->price + $this->extra_price;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
