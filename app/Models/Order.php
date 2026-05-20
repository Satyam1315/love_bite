<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'coupon_code',
        'discount_amount',
        'order_type',
        'status',
        'is_cancelled',
        'cancelled_at',
        'cancel_reason',
        'address',
        'table_number',
        'pickup_time',
        'payment_method',
        'payment_status',
        'payment_id',
    ];

    protected function casts(): array
    {
        return [
            'is_cancelled' => 'boolean',
            'cancelled_at' => 'datetime',
            'pickup_time' => 'datetime',
        ];
    }

    public function canBeCancelledByUser(): bool
    {
        return ! $this->is_cancelled && in_array($this->status, ['pending', 'accepted'], true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
