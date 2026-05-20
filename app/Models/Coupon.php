<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'value' => 'float',
            'min_order_amount' => 'float',
        ];
    }

    /**
     * Check if the coupon is currently valid for the given order amount.
     */
    public function isValidForAmount(float $amount): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($amount < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount based on order subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ($this->value / 100);
        } else {
            $discount = $this->value;
        }

        // Discount cannot exceed the subtotal
        return min($discount, $subtotal);
    }
}
