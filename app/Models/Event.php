<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'title', 'slug', 'description', 'venue', 'city',
        'starts_at', 'ends_at', 'poster', 'status',
        'ticket_bg_type', 'ticket_bg_color', 'ticket_bg_image',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor URL gambar background
    public function getTicketBgImageUrlAttribute(): ?string
    {
        if (!$this->ticket_bg_image) {
            return null;
        }
        if (str_starts_with($this->ticket_bg_image, 'http://') || str_starts_with($this->ticket_bg_image, 'https://')) {
            return $this->ticket_bg_image;
        }
        return Storage::disk('public')->url($this->ticket_bg_image);
    }

    // Accessor inline CSS style untuk tiket
    public function getTicketStyleAttribute(): string
    {
        if ($this->ticket_bg_type === 'image' && $this->ticket_bg_image_url) {
            return "background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), url('{$this->ticket_bg_image_url}') center/cover no-repeat;";
        }

        // Pilihan preset gradasi
        return match ($this->ticket_bg_color) {
            'emerald' => 'background: linear-gradient(135deg, #059669 0%, #0d9488 100%);',
            'sunset'  => 'background: linear-gradient(135deg, #e11d48 0%, #f97316 100%);',
            'dark'    => 'background: linear-gradient(135deg, #0f172a 0%, #334155 100%);',
            'gold'    => 'background: linear-gradient(135deg, #b45309 0%, #d97706 100%);',
            'purple'  => 'background: linear-gradient(135deg, #7c3aed 0%, #c026d3 100%);',
            default   => 'background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);', // Indigo
        };
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}