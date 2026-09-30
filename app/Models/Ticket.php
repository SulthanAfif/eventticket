<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Ticket extends Model
{
    protected $fillable = [
        'order_item_id', 'code', 'is_used', 'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->code)) {
                $ticket->code = strtoupper(Str::random(12));
            }
        });
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    public function ticketType(): HasOneThrough
    {
        return $this->hasOneThrough(
            TicketType::class,
            OrderItem::class,
            'id',             // Foreign key di tabel order_items
            'id',             // Foreign key di tabel ticket_types
            'order_item_id',  // Local key di tabel tickets
            'ticket_type_id'  // Local key di tabel order_items
        );
    }
}