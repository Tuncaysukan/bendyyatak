<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'city', 'price', 'cash_on_delivery_available',
        'cash_on_delivery_extra', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'price'                      => 'decimal:2',
            'cash_on_delivery_extra'     => 'decimal:2',
            'cash_on_delivery_available' => 'boolean',
            'is_active'                  => 'boolean',
        ];
    }
}
