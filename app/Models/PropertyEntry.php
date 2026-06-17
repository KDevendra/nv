<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyEntry extends Model
{
    protected $fillable = [
        'field_officer_id',
        'supply_head_id',
        'status',
        'supply_head_note',
        'allow_resubmit',
        'submitted_at',
        'reviewed_at',
        'verified_at',
        'reviewed_by',
        // A
        'facility_type',
        'name_full_address',
        'village_town_district',
        'postal_address_pin',
        'nearest_highway',
        'nearest_city',
        'nearest_railway_station',
        'nearest_airport',
        // B
        'tenure',
        'approved_land_use',
        'fire_noc',
        'clu_conversion_status',
        'occupancy_certificate',
        // C
        'plot_area',
        'built_up_area',
        'clear_height_highest',
        'clear_height_side',
        'number_of_floors',
        'fsi_far',
        // D
        'dock_door_count',
        'dock_type',
        'dock_height',
        'truck_movement',
        // E
        'flooring_type',
        'office_cabin_area',
        'washrooms',
        'ventilation_lighting',
        // F
        'power_sanctioned_kva',
        'discom_name',
        'water_source',
        'fire_fighting_system',
        // G
        'deal_type',
        'expected_rent',
        'expected_sale_price',
        'security_deposit_months',
        'lock_in_years',
        'available_from',
        // H
        'approach_road_width',
        'top_neighbouring_companies',
        'flood_risk',
        // I
        'nearest_hospital_km',
        'nearest_fire_station_km',
        'nearest_police_station_km',
        // K
        'remarks',
        'owner_contact_name',
        'owner_contact_phone',
    ];

    protected $casts = [
        'available_from'            => 'date',
        'submitted_at'              => 'datetime',
        'reviewed_at'               => 'datetime',
        'verified_at'               => 'datetime',
        'allow_resubmit'            => 'boolean',
        'plot_area'                 => 'float',
        'built_up_area'             => 'float',
        'clear_height_highest'      => 'float',
        'clear_height_side'         => 'float',
        'number_of_floors'          => 'integer',
        'dock_door_count'           => 'integer',
        'dock_height'               => 'float',
        'office_cabin_area'         => 'float',
        'washrooms'                 => 'integer',
        'power_sanctioned_kva'      => 'float',
        'expected_rent'             => 'float',
        'expected_sale_price'       => 'float',
        'security_deposit_months'   => 'float',
        'lock_in_years'             => 'float',
        'approach_road_width'       => 'float',
        'nearest_hospital_km'       => 'float',
        'nearest_fire_station_km'   => 'float',
        'nearest_police_station_km' => 'float',
    ];

    /**
     * Auto-generate entry code on creating.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $seq = (static::max('id') ?? 0) + 1;
            $model->code = 'ZI-WH-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
        });
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function fieldOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'field_officer_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PropertyEntryPhoto::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PropertyEntryLog::class)->latest();
    }

    public function supplyHead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supply_head_id');
    }

    public function fieldReviews(): HasMany
    {
        return $this->hasMany(PropertyEntryFieldReview::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isEditable(): bool
    {
        if ($this->status === 'rejected') {
            return $this->allow_resubmit === true;
        }

        return $this->status === 'recheck';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'bg-blue-100 text-blue-800',
            'verified'  => 'bg-green-100 text-green-800',
            'rejected'  => 'bg-red-100 text-red-800',
            'recheck'   => 'bg-orange-100 text-orange-800',
            default     => 'bg-gray-100 text-gray-600',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'Pending Review',
            'verified'  => 'Verified',
            'rejected'  => 'Rejected',
            'recheck'   => 'Needs Recheck',
            default     => ucfirst($this->status),
        };
    }
}
