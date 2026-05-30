<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyPageSectionImage extends Model
{
    protected $fillable = [
        'property_page_section_id',
        'image_path',
        'alt_tag',
        'sort_order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(PropertyPageSection::class, 'property_page_section_id');
    }

    public function getUrlAttribute(): string
    {
        return asset($this->image_path);
    }
}
