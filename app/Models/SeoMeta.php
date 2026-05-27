<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $fillable = [
        'page_key',
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'schema_markup',
        'faq_schema',
    ];

    /**
     * Get SEO meta for a given page key.
     */
    public static function getForPage(string $pageKey): ?self
    {
        return static::where('page_key', $pageKey)->first();
    }

    /**
     * Get all available page keys with labels.
     */
    public static function pageOptions(): array
    {
        return [
            'home' => 'Home Page',
            'about' => 'About Page',
            'contact' => 'Contact Page',
            'properties' => 'Properties Listing Page',
            'blogs' => 'Blogs Page',
            'privacy-policy' => 'Privacy Policy Page',
            'terms-and-conditions' => 'Terms & Conditions Page',
            'calculators.acre-to-bigha' => 'Calculator: Acre to Bigha',
            'calculators.acre-to-hectare' => 'Calculator: Acre to Hectare',
            'calculators.emi-calculator' => 'Calculator: EMI Calculator',
            'calculators.length-calculator' => 'Calculator: Length Calculator',
            'calculators.acre-to-squaremeter' => 'Calculator: Acre to Square Meter',
            'calculators.cent-to-square-feet' => 'Calculator: Cent to Square Feet',
            'calculators.cent-to-square-meter' => 'Calculator: Cent to Square Meter',
            'calculators.cm-to-mm' => 'Calculator: CM to MM',
            'calculators.cm-to-inches' => 'Calculator: CM to Inches',
            'calculators.ft-to-cm' => 'Calculator: FT to CM',
            'calculators.ft-to-inches' => 'Calculator: FT to Inches',
            'calculators.ft-to-mm' => 'Calculator: FT to MM',
        ];
    }

    /**
     * Static page keys that cannot be deleted from admin panel.
     */
    public static function staticPageKeys(): array
    {
        return array_keys(static::pageOptions());
    }

    /**
     * Check if this SEO meta entry is for a static page (non-deletable).
     */
    public function isStatic(): bool
    {
        return in_array($this->page_key, static::staticPageKeys());
    }
}
