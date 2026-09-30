<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function publishedEvents(): HasMany
    {
        return $this->hasMany(Event::class)->where('status', 'published');
    }

    /** Tailwind classes untuk badge berdasarkan color */
    public function getBadgeClassesAttribute(): string
    {
        return match ($this->color) {
            'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'rose'    => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            'amber'   => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'sky'     => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            'violet'  => 'bg-violet-50 text-violet-700 ring-violet-600/20',
            'orange'  => 'bg-orange-50 text-orange-700 ring-orange-600/20',
            'teal'    => 'bg-teal-50 text-teal-700 ring-teal-600/20',
            'pink'    => 'bg-pink-50 text-pink-700 ring-pink-600/20',
            default   => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        };
    }
}
