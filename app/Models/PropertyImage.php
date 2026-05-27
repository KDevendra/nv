<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    protected $fillable = [
        'property_id',
        'image_path',
        'image_type',
        'title',
        'alt_tag',
        'display_order',
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeMain($query)
    {
        return $query->where('image_type', 'main');
    }

    public function scopeGallery($query)
    {
        return $query->where('image_type', 'gallery');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Accessors
    public function getFullUrlAttribute(): string
    {
        return asset($this->image_path);
    }
}
