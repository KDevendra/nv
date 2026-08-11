<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Zone extends Model
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
     * Field officers assigned to this zone (one zone per field officer).
     */
    public function fieldOfficers()
    {
        return $this->hasMany(User::class, 'zone_id')->where('role', 'field_officer');
    }

    /**
     * Every user whose single zone is this one, regardless of role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'zone_id');
    }

    /**
     * Supply heads covering this zone (a supply head can cover many zones).
     */
    public function supplyHeads()
    {
        return $this->belongsToMany(User::class, 'supply_head_zone', 'zone_id', 'user_id')->withTimestamps();
    }

    /**
     * Property entries submitted inside this zone.
     */
    public function propertyEntries()
    {
        return $this->hasMany(PropertyEntry::class, 'zone_id');
    }

    /**
     * Scope a query to only include active zones.
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
     * Pick the supply head an entry from this zone should be assigned to.
     * When several supply heads cover the zone, the one currently holding
     * the fewest entries takes it, so the load stays even. Every supply
     * head on the zone still sees the entry (see zone-based listing
     * queries) — this only decides the record owner.
     */
    public function primarySupplyHeadId(): ?int
    {
        return $this->supplyHeads()
            ->where('users.is_active', true)
            ->withCount(['supplyHeadEntries as open_entry_count' => function ($q) {
                $q->whereIn('status', ['submitted', 'recheck']);
            }])
            ->orderBy('open_entry_count')
            ->orderBy('users.id')
            ->first()?->id;
    }

    /**
     * Automatically generate slug when creating/updating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($zone) {
            if (empty($zone->slug)) {
                $zone->slug = Str::slug($zone->name);
            }
        });

        static::updating(function ($zone) {
            if ($zone->isDirty('name') && empty($zone->slug)) {
                $zone->slug = Str::slug($zone->name);
            }
        });
    }
}
