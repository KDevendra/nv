<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTourSection extends Model
{
    protected $fillable = [
        'badge_text',
        'title',
        'button_text',
        'button_link',
        'youtube_url',
        'video_path',
        'thumbnail',
        'thumbnail_alt',
        'phone_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail && file_exists(public_path($this->thumbnail))) {
            return asset($this->thumbnail);
        }
        return null;
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->youtube_url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->youtube_url, $matches);
        return isset($matches[1]) ? "https://www.youtube.com/embed/{$matches[1]}" : null;
    }
}
