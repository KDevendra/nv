<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyPageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_type_id',
        'section_key',
        'title',
        'subtitle',
        'kicker',
        'description',
        'button_text',
        'button_link',
        'secondary_button_text',
        'secondary_button_link',
        'images',
        'features',
        'badges',
        'is_active',
        'order',
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'badges' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Section images stored in the dedicated table.
     */
    public function sectionImages(): HasMany
    {
        return $this->hasMany(PropertyPageSectionImage::class, 'property_page_section_id')
            ->orderBy('sort_order');
    }

    public static function getByKey($key)
    {
        return static::where('section_key', $key)->where('is_active', true)->first();
    }

    public function getImageUrlAttribute()
    {
        $first = $this->sectionImages->first();
        if ($first) {
            return asset($first->image_path);
        }
        return null;
    }

    public function getImagesUrlsAttribute()
    {
        return $this->sectionImages->map(fn($img) => asset($img->image_path))->all();
    }

    /**
     * Get images with alt tags as array of [url, alt, path] items.
     */
    public function getImagesWithAltAttribute(): array
    {
        return $this->sectionImages->map(fn($img) => [
            'id'   => $img->id,
            'url'  => asset($img->image_path),
            'path' => $img->image_path,
            'alt'  => $img->alt_tag ?? '',
        ])->all();
    }

    /**
     * Count of images (used by views for @if checks).
     */
    public function getImagesCountAttribute(): int
    {
        return $this->sectionImages->count();
    }
}
