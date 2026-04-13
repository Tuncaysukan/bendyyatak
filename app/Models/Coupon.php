<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount',
        'usage_limit', 'usage_per_user', 'used_count',
        'category_id', 'starts_at', 'expires_at', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'value'            => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'is_active'        => 'boolean',
            'starts_at'        => 'date',
            'expires_at'       => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order_amount) return 0;
        if ($this->type === 'percentage') {
            return round($subtotal * $this->value / 100, 2);
        }
        return min($this->value, $subtotal);
    }
}
