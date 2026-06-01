<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'title',
        'subtitle',
        'points',
        'primary_button_text',
        'primary_button_link',
        'secondary_button_text',
        'secondary_button_link',
        'gallery_images',
        'uploaded_images',
        'gallery_note',
        'is_active'
    ];

    protected $casts = [
        'points' => 'array',
        'gallery_images' => 'array',
        'uploaded_images' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active commercial section
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get formatted gallery images with full URLs
     */
    public function getFormattedGalleryImagesAttribute()
    {
        $images = [];

        // Add uploaded images first
        if ($this->uploaded_images) {
            foreach ($this->uploaded_images as $image) {
                $path = $image['path'] ?? '';
                // Handle both old (storage) and new (public/uploads) paths
                if (str_starts_with($path, 'uploads/')) {
                    $src = asset($path);
                } elseif (str_starts_with($path, 'commercial-sections/')) {
                    $src = asset('storage/' . $path);
                } else {
                    $src = asset($path);
                }

                $images[] = [
                    'src' => $src,
                    'alt' => $image['alt'] ?? 'Commercial property image',
                    'label' => $image['label'] ?? 'Commercial project',
                    'type' => 'uploaded'
                ];
            }
        }

        // Add manual gallery images
        if ($this->gallery_images) {
            foreach ($this->gallery_images as $image) {
                $images[] = [
                    'src' => isset($image['src']) ? asset($image['src']) : asset('main/images/placeholder.jpg'),
                    'alt' => $image['alt'] ?? 'Commercial property image',
                    'label' => $image['label'] ?? 'Commercial project',
                    'type' => 'manual'
                ];
            }
        }

        return $images;
    }

    /**
     * Get formatted points
     */
    public function getFormattedPointsAttribute()
    {
        if (!$this->points) {
            return [];
        }

        return collect($this->points)->map(function ($point) {
            return [
                'title' => $point['title'] ?? '',
                'description' => $point['description'] ?? ''
            ];
        })->toArray();
    }
}