<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'is_active',
        'order',
    ];

    // Accessor: Otomatis memformat URL gambar
    public function getImageUrlAttribute(): string
    {
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }
}
