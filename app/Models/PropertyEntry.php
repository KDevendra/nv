<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyEntry extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'field_officer_id',
        'supply_head_id',
        'zone_id',
        'status',
        'supply_head_note',
        'allow_resubmit',
        'show_on_website',
        'admin_status',
        'admin_note',
        'admin_actioned_at',
        'admin_actioned_by',
        'submitted_at',
        'reviewed_at',
        'verified_at',
        'reviewed_by',
        'supply_head_viewed_at',
        // A
        'facility_type',
        'property_name',
        'name_full_address',
        'village_town_district', 
        'village',
        'tehsil',
        'district',
        'state',
        'country',
        'postal_address_pin',
        'nearest_highway',
        'nearest_city',
        'nearest_railway_station',
        'nearest_airport',
        'owner_contact_name',
        'owner_contact_phone',
        'owner_email',
        // B
        'tenure',
        'approved_land_use',
        'fire_noc',
        'clu_conversion_status',
        'occupancy_certificate',
        'pollution_noc',
        'pollution_category',
        // C
        'plot_area',
        'built_up_area',
        'carpet_area',
        'available_area',
        'area_unit',
        'clear_height_highest',
        'clear_height_side',
        'shed_width',
        'shed_length',
        'number_of_floors',
        'fsi_far',
        // C — docks
        'dock_door_count',
        'dock_front',
        'dock_left',
        'dock_right',
        'dock_back',
        'dock_leveller_front',
        'dock_leveller_left',
        'dock_leveller_right',
        'dock_leveller_back',
        'has_dock_leveller',
        'fire_exit_front',
        'fire_exit_left',
        'fire_exit_right',
        'fire_exit_back',
        'canopy_width_front',
        'canopy_length_front',
        'canopy_width_left',
        'canopy_length_left',
        'canopy_width_right',
        'canopy_length_right',
        'canopy_width_back',
        'canopy_length_back',
        'road_width_front',
        'road_width_left',
        'road_width_right',
        'road_width_back',
        'no_of_offices',
        'has_offices',
        'office_sizes',
        'canteen',
        'canteen_size',
        'stp_plant',
        'stp_capacity',
        'no_of_urinals',
        'no_of_closets',
        'female_washroom',
        'driver_rest_room',
        'mezzanine',
        'mezzanine_size',
        'structure_type',
        'insulation_roof',
        'insulation_side',
        'fire_sprinkler',
        'scrap_yard',
        'no_of_companies_same_premise',
        'extension_possible',
        // D
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
        'water_tank_capacity',
        'fire_fighting_system',
        'solar',
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
        'form_submited_location',
    ];

    protected $casts = [
        'available_from'               => 'date',
        'submitted_at'                 => 'datetime',
        'reviewed_at'                  => 'datetime',
        'verified_at'                  => 'datetime',
        'supply_head_viewed_at'        => 'datetime',
        'allow_resubmit'               => 'boolean',
        'show_on_website'              => 'boolean',
        'admin_actioned_at'            => 'datetime',
        // C — decimals
        'plot_area'                    => 'float',
        'built_up_area'                => 'float',
        'carpet_area'                  => 'float',
        'available_area'               => 'float',
        'clear_height_highest'         => 'float',
        'clear_height_side'            => 'float',
        'shed_width'                   => 'float',
        'shed_length'                  => 'float',
        'canopy_width_front'           => 'float',
        'canopy_length_front'          => 'float',
        'canopy_width_left'            => 'float',
        'canopy_length_left'           => 'float',
        'canopy_width_right'           => 'float',
        'canopy_length_right'          => 'float',
        'canopy_width_back'            => 'float',
        'canopy_length_back'           => 'float',
        'has_dock_leveller'            => 'boolean',
        'road_width_front'             => 'float',
        'road_width_left'              => 'float',
        'road_width_right'             => 'float',
        'road_width_back'              => 'float',
        // C — integers
        'number_of_floors'             => 'integer',
        'dock_door_count'              => 'integer',
        'dock_front'                   => 'integer',
        'dock_left'                    => 'integer',
        'dock_right'                   => 'integer',
        'dock_back'                    => 'integer',
        'dock_leveller_front'          => 'integer',
        'dock_leveller_left'           => 'integer',
        'dock_leveller_right'          => 'integer',
        'dock_leveller_back'           => 'integer',
        'fire_exit_front'              => 'integer',
        'fire_exit_left'               => 'integer',
        'fire_exit_right'              => 'integer',
        'fire_exit_back'               => 'integer',
        'no_of_offices'                => 'integer',
        'has_offices'                  => 'boolean',
        'office_sizes'                 => 'array',
        'no_of_urinals'                => 'integer',
        'no_of_closets'                => 'integer',
        'no_of_companies_same_premise' => 'integer',
        // C — booleans
        'canteen'                      => 'boolean',
        'stp_plant'                    => 'boolean',
        'female_washroom'              => 'boolean',
        'driver_rest_room'             => 'boolean',
        'mezzanine'                    => 'boolean',
        'scrap_yard'                   => 'boolean',
        'extension_possible'           => 'boolean',
        // D
        'dock_height'                  => 'float',
        // E
        'office_cabin_area'            => 'float',
        'washrooms'                    => 'integer',
        // F
        'power_sanctioned_kva'         => 'float',
        'solar'                        => 'boolean',
        // G
        'expected_rent'                => 'float',
        'expected_sale_price'          => 'float',
        'security_deposit_months'      => 'float',
        'lock_in_years'                => 'float',
        // H
        'approach_road_width'          => 'float',
        // I
        'nearest_hospital_km'          => 'float',
        'nearest_fire_station_km'      => 'float',
        'nearest_police_station_km'    => 'float',
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

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isEditable(): bool
    {
        if ($this->status === 'draft') {
            return true;
        }

        if ($this->status === 'rejected') {
            return $this->allow_resubmit === true;
        }

        return $this->status === 'recheck';
    }

    public function isViewedBySupplyHead(): bool
    {
        return $this->supply_head_viewed_at !== null;
    }

    /**
     * Decoded {address, country, lat, long} payload captured from the field
     * officer's browser. Falls back to null for legacy "lat,lng" values
     * saved before this was stored as JSON.
     */
    public function getFormSubmitedLocationDataAttribute(): ?array
    {
        if (! $this->form_submited_location) {
            return null;
        }

        $decoded = json_decode($this->form_submited_location, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Human-readable "Country | address" string, matching the field
     * officer's live location readout.
     */
    public function getFormSubmitedAddressAttribute(): ?string
    {
        $data = $this->form_submited_location_data;

        if (! $data) {
            return null;
        }

        return collect([$data['country'] ?? null, $data['address'] ?? null])
            ->filter()
            ->implode(' | ') ?: null;
    }

    public function getFormSubmitedMapsUrlAttribute(): ?string
    {
        $data = $this->form_submited_location_data;

        if ($data && ! empty($data['lat']) && ! empty($data['long'])) {
            return 'https://www.google.com/maps?q=' . urlencode($data['lat'] . ',' . $data['long']);
        }

        // Legacy records stored the raw "lat,lng" string directly.
        if ($this->form_submited_location && preg_match('/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/', trim($this->form_submited_location))) {
            return 'https://www.google.com/maps?q=' . urlencode($this->form_submited_location);
        }

        return null;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'bg-gray-100 text-gray-600',
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
            'draft'     => 'Draft',
            'submitted' => 'Under Review',
            'verified'  => 'Verified',
            'rejected'  => 'Rejected',
            'recheck'   => 'Needs Recheck',
            default     => ucfirst($this->status),
        };
    }

    public function getAdminStatusLabelAttribute(): string
    {
        return match ($this->admin_status) {
            'approved' => 'Admin Approved',
            'rejected' => 'Admin Rejected',
            default    => 'Pending Admin Review',
        };
    }

    public function adminActioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_actioned_by');
    }
}
