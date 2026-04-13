<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $fillable = ['order_id', 'admin_id', 'status', 'note', 'notify_customer'];

    protected function casts(): array
    {
        return ['notify_customer' => 'boolean'];
    }
}
