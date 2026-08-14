<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisoryPageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_title',
        'hero_eyebrow',
        'hero_title',
        'hero_description',
        'hero_note',
        'hero_btn1_text',
        'hero_btn1_link',
        'hero_btn2_text',
        'hero_btn2_link',
        'services_eyebrow',
        'services_title',
        'services_description',
        'track1_title',
        'track1_tagline',
        'track1_description',
        'track1_benefits',
        'track2_title',
        'track2_tagline',
        'track2_description',
        'track2_benefits',
        'why_eyebrow',
        'why_title',
        'why_items',
        'cta_eyebrow',
        'cta_title',
        'cta_phone_text',
        'cta_phone_link',
        'cta_note',
        'cta_btn1_text',
        'cta_btn1_link',
        'cta_btn2_text',
        'cta_btn2_link',
        'footnote_text',
        'is_active',
    ];

    protected $casts = [
        'track1_benefits' => 'array',
        'track2_benefits' => 'array',
        'why_items' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the active advisory page section (first record)
     */
    public static function getActive()
    {
        return self::active()->first();
    }
}
