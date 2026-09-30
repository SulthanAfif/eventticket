<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'total_price', 'status', 'paid_at',
        'snap_token', 'payment_type', 'expires_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'total_price' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->code)) {
                $order->code = 'EVT-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
    return 'code';
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending'
            && $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    public function paymentLabel(): ?string
    {
        return match ($this->payment_type) {
            'credit_card' => 'Kartu kredit/debit',
            'bank_transfer' => 'Transfer bank (VA)',
            'echannel' => 'Mandiri Bill',
            'gopay' => 'GoPay',
            'qris' => 'QRIS',
            'shopeepay' => 'ShopeePay',
            'cstore' => 'Minimarket',
            null => null,
            default => ucfirst(str_replace('_', ' ', $this->payment_type)),
        };
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}