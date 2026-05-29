<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function getByKey($key)
    {
        return static::where('section_key', $key)->where('is_active', true)->first();
    }

    public function getImageUrlAttribute()
    {
        if ($this->images && isset($this->images[0])) {
            $image = $this->images[0];
            $path = is_array($image) ? ($image['path'] ?? '') : $image;
            return asset($path);
        }
        return null;
    }

    public function getImagesUrlsAttribute()
    {
        if ($this->images && is_array($this->images)) {
            return array_map(function($image) {
                if (is_array($image) && isset($image['path'])) {
                    return asset($image['path']);
                }
                return asset($image);
            }, $this->images);
        }
        return [];
    }

    /**
     * Get images with alt tags as array of [url, alt, path] items.
     * Handles both old format (plain string) and new format ({path, alt}).
     */
    public function getImagesWithAltAttribute(): array
    {
        if (!$this->images || !is_array($this->images) || empty($this->images)) {
            return [];
        }

        return array_map(function($image) {
            if (is_array($image) && isset($image['path'])) {
                return [
                    'url' => asset($image['path']),
                    'path' => $image['path'],
                    'alt' => $image['alt'] ?? '',
                ];
            }
            // Old format: plain string path
            return [
                'url' => asset($image),
                'path' => $image,
                'alt' => '',
            ];
        }, $this->images);
    }
}
