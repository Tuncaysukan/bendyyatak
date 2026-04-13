<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'transaction_id', 'conversation_id',
        'status', 'amount', 'installment_count', 'card_last4', 'card_bank', 'raw_response'
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'raw_response' => 'array',
        ];
    }
}
