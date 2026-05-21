<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'status',
        'show_in_header',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'show_in_header' => 'boolean',
    ];

    /**
     * Get the service types associated with this property type.
     */
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'property_type_service_type')
                    ->withTimestamps();
    }

    /**
     * Get the BHKs associated with this property type.
     */
    public function bhks()
    {
        return $this->belongsToMany(Bhk::class, 'bhk_property_type')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active property types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the property page sections for this property type.
     */
    public function propertyPageSections()
    {
        return $this->hasMany(PropertyPageSection::class);
    }

    /**
     * Get carousel section for this property type.
     */
    public function carouselSection()
    {
        return $this->hasOne(PropertyPageSection::class)->where('section_key', 'carousel_section');
    }

    /**
     * Get perspective section for this property type.
     */
    public function perspectiveSection()
    {
        return $this->hasOne(PropertyPageSection::class)->where('section_key', 'perspective_section');
    }

    /**
     * Get intro section for this property type.
     */
    public function introSection()
    {
        return $this->hasOne(PropertyPageSection::class)->where('section_key', 'intro_section');
    }
}
