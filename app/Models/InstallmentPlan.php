<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    protected $fillable = ['bank_name', 'bank_logo', 'installment_count', 'interest_rate', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'interest_rate' => 'decimal:2',
            'is_active'     => 'boolean',
        ];
    }

    public function calculateMonthly(float $amount): float
    {
        if ($this->interest_rate > 0) {
            $total = $amount * (1 + ($this->interest_rate / 100));
        } else {
            $total = $amount;
        }
        return round($total / $this->installment_count, 2);
    }

    /** Alias used in product view */
    public function calculateMonthlyPayment(float $amount): float
    {
        return $this->calculateMonthly($amount);
    }
}
