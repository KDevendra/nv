<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bhk extends Model
{
    protected $fillable = [
        'name',
        'value',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Scope a query to only include active BHKs.
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
     * Get the property types associated with this BHK.
     */
    public function propertyTypes()
    {
        return $this->belongsToMany(PropertyType::class, 'bhk_property_type')
                    ->withTimestamps();
    }
}
