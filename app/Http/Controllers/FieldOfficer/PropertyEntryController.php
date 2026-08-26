<?php

namespace App\Http\Controllers\FieldOfficer;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
use App\Models\PropertyFieldConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PropertyEntryController extends Controller
{
    private const PHOTO_SLOTS = [
        0 => 'Front / exterior',
        1 => 'Interior — full floor',
        2 => 'Roof / height shot',
        3 => 'Dock doors close-up',
        4 => 'Office / cabin',
        5 => 'Fire system',
        6 => 'Approach road',
        7 => 'Fire NOC document',
    ];

    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $userId = auth()->id();

        $query = PropertyEntry::where('field_officer_id', $userId);

        if ($request->filled('status')) {
            // A stat card was clicked for a specific status — show every
            // matching entry (so the list count matches the card's count),
            // bypassing the default view's recent-verified-only narrowing.
            $query->where('status', $request->string('status'));
        } else {
            // Default (no status filter): verified entries verified less
            // than 6 hours ago are still shown; verified 6+ hours ago are
            // excluded from the table.
            $query->where(function ($q) {
                $q->where('status', '!=', 'verified')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'verified')
                            ->where('verified_at', '>', now()->subHours(6));
                    });
            });
        }

        $query->latest();

        $entries = $query->paginate(15)->appends($request->query());

        // Counters always reflect the full dataset (no 6h filter)
        $counters = [
            'total' => PropertyEntry::where('field_officer_id', $userId)->count(),
            'draft' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'draft')->count(),
            'submitted' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'submitted')->count(),
            'verified' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'verified')->count(),
            'recheck' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'recheck')->count(),
            'rejected' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('field.properties.index', compact('entries', 'counters'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $property = null;
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = []; // No remarks on create
        return view('field.properties.create', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        // Increase memory limit for large form processing
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300); // 5 minutes

        // Debug session and auth state
        \Log::info('PropertyEntry Store - Session Debug:', [
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->role,
            'csrf_token' => $request->header('X-CSRF-TOKEN') ?: $request->input('_token'),
            'form_size' => strlen($request->getContent()),
            'memory_usage' => memory_get_usage(true),
        ]);

        abort_if(auth()->user()->role !== 'field_officer', 403);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');

        try {
            $data = $this->validateEntry($request, $isDraft);
        } catch (ValidationException $e) {
            // Real, human-readable, per-field messages (e.g. "The property
            // name field is required.") are already inside $e->errors().
            // Let Laravel's default handling take over so the $errors bag
            // reaches the view intact — the blade's @error('field') tags
            // and step-error badges depend on this. Do NOT flatten this
            // into one generic message.
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('Store Form validation failed unexpectedly:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'form_error' => 'Something went wrong while saving your entry. Please try again, and if the problem continues, contact support with code: ' . now()->format('YmdHis'),
                ]);
        }

        // Decode office_sizes JSON string to array for proper storage
        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        // Zone comes from the officer's own assignment and decides which
        // supply head takes the entry — the officer never picks either.
        $zoneId = auth()->user()->zone_id;
        $supplyHeadId = $this->resolveZoneSupplyHeadId($zoneId);

        if ($isDraft) {
            // Save as draft — no status change, no submitted_at, no log
            $entry = PropertyEntry::create(array_merge($data, [
                'field_officer_id' => auth()->id(),
                'zone_id' => $zoneId,
                'supply_head_id' => $supplyHeadId,
                'status' => 'draft',
                'submitted_at' => null,
                'area_unit' => $request->input('area_unit', 'sq_ft'),
            ]));

            $this->handlePhotos($entry, $request);

            return redirect()->route('field.properties.edit', $entry)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        // Default: submit
        $entry = PropertyEntry::create(array_merge($data, [
            'field_officer_id' => auth()->id(),
            'zone_id' => $zoneId,
            'supply_head_id' => $supplyHeadId,
            'status' => 'submitted',
            'submitted_at' => now(),
            'area_unit' => $request->input('area_unit', 'sq_ft'),
        ]));

        $this->handlePhotos($entry, $request);

        PropertyEntryLog::logAction($entry, 'submitted', null, 'submitted');

        return redirect()->route('field.dashboard')
            ->with('success', 'Property entry submitted successfully. Code: ' . $entry->code);
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        // Block access to submitted, verified, and rejected entries (only draft and recheck can be viewed)
        if (in_array($property->status, ['submitted', 'verified', 'rejected'])) {
            abort(403, 'This entry is no longer accessible for viewing. Please use the edit option if available.');
        }

        $property->load('photos');
        $property = null;
        $slots = self::PHOTO_SLOTS;

        return view('field.properties.show', compact('property', 'slots'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        // Check if the property is editable using the model's isEditable() method
        abort_if(!$property->isEditable(), 403, 'This entry cannot be edited. It may have been permanently rejected or is in a non-editable state.');

        $property->load(['photos', 'fieldReviews']);
        $property = null;
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();

        // Build field reviews map for easy lookup in the form (field_name => remark)
        $fieldRemarks = $property->fieldReviews()
            ->where('is_correct', false)
            ->whereNotNull('remark')
            ->pluck('remark', 'field_name')
            ->toArray();

        // Build list of fields marked as correct by supply head (used to lock them on rejected+allow_resubmit)
        $correctFields = $property->fieldReviews()
            ->where('is_correct', true)
            ->pluck('field_name')
            ->toArray();

        return view('field.properties.edit', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks', 'correctFields'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        // Increase memory limit for large form processing
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300); // 5 minutes

        // Debug session and auth state
        \Log::info('PropertyEntry Update - Session Debug:', [
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->role,
            'property_id' => $property->id,
            'csrf_token' => $request->header('X-CSRF-TOKEN') ?: $request->input('_token'),
            'form_size' => strlen($request->getContent()),
            'memory_usage' => memory_get_usage(true),
        ]);

        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        // Check if the property is editable using the model's isEditable() method
        abort_if(!$property->isEditable(), 403, 'This entry cannot be edited. It may have been permanently rejected or is in a non-editable state.');

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');
        $oldStatus = $property->status;

        try {
            $data = $this->validateEntry($request, $isDraft);
        } catch (ValidationException $e) {
            // Same reasoning as store(): let the real per-field messages
            // flow through Laravel's normal error-bag redirect instead of
            // being replaced with a generic sentence.
            throw $e;
        } catch (\Throwable $e) {
            \Log::error('Update Form validation failed unexpectedly:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'property_id' => $property->id,
                'session_id' => session()->getId(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'form_error' => 'Something went wrong while saving your entry. Please try again, and if the problem continues, contact support with code: ' . now()->format('YmdHis'),
                ]);
        }

        // Decode office_sizes JSON string to array for proper storage
        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        // Keep the entry pinned to the officer's zone, and (re)route it to a
        // supply head of that zone whenever it doesn't have one yet.
        $zoneId = $property->zone_id ?? auth()->user()->zone_id;
        $routing = [
            'zone_id' => $zoneId,
            'supply_head_id' => $property->supply_head_id ?: $this->resolveZoneSupplyHeadId($zoneId),
        ];

        if ($isDraft) {
            // Save as draft — keep current status if already draft, otherwise set to draft
            $property->update(array_merge($data, $routing, [
                'status' => 'draft',
                'submitted_at' => null,
                'allow_resubmit' => null,
                'area_unit' => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
            ]));

            $this->handlePhotos($property, $request);

            return redirect()->route('field.properties.edit', $property)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        // Default: submit
        $property->update(array_merge($data, $routing, [
            'status' => 'submitted',
            'submitted_at' => now(),
            'allow_resubmit' => null,
            'area_unit' => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
        ]));

        $this->handlePhotos($property, $request);

        PropertyEntryLog::logAction(
            $property,
            $oldStatus === 'rejected' ? 'resubmitted_after_rejection' : 'resubmitted',
            $oldStatus,
            'submitted'
        );

        return redirect()->route('field.properties.index')
            ->with('success', 'Entry resubmitted successfully. Code: ' . $property->code);
    }

    // ── Zone routing ──────────────────────────────────────────────────────────

    /**
     * Supply head that should own an entry submitted in the given zone.
     * Returns null when the zone has no active supply head — the entry
     * still carries its zone, so it surfaces for every supply head added
     * to that zone later.
     */
    private function resolveZoneSupplyHeadId(?int $zoneId): ?int
    {
        $fromZone = $zoneId ? \App\Models\Zone::find($zoneId)?->primarySupplyHeadId() : null;

        // Transitional fallback for officers created before zones existed
        // and not yet assigned one — keeps their entries from landing with
        // no reviewer at all.
        return $fromZone ?? auth()->user()->supply_head_id;
    }

    // ── Validation ────────────────────────────────────────────────────────────

    /**
     * Human-readable labels used to build friendly validation messages
     * (e.g. "The Owner E-mail field is required." instead of the raw
     * snake_case attribute name "owner_email").
     */
    private const FIELD_LABELS = [
        'facility_type' => 'Facility Type',
        'property_name' => 'Name of Property',
        'name_full_address' => 'Address',
        'postal_address_pin' => 'PIN Code',
        'village' => 'Village',
        'tehsil' => 'Tehsil',
        'district' => 'District',
        'state' => 'State',
        'country' => 'Country',
        'nearest_highway' => 'Nearest Highway',
        'nearest_city' => 'Nearest City',
        'nearest_railway_station' => 'Nearest Railway Station',
        'nearest_airport' => 'Nearest Airport',
        'owner_contact_name' => 'Owner Name',
        'owner_contact_phone' => 'Owner Contact Number',
        'owner_email' => 'Owner E-mail',
        'tenure' => 'Tenure',
        'approved_land_use' => 'Approved Land Use',
        'fire_noc' => 'Fire NOC Availability',
        'clu_conversion_status' => 'CLU / Conversion Status',
        'occupancy_certificate' => 'Occupancy Certificate',
        'pollution_noc' => 'Pollution NOC',
        'pollution_category' => 'Pollution Category',
        'area_unit' => 'Area Unit',
        'plot_area' => 'Plot Area',
        'built_up_area' => 'Built-up Area',
        'carpet_area' => 'Carpet Area',
        'available_area' => 'Available Area',
        'clear_height_highest' => 'Clear Height — Highest',
        'clear_height_side' => 'Clear Height — Side Wall',
        'shed_width' => 'Shed Width',
        'shed_length' => 'Shed Length',
        'number_of_floors' => 'Number of Floors',
        'fsi_far' => 'FSI / FAR',
        'dock_door_count' => 'Total Dock Doors',
        'dock_front' => 'Dock Doors — Front',
        'dock_left' => 'Dock Doors — Left',
        'dock_right' => 'Dock Doors — Right',
        'dock_back' => 'Dock Doors — Back',
        'dock_leveller_front' => 'Dock Leveller — Front',
        'dock_leveller_left' => 'Dock Leveller — Left',
        'dock_leveller_right' => 'Dock Leveller — Right',
        'dock_leveller_back' => 'Dock Leveller — Back',
        'fire_exit_front' => 'Fire Exit — Front',
        'fire_exit_left' => 'Fire Exit — Left',
        'fire_exit_right' => 'Fire Exit — Right',
        'fire_exit_back' => 'Fire Exit — Back',
        'canopy_width_front' => 'Canopy Width — Front',
        'canopy_length_front' => 'Canopy Length — Front',
        'canopy_width_left' => 'Canopy Width — Left',
        'canopy_length_left' => 'Canopy Length — Left',
        'canopy_width_right' => 'Canopy Width — Right',
        'canopy_length_right' => 'Canopy Length — Right',
        'canopy_width_back' => 'Canopy Width — Back',
        'canopy_length_back' => 'Canopy Length — Back',
        'has_dock_leveller' => 'Dock Levellers Available?',
        'road_width_front' => 'Road Width — Front',
        'road_width_left' => 'Road Width — Left',
        'road_width_right' => 'Road Width — Right',
        'road_width_back' => 'Road Width — Back',
        'no_of_offices' => 'No. of Offices',
        'has_offices' => 'Offices Available?',
        'office_sizes' => 'Office Sizes',
        'canteen' => 'Canteen',
        'canteen_size' => 'Canteen Size',
        'stp_plant' => 'STP Plant',
        'stp_capacity' => 'STP Capacity',
        'no_of_urinals' => 'No. of Urinals',
        'no_of_closets' => 'No. of Closets',
        'female_washroom' => 'Female Washroom',
        'driver_rest_room' => 'Driver Rest Room',
        'mezzanine' => 'Mezzanine',
        'mezzanine_size' => 'Mezzanine Size',
        'structure_type' => 'Structure Type',
        'insulation_roof' => 'Roof Insulation',
        'insulation_side' => 'Side Insulation',
        'fire_sprinkler' => 'Fire Sprinkler',
        'scrap_yard' => 'Scrap Yard',
        'no_of_companies_same_premise' => 'No. of Companies in Same Premise',
        'extension_possible' => 'Extension Possible?',
        'dock_type' => 'Dock Type',
        'dock_height' => 'Dock Height',
        'truck_movement' => 'Truck Movement',
        'flooring_type' => 'Flooring Type',
        'office_cabin_area' => 'Office / Cabin Area',
        'washrooms' => 'No. of Washrooms',
        'ventilation_lighting' => 'Ventilation & Lighting',
        'power_sanctioned_kva' => 'Power Sanctioned (KVA)',
        'discom_name' => 'DISCOM Name',
        'water_source' => 'Water Source',
        'water_tank_capacity' => 'Water Tank Capacity',
        'fire_fighting_system' => 'Fire Fighting System',
        'solar' => 'Solar',
        'deal_type' => 'Lease / Sale Status',
        'expected_rent' => 'Expected Rent',
        'expected_sale_price' => 'Expected Sale Price',
        'security_deposit_months' => 'Security Deposit (months)',
        'lock_in_years' => 'Lock-in Period (years)',
        'available_from' => 'Available From Date',
        'approach_road_width' => 'Approach Road Width',
        'top_neighbouring_companies' => 'Top Neighbouring Companies',
        'flood_risk' => 'Flood / Water-Logging Risk',
        'nearest_hospital_km' => 'Nearest Hospital (km)',
        'nearest_fire_station_km' => 'Nearest Fire Station (km)',
        'nearest_police_station_km' => 'Nearest Police Station (km)',
        'remarks' => 'Remarks / Observations',
        'photos' => 'Photographs',
        'photos.*' => 'Photograph',
        'form_submited_location' => 'Submitted Location',
    ];

    private function validateEntry(Request $request, bool $isDraft = false): array
    {
        $configs = PropertyFieldConfig::allKeyed();

        // Base type constraints per field — independent of required/nullable
        $typeRules = [
            // A
            'facility_type' => 'string',
            'property_name' => 'string|max:255',
            'name_full_address' => 'string',
            'village' => 'string|max:255',
            'tehsil' => 'string|max:255',
            'district' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:255',
            'postal_address_pin' => ['string', 'max:6', 'regex:/^[0-9]{6}$/'],
            'nearest_highway' => 'string|max:255',
            'nearest_city' => 'string|max:255',
            'nearest_railway_station' => 'string|max:255',
            'nearest_airport' => 'string|max:255',
            'owner_contact_name' => 'string|max:255',
            'owner_contact_phone' => ['string', 'max:10', 'regex:/^[6-9][0-9]{9}$/'],
            'owner_email' => 'email|max:255',
            // B
            'tenure' => 'string|max:50',
            'approved_land_use' => 'string|max:100',
            'fire_noc' => 'string|max:50',
            'clu_conversion_status' => 'string|max:255',
            'occupancy_certificate' => 'string|max:50',
            'pollution_noc' => 'string|max:50',
            'pollution_category' => 'string|max:100',
            // C — dimensions
            'area_unit' => 'string|in:sq_ft,sq_mt,sq_yd',
            'plot_area' => 'numeric|min:0',
            'built_up_area' => 'numeric|min:0',
            'carpet_area' => 'numeric|min:0',
            'available_area' => 'numeric|min:0',
            'clear_height_highest' => 'numeric|min:0',
            'clear_height_side' => 'numeric|min:0',
            'shed_width' => 'numeric|min:0',
            'shed_length' => 'numeric|min:0',
            'number_of_floors' => 'integer|min:0',
            'fsi_far' => 'string|max:50',
            // C — docks/sides
            'dock_door_count' => 'integer|min:0',
            'dock_front' => 'integer|min:0',
            'dock_left' => 'integer|min:0',
            'dock_right' => 'integer|min:0',
            'dock_back' => 'integer|min:0',
            'dock_leveller_front' => 'integer|min:0',
            'dock_leveller_left' => 'integer|min:0',
            'dock_leveller_right' => 'integer|min:0',
            'dock_leveller_back' => 'integer|min:0',
            'fire_exit_front' => 'integer|min:0',
            'fire_exit_left' => 'integer|min:0',
            'fire_exit_right' => 'integer|min:0',
            'fire_exit_back' => 'integer|min:0',
            'canopy_width_front' => 'numeric|min:0',
            'canopy_length_front' => 'numeric|min:0',
            'canopy_width_left' => 'numeric|min:0',
            'canopy_length_left' => 'numeric|min:0',
            'canopy_width_right' => 'numeric|min:0',
            'canopy_length_right' => 'numeric|min:0',
            'canopy_width_back' => 'numeric|min:0',
            'canopy_length_back' => 'numeric|min:0',
            'has_dock_leveller' => 'boolean',
            'road_width_front' => 'numeric|min:0',
            'road_width_left' => 'numeric|min:0',
            'road_width_right' => 'numeric|min:0',
            'road_width_back' => 'numeric|min:0',
            'no_of_offices' => 'integer|min:0',
            'has_offices' => 'boolean',
            'office_sizes' => 'nullable|string',
            'canteen' => 'boolean',
            'canteen_size' => 'string|max:255',
            'stp_plant' => 'boolean',
            'stp_capacity' => 'string|max:255',
            'no_of_urinals' => 'integer|min:0',
            'no_of_closets' => 'integer|min:0',
            'female_washroom' => 'boolean',
            'driver_rest_room' => 'boolean',
            'mezzanine' => 'boolean',
            'mezzanine_size' => 'string|max:255',
            'structure_type' => 'string|max:100',
            'insulation_roof' => 'string|max:100',
            'insulation_side' => 'string|max:100',
            'fire_sprinkler' => 'string|max:50',
            'scrap_yard' => 'boolean',
            'no_of_companies_same_premise' => 'integer|min:0',
            'extension_possible' => 'boolean',
            // D
            'dock_type' => 'string|max:100',
            'dock_height' => 'numeric|min:0',
            'truck_movement' => 'string|max:100',
            // E
            'flooring_type' => 'string|max:100',
            'office_cabin_area' => 'numeric|min:0',
            'washrooms' => 'integer|min:0',
            'ventilation_lighting' => 'string|max:50',
            // F
            'power_sanctioned_kva' => 'numeric|min:0',
            'discom_name' => 'string|max:255',
            'water_source' => 'string|max:100',
            'water_tank_capacity' => 'string|max:100',
            'fire_fighting_system' => 'string|max:100',
            'solar' => 'boolean',
            // G
            'deal_type' => 'string|max:50',
            'expected_rent' => 'numeric|min:0',
            'expected_sale_price' => 'numeric|min:0',
            'security_deposit_months' => 'nullable|string|max:255',
            'lock_in_years' => 'numeric|min:0|max:99',
            'available_from' => 'date',
            // H
            'approach_road_width' => 'numeric|min:0',
            'top_neighbouring_companies' => 'string',
            'flood_risk' => 'string|max:50',
            // I
            'nearest_hospital_km' => 'numeric|min:0',
            'nearest_fire_station_km' => 'numeric|min:0',
            'nearest_police_station_km' => 'numeric|min:0',
            // K
            'remarks' => 'string',
            // Metadata — captured client-side via the browser's Geolocation API, not driven by PropertyFieldConfig
            'form_submited_location' => 'nullable|string|max:1000',
        ];

        $rules = [];

        foreach ($typeRules as $field => $typeConstraint) {
            $cfg = $configs->get($field);

            // If config says keep_field = false, skip validation entirely
            if ($cfg && $cfg->keep_field === false) {
                continue;
            }

            // For drafts, all fields are nullable regardless of config
            // For submissions, use config & conditional rules to determine required vs nullable
            if ($isDraft) {
                $presence = 'nullable';
            } else {
                $isMandatory = $cfg && $cfg->mandatory_field;
                if (!$isMandatory) {
                    $presence = 'nullable';
                } else {
                    if ($field === 'canteen_size') {
                        $presence = ((string)$request->input('canteen') === '1') ? 'required' : 'nullable';
                    } elseif ($field === 'stp_capacity') {
                        $presence = ((string)$request->input('stp_plant') === '1') ? 'required' : 'nullable';
                    } elseif ($field === 'mezzanine_size') {
                        $presence = ((string)$request->input('mezzanine') === '1') ? 'required' : 'nullable';
                    } elseif (in_array($field, ['expected_rent', 'security_deposit_months', 'lock_in_years'])) {
                        $presence = in_array($request->input('deal_type'), ['Lease', 'Both']) ? 'required' : 'nullable';
                    } elseif ($field === 'expected_sale_price') {
                        $presence = in_array($request->input('deal_type'), ['Sale', 'Both']) ? 'required' : 'nullable';
                    } elseif (in_array($field, ['dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back'])) {
                        // Individually optional — the real "must have a real
                        // count when levellers are available" requirement is
                        // enforced as a group (sum > 0) further down, since no
                        // single direction is inherently the mandatory one.
                        $presence = 'nullable';
                    } else {
                        $presence = 'required';
                    }
                }
            }

            // Build rule — typeConstraint can be a string or an array
            if (is_array($typeConstraint)) {
                $rules[$field] = array_merge([$presence], $typeConstraint);
            } else {
                $rules[$field] = $presence . '|' . $typeConstraint;
            }
        }

        // Photos are always optional — not driven by field config
        $rules['photos'] = 'nullable|array';
        $rules['photos.*'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';

        $data = $request->validate($rules, [
            'photos.*.image' => 'Only camera photos are allowed for :attribute.',
            'postal_address_pin.regex' => 'PIN code must be exactly 6 digits.',
            'owner_contact_phone.regex' => 'Contact number must be a valid 10-digit Indian mobile number.',
        ], self::FIELD_LABELS); // <-- human-readable :attribute names instead of snake_case

        // Auto-clear conditional fields if parent answer is No / deal_type doesn't match
        if (isset($data['canteen']) && (string)$data['canteen'] === '0') {
            $data['canteen_size'] = null;
        }
        if (isset($data['stp_plant']) && (string)$data['stp_plant'] === '0') {
            $data['stp_capacity'] = null;
        }
        if (isset($data['mezzanine']) && (string)$data['mezzanine'] === '0') {
            $data['mezzanine_size'] = null;
        }
        if (isset($data['deal_type'])) {
            if ($data['deal_type'] === 'Sale') {
                $data['expected_rent'] = null;
                $data['security_deposit_months'] = null;
                $data['lock_in_years'] = null;
            } elseif ($data['deal_type'] === 'Lease') {
                $data['expected_sale_price'] = null;
            }
        }

        // Dock levellers: answering "Yes" is only meaningful with a real
        // count somewhere — mirrors the client-side check that requires the
        // four direction fields (as a group) once has_dock_leveller = 1.
        if (!$isDraft && (string) $request->input('has_dock_leveller') === '1') {
            $levellerFields = ['dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back'];
            $anyMandatory = collect($levellerFields)->contains(fn ($f) => (bool) optional($configs->get($f))->mandatory_field);

            if ($anyMandatory) {
                $sum = collect($levellerFields)->sum(fn ($f) => (int) $request->input($f, 0));
                if ($sum <= 0) {
                    throw ValidationException::withMessages([
                        'dock_leveller_front' => 'Enter at least one dock leveller count (front/left/right/back) since levellers are marked as available.',
                    ]);
                }
            }
        }

        return $data;
    }

    // ── Photo Handler ─────────────────────────────────────────────────────────

    private function handlePhotos(PropertyEntry $entry, Request $request): void
    {
        if (!$request->hasFile('photos')) {
            return;
        }

        $manager = new ImageManager(new Driver());

        foreach (self::PHOTO_SLOTS as $index => $slotLabel) {
            $inputKey = 'photos.' . $index;

            if (!$request->hasFile($inputKey)) {
                continue;
            }

            $file = $request->file($inputKey);

            $image = $manager->read($file->getRealPath());
            $webpData = $image->toWebp(75)->toString();

            $publicPath = public_path('images/property_photos');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filename = $entry->id . '_' . $index . '_' . time() . '.webp';
            $fullPath = $publicPath . '/' . $filename;
            file_put_contents($fullPath, $webpData);

            $old = $entry->photos()->where('slot_label', $slotLabel)->first();
            if ($old) {
                $oldPath = public_path('images/property_photos/' . basename($old->file_path));
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $old->delete();
            }

            $entry->photos()->create([
                'slot_label' => $slotLabel,
                'file_path' => 'images/property_photos/' . $filename,
            ]);
        }
    }

    // ── Reverse Geocode (Mappls proxy) ───────────────────────────────────────
    // Called client-side from the form's live location readout. Proxied
    // through the backend (rather than calling Mappls directly from the
    // browser) so the access token never appears in client-side JS/network
    // requests, and only this server's IP needs to be whitelisted on the
    // Mappls dashboard — not every field officer's changing mobile IP.
    public function reverseGeocode(Request $request): JsonResponse
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $token = config('services.mappls.access_token');

        if (!$token) {
            return response()->json(['address' => null, 'country' => null, 'error' => 'not_configured']);
        }

        try {
            $response = Http::timeout(5)->get('https://search.mappls.com/search/address/rev-geocode', [
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'access_token' => $token,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['address' => null, 'country' => null, 'error' => 'request_failed']);
        }

        if (!$response->successful()) {
            return response()->json(['address' => null, 'country' => null, 'error' => 'upstream_error'], 200);
        }

        $result = $response->json('results.0');

        if (!$result) {
            return response()->json(['address' => null, 'country' => null, 'error' => 'no_result']);
        }

        $address = $result['formattedAddress'] ?? $result['formatted_address'] ?? null;

        if (!$address) {
            $address = collect([
                $result['subLocality'] ?? null,
                $result['locality'] ?? null,
                $result['village'] ?? null,
                $result['district'] ?? null,
                $result['city'] ?? null,
                $result['state'] ?? null,
            ])->filter()->unique()->implode(', ') ?: null;
        }

        $country = $result['country'] ?? 'India';

        // Mappls' formattedAddress already ends with "(<country>)" — strip it
        // since the country is shown separately in front of the address.
        if ($address && $country) {
            $address = trim(preg_replace('/\s*\(' . preg_quote($country, '/') . '\)\s*$/i', '', $address));
        }

        return response()->json([
            'address' => $address,
            'country' => $country,
        ]);
    }
}