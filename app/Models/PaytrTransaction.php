<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaytrTransaction extends Model
{
    protected $fillable = [
        'order_id', 'merchant_oid', 'paytr_token', 'amount', 'currency',
        'installment_count', 'card_type', 'card_brand', 'card_bank',
        'card_bank_id', 'card_holder', 'card_last_four', 'status',
        'paytr_response', 'callback_data', 'paid_at'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'paytr_response' => 'array',
            'callback_data' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Beklemede',
            'success' => 'Baarili',
            'failed' => 'Baarisiz',
            'cancelled' => 'Iptal Edildi',
            'refunded' => 'Iade Edildi',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'refunded' => 'info',
            default => 'secondary',
        };
    }

    public static function generateMerchantOid(): string
    {
        return 'BY-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -8));
    }
}
