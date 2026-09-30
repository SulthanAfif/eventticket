<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'price', 'quota', 'sold', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'quota' => 'integer',
        'sold' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function remaining(): int
    {
        return max(0, $this->quota - $this->sold);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->remaining() > 0;
    }
}
