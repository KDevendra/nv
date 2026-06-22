<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get all areas under this region.
     */
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Get all users assigned to this region.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope a query to only include active regions.
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
     * Automatically generate slug when creating/updating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($region) {
            if (empty($region->slug)) {
                $region->slug = Str::slug($region->name);
            }
        });

        static::updating(function ($region) {
            if ($region->isDirty('name') && empty($region->slug)) {
                $region->slug = Str::slug($region->name);
            }
        });
    }
}
