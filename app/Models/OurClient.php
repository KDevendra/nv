<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurClient extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset($this->logo) : null;
    }
}
