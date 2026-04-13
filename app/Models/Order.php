<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_no', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_first_name', 'shipping_last_name', 'shipping_phone',
        'shipping_city', 'shipping_district', 'shipping_address', 'shipping_zip',
        'billing_same_as_shipping', 'billing_name', 'billing_tax_no', 'billing_tax_office',
        'payment_method', 'payment_status',
        'subtotal', 'shipping_cost', 'discount_amount', 'total',
        'coupon_id', 'coupon_code',
        'status', 'cargo_company', 'cargo_tracking_no', 'cargo_tracking_url',
        'customer_note', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'billing_same_as_shipping' => 'boolean',
            'subtotal'       => 'decimal:2',
            'shipping_cost'  => 'decimal:2',
            'discount_amount'=> 'decimal:2',
            'total'          => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Beklemede',
            'confirmed'  => 'Onaylandı',
            'preparing'  => 'Hazırlanıyor',
            'shipped'    => 'Kargoya Verildi',
            'delivered'  => 'Teslim Edildi',
            'cancelled'  => 'İptal Edildi',
            'refunded'   => 'İade Edildi',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'confirmed'  => 'info',
            'preparing'  => 'primary',
            'shipped'    => 'indigo',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            'refunded'   => 'secondary',
            default      => 'secondary',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'iyzico'           => 'Kredi / Banka Kartı',
            'bank_transfer'    => 'Havale / EFT',
            'cash_on_delivery' => 'Kapıda Ödeme',
            default            => $this->payment_method,
        };
    }

    public static function generateOrderNo(): string
    {
        return 'BY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
