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
        'property_type',
        'custom_fields',
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
        // Apartment / Flat / Studio
        'submitter_full_name',
        'submitter_phone',
        'submitter_email',
        'submitter_role',
        'company_entity_name',
        'owner_email',
        'city',
        'locality_broad_area',
        'sub_locality_society_name',
        'project_name',
        'builder_developer_name',
        'nearby_landmarks_key_distances',
        'nearby_landmarks',
        'distance_from_key_locations',
        'facing_orientation',
        'overlooking_view',
        'gps_latitude',
        'gps_longitude',
        'part_of_a_project_society',
        'project_society_name',
        'project_rera_id',
        'developer_builder_name',
        'total_towers_blocks',
        'total_units_in_project',
        'configurations_offered',
        'project_amenities',
        'approved_loan_banks',
        'unit_property_type',
        'configuration',
        'super_built_up_area',
        'floor_number',
        'units_on_this_floor',
        'no_of_bedrooms',
        'no_of_bathrooms',
        'no_of_balconies',
        'additional_rooms',
        'furnishing_status',
        'furnishing_detail',
        'parking_slots',
        'covered_parking_slots',
        'open_parking_slots',
        'property_status',
        'construction_listing_status',
        'possession_by',
        'age_of_property',
        'availability',
        'bank_loan_emi_available',
        'ownership_type',
        'title_status',
        'rera_registered',
        'rera_registration_id',
        'completion_certificate',
        'encumbrance_loan_on_property',
        'khata_property_tax_status',
        'lift_elevator',
        'power_backup',
        'water_source',
        'water_availability',
        'water_supply',
        'electricity_status',
        'gated_society',
        'security_cctv',
        'amenities_checklist',
        'pet_friendly',
        'price_on_request',
        'rent_range_band',
        'maintenance_charge',
        'sale_price_band',
        'price_per_sqft',
        'booking_amount',
        'negotiable_floor_price',
        'owner_flexibility_notes',
        'preferred_tenant',
        'non_veg_allowed',
        'pets_allowed',
        'non_veg_pets_allowed',
        'notice_period',
        'minimum_lease_agreement_term',
        'electricity_charges',
        'water_charges',
        'maintenance_inclusion',
        'utilities_who_bears',
        'currently_rented_tenanted',
        'current_monthly_rent_received',
        'rental_income_band',
        'rental_yield_roi',
        'tenant_name_profile',
        'tenant_type',
        'lease_start_date',
        'lease_tenure',
        'lock_in_remaining',
        'annual_escalation_in_lease',
        'security_deposit_held',
        'deposit_adjustment_on_sale',
        'cam_outgoings_borne_by',
        'payback_capital_value_note',
        'video_walkthrough_link',
        'virtual_tour_360_link',
        'video_virtual_tour_link',
        'property_description',
        'field_officer_name',
        'field_verified',
        'inspection_submission_date',
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
        'security_deposit_months'      => 'string',
        'lock_in_years'                => 'float',
        // H
        'approach_road_width'          => 'float',
        // I
        'nearest_hospital_km'          => 'float',
        'nearest_fire_station_km'      => 'float',
        'nearest_police_station_km'    => 'float',
        // Apartment / Flat / Studio casts
        'overlooking_view'             => 'array',
        'gps_latitude'                 => 'float',
        'gps_longitude'                => 'float',
        'configurations_offered'       => 'array',
        'project_amenities'            => 'array',
        'additional_rooms'             => 'array',
        'furnishing_detail'            => 'array',
        'amenities_checklist'          => 'array',
        'preferred_tenant'             => 'array',
        'possession_by'                => 'date',
        'lease_start_date'             => 'date',
        'inspection_submission_date'   => 'date',
        'field_verified'               => 'boolean',
        'total_towers_blocks'          => 'float',
        'total_units_in_project'       => 'float',
        'super_built_up_area'          => 'float',
        'floor_number'                 => 'float',
        'units_on_this_floor'          => 'float',
        'no_of_bedrooms'               => 'float',
        'no_of_bathrooms'              => 'float',
        'no_of_balconies'              => 'float',
        'covered_parking_slots'        => 'float',
        'open_parking_slots'           => 'float',
        'maintenance_charge'           => 'float',
        'price_per_sqft'               => 'float',
        'booking_amount'               => 'float',
        'negotiable_floor_price'       => 'float',
        'notice_period'                => 'float',
        'minimum_lease_agreement_term' => 'float',
        'current_monthly_rent_received'=> 'float',
        'rental_yield_roi'             => 'float',
        'lease_tenure'                 => 'float',
        'lock_in_remaining'            => 'float',
        'annual_escalation_in_lease'   => 'float',
        'lease_tenure'                 => 'float',
        'lock_in_remaining'            => 'float',
    ];

    protected static array $codePrefixes = [
        'warehouse'                         => 'ZI-WH-',
        'apartment_flat_studio'            => 'ZI-RA-',
        'house_villa_farmhouse'            => 'ZI-RH-',
        'builder_floor'                    => 'ZI-RB-',
        'residential_plot_land'            => 'ZI-RP-',
        'service_apartment_pg'             => 'ZI-RS-',
        'office_space'                     => 'ZI-CO-',
        'retail_shop_showroom'             => 'ZI-CR-',
        'sez_eou_stpi_unit'                => 'ZI-CS-',
        'factory_manufacturing_industrial' => 'ZI-CF-',
        'commercial_institutional_land'    => 'ZI-CL-',
        'agricultural_farm_land'           => 'ZI-CA-',
        'multi_tenant_building'            => 'ZI-CB-',
        'hotel_resort_guesthouse_banquet'  => 'ZI-CH-',
    ];

    /**
     * Auto-generate entry code on creating.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $prefix = static::$codePrefixes[$model->property_type ?? 'warehouse'] ?? 'ZI-WH-';

            $existingCodes = static::withTrashed()
                ->where('code', 'LIKE', $prefix . '%')
                ->pluck('code');

            $maxSeq = 0;
            foreach ($existingCodes as $code) {
                $suffix = substr($code, strlen($prefix));
                if (is_numeric($suffix)) {
                    $num = (int) $suffix;
                    if ($num > $maxSeq) {
                        $maxSeq = $num;
                    }
                }
            }

            $seq = $maxSeq + 1;
            $model->code = $prefix . str_pad((string) $seq, 3, '0', STR_PAD_LEFT);
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

    public function scopeOfType($query, string $type)
    {
        return $query->where('property_type', $type);
    }

    /**
     * The sole gate for public visibility on the /properties listing —
     * a deliberate two-stage gate (internal review status is a separate
     * concern), matching what the admin report's Approve/Reject/toggle-
     * website actions already control. Never join or union another table
     * here; property_entries is the only source for that page.
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('admin_status', 'approved')->where('show_on_website', true);
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

    public function getOwnerEditUrlAttribute(): string
    {
        if (!empty($this->property_type) && $this->property_type !== 'warehouse') {
            $slug = str_replace('_', '-', $this->property_type);
            $routeName = "owner.properties.{$slug}.edit";
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                return route($routeName, $this);
            }
        }

        return route('owner.properties.edit', $this);
    }

    public function adminActioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_actioned_by');
    }

    // ── Admin detail view — type-aware section/field map ─────────────────────

    /**
     * Columns that are workflow/system plumbing rather than submitted data.
     * Excluded from the admin detail view's "Other Data" catch-all, since
     * they're already surfaced by the page's own header/status/log blocks.
     */
    private const NON_DATA_COLUMNS = [
        'id', 'code', 'created_at', 'updated_at', 'deleted_at',
        'field_officer_id', 'supply_head_id', 'zone_id', 'reviewed_by', 'admin_actioned_by',
        'status', 'admin_status', 'admin_note', 'supply_head_note',
        'submitted_at', 'reviewed_at', 'verified_at', 'admin_actioned_at', 'supply_head_viewed_at',
        'allow_resubmit', 'show_on_website', 'property_type', 'custom_fields',
        'form_submited_location', 'form_submited_address', 'form_submited_maps_url',
    ];

    /**
     * The property_type this row should be *rendered* as. Legacy rows
     * predate the property_type column and store NULL — they're all
     * warehouse entries, matching how $codePrefixes and getOwnerEditUrl
     * already treat an absent type.
     */
    public function getResolvedPropertyTypeAttribute(): string
    {
        return $this->property_type ?: 'warehouse';
    }

    /**
     * Ordered section => [column => normalised field definition] map for
     * this row's type, with every field definition expanded to the full
     * array form so the view never has to branch on shape.
     */
    public function sectionMap(): array
    {
        $raw = config('property_entry_sections.' . $this->resolved_property_type)
            ?? config('property_entry_sections.warehouse', []);

        $out = [];
        foreach ($raw as $section => $fields) {
            foreach ($fields as $column => $definition) {
                $out[$section][$column] = is_array($definition)
                    ? $definition + ['label' => $column, 'type' => 'text', 'tier' => null, 'wide' => false]
                    : ['label' => $definition, 'type' => 'text', 'tier' => null, 'wide' => false];
            }
        }

        return $out;
    }

    /**
     * Decoded custom_fields payload. The 12 dedicated wizard forms route
     * every submitted key without a matching real column into here, so for
     * those types it holds the majority of the submission.
     */
    public function customFieldsArray(): array
    {
        $custom = $this->custom_fields;
        if (is_string($custom)) {
            $custom = json_decode($custom, true);
        }

        return is_array($custom) ? $custom : [];
    }

    /**
     * Value for a mapped field, wherever it actually lives. Real columns
     * win; otherwise fall back to the custom_fields blob, since most of the
     * 12 dedicated types' fields have no dedicated column and would
     * otherwise render as "—" on the admin detail view despite having been
     * filled in.
     */
    public function fieldValue(string $column): mixed
    {
        if (array_key_exists($column, $this->getAttributes()) || $this->hasCast($column)) {
            return $this->$column;
        }

        return $this->customFieldsArray()[$column] ?? null;
    }

    /**
     * Submitted values present on the row (or in custom_fields) that this
     * type's section map doesn't account for — legacy or orphaned data.
     * Surfaced under an "Other Data" section rather than silently dropped,
     * so nothing a field officer entered can disappear from admin review.
     * Only non-empty values are included: every mapped field already renders
     * as "—" when empty, and listing every unmapped empty column here would
     * bury the real leftovers under a wall of blanks.
     */
    public function unmappedData(): array
    {
        $mapped = collect($this->sectionMap())->flatMap(fn ($fields) => array_keys($fields))->all();
        $skip = array_merge($mapped, self::NON_DATA_COLUMNS);

        $out = [];
        foreach ($this->getAttributes() as $column => $value) {
            if (in_array($column, $skip, true)) {
                continue;
            }
            $cast = $this->$column;
            if ($cast === null || $cast === '' || (is_array($cast) && $cast === [])) {
                continue;
            }
            $out[$column] = $cast;
        }

        // custom_fields holds whatever the 12 dedicated wizard forms couldn't
        // map onto a real column — genuinely submitted data, so anything the
        // section map doesn't already render belongs here.
        foreach ($this->customFieldsArray() as $key => $value) {
            if (in_array($key, $skip, true)) {
                continue;
            }
            if ($value !== null && $value !== '' && $value !== []) {
                $out[$key] = $value;
            }
        }

        ksort($out);

        return $out;
    }

    // ── Public-listing presentation (WEBSITE-tier fields only) ───────────────
    //
    // These back the /properties listing and its cards. They intentionally
    // read only fields the Excel spec marks WEBSITE-tier (never VERIFIED or
    // INTERNAL), and only real columns — most of the 12 dedicated wizard
    // forms besides warehouse/apartment-flat-studio don't yet map their
    // type-specific fields onto shared columns (they fall into custom_fields
    // instead, a separate known gap), so those types render a leaner but
    // honest card rather than fabricating detail that isn't reliably there.

    /**
     * Warehouse stores its city in `nearest_city`; every dedicated wizard
     * form (including apartment-flat-studio) stores it in `city`. This is
     * the single place that difference should be reconciled.
     */
    public function getPublicCityAttribute(): ?string
    {
        return $this->city ?: $this->nearest_city;
    }

    /**
     * WEBSITE-tier title: property name first, falling back to the
     * INTERNAL-tier `name_full_address` truncated for teaser use (per the
     * apartment spec sheet, full address is INTERNAL — never publish the
     * whole thing, just enough to identify the listing), then facility
     * type as a last resort for warehouse rows with no name at all.
     */
    public function getPublicTitleAttribute(): ?string
    {
        if ($this->property_name) {
            return $this->property_name;
        }

        if ($this->locality_broad_area || $this->public_city) {
            return collect([$this->locality_broad_area, $this->public_city])->filter()->implode(', ');
        }

        return $this->facility_type;
    }

    /**
     * The type-appropriate "middle line" detail — never a residential field
     * on a non-residential card. Falls back to the property_type's own
     * label from config('property_types') when the type has no dedicated
     * detail field populated.
     */
    public function getPublicDetailLineAttribute(): ?string
    {
        $typeLabel = config("property_types.types.{$this->property_type}.label", $this->property_type);

        $detail = match ($this->property_type) {
            'apartment_flat_studio' => $this->configuration,
            'warehouse'             => $this->facility_type,
            default                 => $this->unit_property_type ?: $this->facility_type,
        };

        return collect([$this->public_city, $detail, $detail ? null : $typeLabel])->filter()->implode(' • ');
    }

    /**
     * WEBSITE-tier price display — always a rounded-down band, never the
     * exact internal figure. Picks whichever band the row actually has
     * rather than trusting `deal_type`, since most of the 12 dedicated
     * forms don't populate that shared column today.
     */
    public function getPublicPriceLabelAttribute(): string
    {
        if ($this->rent_range_band) {
            return 'Rent';
        }
        if ($this->sale_price_band) {
            return 'Price';
        }
        return 'Price';
    }

    public function getPublicPriceValueAttribute(): string
    {
        return $this->rent_range_band ?: ($this->sale_price_band ?: 'Price on Request');
    }

    /**
     * Type-aware amenity tags, capped at 4. Only reads columns that are
     * genuinely reliable for that type today — apartment-flat-studio's
     * `amenities_checklist` (a real array-cast column) and warehouse's own
     * real fillable columns already used for it before this rewrite. Every
     * other type returns an empty list rather than showing amenities that
     * weren't actually entered for that row.
     */
    public function getPublicAmenitiesAttribute(): array
    {
        if ($this->property_type === 'apartment_flat_studio') {
            return array_slice((array) ($this->amenities_checklist ?? []), 0, 4);
        }

        if ($this->property_type === 'warehouse') {
            return collect([
                $this->dock_door_count ? "{$this->dock_door_count} Dock Doors" : null,
                $this->clear_height_highest ? "{$this->clear_height_highest}ft Height" : null,
                $this->power_sanctioned_kva ? "{$this->power_sanctioned_kva} KVA Power" : null,
                $this->fire_fighting_system ? 'Fire Fighting System' : null,
            ])->filter()->take(4)->values()->all();
        }

        return [];
    }
}
