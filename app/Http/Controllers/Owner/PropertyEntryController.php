<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
use App\Models\PropertyFieldConfig;
use App\Models\User;
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
        abort_if(auth()->user()->role !== 'owner', 403);

        $userId = auth()->id();

        $query = PropertyEntry::where('field_officer_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('type')) {
            $type = $request->string('type');
            if ($type === 'warehouse') {
                $query->where(function ($q) {
                    $q->where('property_type', 'warehouse')->orWhereNull('property_type');
                });
            } else {
                $query->where('property_type', $type);
            }
        } elseif ($request->filled('group')) {
            $group = $request->string('group');
            $propertyTypesConfig = config('property_types.types', []);
            $typesInGroup = collect($propertyTypesConfig)->where('group', $group)->pluck('property_type')->all();
            if ($group === 'warehousing') {
                $typesInGroup[] = 'warehouse';
                $query->where(function ($q) use ($typesInGroup) {
                    $q->whereIn('property_type', $typesInGroup)->orWhereNull('property_type');
                });
            } else {
                $query->whereIn('property_type', $typesInGroup);
            }
        }

        $query->latest();

        $entries = $query->paginate(15)->appends($request->query());

        // Base query for type counts
        $baseCountQuery = PropertyEntry::where('field_officer_id', $userId);
        if ($request->filled('status')) {
            $baseCountQuery->where('status', $request->string('status'));
        }

        $rawCounts = (clone $baseCountQuery)
            ->select('property_type', \DB::raw('count(*) as count'))
            ->groupBy('property_type')
            ->pluck('count', 'property_type')
            ->toArray();

        $typeCounts = [];
        $warehouseCount = 0;
        foreach ($rawCounts as $typeKey => $cnt) {
            if (empty($typeKey) || $typeKey === 'warehouse') {
                $warehouseCount += $cnt;
            } else {
                $typeCounts[$typeKey] = $cnt;
            }
        }
        $typeCounts['warehouse'] = $warehouseCount;

        $propertyTypesConfig = config('property_types.types', []);

        $groupCounts = [
            'all'         => array_sum($rawCounts),
            'warehousing' => $warehouseCount,
            'residential' => 0,
            'commercial'  => 0,
        ];

        foreach ($propertyTypesConfig as $key => $cfg) {
            $grp = $cfg['group'] ?? 'commercial';
            $pType = $cfg['property_type'] ?? $key;
            if (isset($typeCounts[$pType])) {
                $groupCounts[$grp] = ($groupCounts[$grp] ?? 0) + $typeCounts[$pType];
            }
        }

        // Base query for status cards (filtered by active type or group)
        $counterQuery = PropertyEntry::where('field_officer_id', $userId);
        if ($request->filled('type')) {
            $type = $request->string('type');
            if ($type === 'warehouse') {
                $counterQuery->where(function ($q) {
                    $q->where('property_type', 'warehouse')->orWhereNull('property_type');
                });
            } else {
                $counterQuery->where('property_type', $type);
            }
        } elseif ($request->filled('group')) {
            $group = $request->string('group');
            $typesInGroup = collect($propertyTypesConfig)->where('group', $group)->pluck('property_type')->all();
            if ($group === 'warehousing') {
                $typesInGroup[] = 'warehouse';
                $counterQuery->where(function ($q) use ($typesInGroup) {
                    $q->whereIn('property_type', $typesInGroup)->orWhereNull('property_type');
                });
            } else {
                $counterQuery->whereIn('property_type', $typesInGroup);
            }
        }

        $counters = [
            'total'     => (clone $counterQuery)->count(),
            'draft'     => (clone $counterQuery)->where('status', 'draft')->count(),
            'submitted' => (clone $counterQuery)->where('status', 'submitted')->count(),
            'verified'  => (clone $counterQuery)->where('status', 'verified')->count(),
            'recheck'   => (clone $counterQuery)->where('status', 'recheck')->count(),
            'rejected'  => (clone $counterQuery)->where('status', 'rejected')->count(),
        ];

        return view('owner.properties.index', compact('entries', 'counters', 'typeCounts', 'groupCounts', 'propertyTypesConfig'));
    }

    // ── Select Property Type ──────────────────────────────────────────────────

    public function selectType(): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $propertyTypes = config('property_types.types', []);

        $residentialTypes = collect($propertyTypes)->where('group', 'residential')->all();
        $commercialTypes  = collect($propertyTypes)->where('group', 'commercial')->all();
        $warehouseType    = $propertyTypes['warehouse'] ?? null;

        return view('owner.properties.select_type', compact('residentialTypes', 'commercialTypes', 'warehouseType'));
    }

    public function createType(string $type): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $types = config('property_types.types', []);
        $matched = collect($types)->first(fn($t) => ($t['slug'] ?? '') === $type || ($t['property_type'] ?? '') === $type);

        if (!$matched) {
            abort(404, 'Property type not found.');
        }

        if ($matched['property_type'] === 'warehouse') {
            return $this->create();
        }

        $property = null;
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];
        $propertyType = $matched['property_type'];
        $typeMeta = $matched;

        return view('owner.properties.create', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks', 'propertyType', 'typeMeta'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $property = null;
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];
        return view('owner.properties.create', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        abort_if(auth()->user()->role !== 'owner', 403);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');

        $data = $this->validateEntry($request, $isDraft);

        \Illuminate\Support\Facades\Log::info('Location data stored for property entry', [
            'location' => $data['form_submited_location'] ?? null,
            'address' => $data['form_submited_address'] ?? null,
            'maps_url' => $data['form_submited_maps_url'] ?? null,
            'user_id' => auth()->id(),
        ]);

        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        $supplyHeadId = auth()->user()->supply_head_id ?? User::where('role', 'supply_head')->first()?->id;

        if ($isDraft) {
            $entry = PropertyEntry::create(array_merge($data, [
                'field_officer_id' => auth()->id(),
                'status'           => 'draft',
                'submitted_at'     => null,
                'area_unit'        => $request->input('area_unit', 'sq_ft'),
            ]));

            $this->handlePhotos($entry, $request);

            return redirect()->route('owner.properties.edit', $entry)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        $entry = PropertyEntry::create(array_merge($data, [
            'field_officer_id' => auth()->id(),
            'status'           => 'submitted',
            'submitted_at'     => now(),
            'area_unit'        => $request->input('area_unit', 'sq_ft'),
        ]));

        $this->handlePhotos($entry, $request);

        PropertyEntryLog::logAction($entry, 'submitted', null, 'submitted');

        return redirect()->route('owner.dashboard')
            ->with('success', 'Property listing submitted successfully. Code: ' . $entry->code);
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        $property->load('photos');
        $slots = self::PHOTO_SLOTS;

        return view('owner.properties.show', compact('property', 'slots'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(PropertyEntry $property): View|RedirectResponse
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        if (!empty($property->property_type) && $property->property_type !== 'warehouse') {
            $slug = str_replace('_', '-', $property->property_type);
            $routeName = "owner.properties.{$slug}.edit";
            if (\Illuminate\Support\Facades\Route::has($routeName)) {
                return redirect()->route($routeName, $property);
            }
        }

        if (!$property->isEditable()) {
            return redirect()->route('owner.properties.show', $property)
                ->with('error', 'This property entry cannot be edited as it is currently under review or verified.');
        }

        $property->load(['photos', 'fieldReviews']);
        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();

        $correctFields = $property->fieldReviews->where('is_correct', true)->pluck('field_name')->toArray();
        $incorrectFields = $property->fieldReviews->where('is_correct', false)->pluck('field_name')->toArray();
        $fieldRemarks = $property->fieldReviews->whereNotNull('remark')->pluck('remark', 'field_name')->toArray();

        return view('owner.properties.edit', compact('property', 'slots', 'fieldConfigs', 'correctFields', 'incorrectFields', 'fieldRemarks'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

        if (!$property->isEditable()) {
            return redirect()->route('owner.properties.show', $property)
                ->with('error', 'This property entry cannot be edited.');
        }

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');

        $data = $this->validateEntry($request, $isDraft);

        \Illuminate\Support\Facades\Log::info('Location data updated for property entry', [
            'property_id' => $property->id,
            'location' => $data['form_submited_location'] ?? null,
            'address' => $data['form_submited_address'] ?? null,
            'maps_url' => $data['form_submited_maps_url'] ?? null,
            'user_id' => auth()->id(),
        ]);

        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        if ($isDraft) {
            $property->update(array_merge($data, [
                'area_unit' => $request->input('area_unit', 'sq_ft'),
            ]));

            $this->handlePhotos($property, $request);

            return redirect()->route('owner.properties.edit', $property)
                ->with('success', 'Draft saved.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        $newStatus = 'submitted';
        $property->update(array_merge($data, [
            'status'                  => $newStatus,
            'submitted_at'            => now(),
            'area_unit'               => $request->input('area_unit', 'sq_ft'),
            'supply_head_viewed_at'   => null,
        ]));

        $this->handlePhotos($property, $request);

        PropertyEntryLog::logAction($property, 'resubmitted', null, 'submitted');

        return redirect()->route('owner.dashboard')
            ->with('success', 'Property listing updated and submitted successfully. Code: ' . $property->code);
    }

    // ── Reverse Geocode Helper ────────────────────────────────────────────────

    public function reverseGeocode(Request $request): JsonResponse
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        try {
            $res = Http::withHeaders(['User-Agent' => 'ZendoIndia/1.0'])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'json',
                    'lat'    => $lat,
                    'lon'    => $lng,
                    'zoom'   => 18,
                    'addressdetails' => 1,
                ]);

            if ($res->successful()) {
                $address = $res->json('address') ?? [];
                return response()->json([
                    'address'      => $res->json('display_name'),
                    'display_name' => $res->json('display_name'),
                    'village'      => $address['village'] ?? $address['suburb'] ?? $address['neighbourhood'] ?? null,
                    'tehsil'       => $address['county'] ?? $address['subdistrict'] ?? null,
                    'district'     => $address['state_district'] ?? $address['county'] ?? null,
                    'state'        => $address['state'] ?? null,
                    'country'      => $address['country'] ?? null,
                    'postcode'     => $address['postcode'] ?? null,
                    'city'         => $address['city'] ?? $address['town'] ?? $address['municipality'] ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return response()->json(['error' => 'Geocoding failed'], 500);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Human-readable labels used to build friendly validation messages
     * (e.g. "The Owner E-mail field is required." instead of the raw
     * snake_case attribute name "owner_email"). Mirrors
     * FieldOfficer\PropertyEntryController::FIELD_LABELS.
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
        'canopy_width_left' => 'Canopy Width — Left',
        'canopy_width_right' => 'Canopy Width — Right',
        'canopy_width_back' => 'Canopy Width — Back',
        'road_width_front' => 'Road Width — Front',
        'road_width_left' => 'Road Width — Left',
        'road_width_right' => 'Road Width — Right',
        'road_width_back' => 'Road Width — Back',
        'no_of_offices' => 'No. of Offices',
        'canteen' => 'Canteen',
        'canteen_size' => 'Canteen Size',
        'stp_plant' => 'STP Plant',
        'stp_capacity' => 'STP Capacity',
        'washrooms' => 'No. of Washrooms',
        'no_of_urinals' => 'No. of Urinals',
        'no_of_closets' => 'No. of Closets',
        'female_washroom' => 'Female Washroom',
        'driver_rest_room' => 'Driver Rest Room',
        'mezzanine' => 'Mezzanine',
        'mezzanine_size' => 'Mezzanine Size',
        'structure_type' => 'Structure Type',
        'flooring_type' => 'Flooring Type',
        'ventilation_lighting' => 'Ventilation & Lighting',
        'insulation_roof' => 'Insulation — Roof',
        'insulation_side' => 'Insulation — Side',
        'fire_sprinkler' => 'Fire Sprinkler',
        'scrap_yard' => 'Scrap Yard',
        'no_of_companies_same_premise' => 'No. of Companies on Same Premise',
        'extension_possible' => 'Extension Possible',
        'dock_type' => 'Dock Type',
        'dock_height' => 'Dock Height',
        'truck_movement' => 'Truck Movement',
        'office_cabin_area' => 'Office / Cabin Area',
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
        'form_submited_location' => 'Submitted Location',
    ];

    /**
     * Mirrors FieldOfficer\PropertyEntryController::validateEntry() —
     * required/nullable is driven by PropertyFieldConfig::mandatory_field
     * (or the field's own required_if trigger) rather than hardcoded,
     * so an owner's full submission is held to the same "every mandatory
     * field must actually be present" rule the field-officer wizard
     * already enforces. Drafts stay fully optional regardless of config.
     */
    private function validateEntry(Request $request, bool $isDraft): array
    {
        $configs = PropertyFieldConfig::allKeyed();

        // Base type constraints per field — independent of required/nullable
        $typeRules = [
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
            'tenure' => 'string|max:50',
            'approved_land_use' => 'string|max:100',
            'fire_noc' => 'string|max:50',
            'clu_conversion_status' => 'string|max:255',
            'occupancy_certificate' => 'string|max:50',
            'pollution_noc' => 'string|max:50',
            'pollution_category' => 'string|max:100',
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
            'canopy_width_left' => 'numeric|min:0',
            'canopy_width_right' => 'numeric|min:0',
            'canopy_width_back' => 'numeric|min:0',
            'has_dock_leveller' => 'boolean',
            'road_width_front' => 'numeric|min:0',
            'road_width_left' => 'numeric|min:0',
            'road_width_right' => 'numeric|min:0',
            'road_width_back' => 'numeric|min:0',
            'no_of_offices' => 'integer|min:0',
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
            'dock_type' => 'string|max:100',
            'dock_height' => 'numeric|min:0',
            'truck_movement' => 'string|max:100',
            'flooring_type' => 'string|max:100',
            'office_cabin_area' => 'numeric|min:0',
            'washrooms' => 'integer|min:0',
            'ventilation_lighting' => 'string|max:50',
            'power_sanctioned_kva' => 'numeric|min:0',
            'discom_name' => 'string|max:255',
            'water_source' => 'string|max:100',
            'water_tank_capacity' => 'string|max:100',
            'fire_fighting_system' => 'string|max:100',
            'solar' => 'boolean',
            'deal_type' => 'string|max:50',
            'expected_rent' => 'numeric|min:0',
            'expected_sale_price' => 'numeric|min:0',
            'security_deposit_months' => 'nullable|string|max:255',
            'lock_in_years' => 'numeric|min:0|max:99',
            'available_from' => 'date',
            'approach_road_width' => 'numeric|min:0',
            'top_neighbouring_companies' => 'string',
            'flood_risk' => 'string|max:50',
            'nearest_hospital_km' => 'numeric|min:0',
            'nearest_fire_station_km' => 'numeric|min:0',
            'nearest_police_station_km' => 'numeric|min:0',
            'remarks' => 'string',
            // Metadata — captured client-side via the browser's Geolocation API, not driven by PropertyFieldConfig
            'form_submited_location' => 'nullable|string|max:1000',
            'form_submited_address'  => 'nullable|string',
            'form_submited_maps_url' => 'nullable|string|max:500',
        ];

        $rules = [];

        foreach ($typeRules as $field => $typeConstraint) {
            $cfg = $configs->get($field);

            // If config says keep_field = false, skip validation entirely
            if ($cfg && $cfg->keep_field === false) {
                continue;
            }

            if ($isDraft) {
                $presence = 'nullable';
            } else {
                $isMandatory = $cfg && $cfg->mandatory_field;
                if (!$isMandatory) {
                    $presence = 'nullable';
                } else {
                    if ($field === 'canteen_size') {
                        $presence = ((string) $request->input('canteen') === '1') ? 'required' : 'nullable';
                    } elseif ($field === 'stp_capacity') {
                        $presence = ((string) $request->input('stp_plant') === '1') ? 'required' : 'nullable';
                    } elseif ($field === 'mezzanine_size') {
                        $presence = ((string) $request->input('mezzanine') === '1') ? 'required' : 'nullable';
                    } elseif (in_array($field, ['expected_rent', 'security_deposit_months', 'lock_in_years'])) {
                        $presence = in_array($request->input('deal_type'), ['Lease', 'Both']) ? 'required' : 'nullable';
                    } elseif ($field === 'expected_sale_price') {
                        $presence = in_array($request->input('deal_type'), ['Sale', 'Both']) ? 'required' : 'nullable';
                    } elseif (in_array($field, ['dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back'])) {
                        $presence = 'nullable';
                    } else {
                        $presence = 'required';
                    }
                }
            }

            if (is_array($typeConstraint)) {
                $rules[$field] = array_merge([$presence], $typeConstraint);
            } else {
                $rules[$field] = $presence . '|' . $typeConstraint;
            }
        }

        // Photos are always optional — not driven by field config
        for ($i = 0; $i < 8; $i++) {
            $rules["photo_{$i}"] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';
        }

        $data = $request->validate($rules, [
            'postal_address_pin.regex' => 'PIN code must be exactly 6 digits.',
            'owner_contact_phone.regex' => 'Contact number must be a valid 10-digit Indian mobile number.',
        ], self::FIELD_LABELS);

        // Auto-clear conditional fields if parent answer is No / deal_type doesn't match
        if (isset($data['canteen']) && (string) $data['canteen'] === '0') {
            $data['canteen_size'] = null;
        }
        if (isset($data['stp_plant']) && (string) $data['stp_plant'] === '0') {
            $data['stp_capacity'] = null;
        }
        if (isset($data['mezzanine']) && (string) $data['mezzanine'] === '0') {
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
        // count somewhere — mirrors FieldOfficer's group check.
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

    private function handlePhotos(PropertyEntry $entry, Request $request): void
    {
        $manager = new ImageManager(new Driver());

        for ($i = 0; $i < 8; $i++) {
            $inputName = "photo_{$i}";
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                $slotLabel = self::PHOTO_SLOTS[$i] ?? "Photo {$i}";

                $filename = 'prop_' . $entry->id . '_slot' . $i . '_' . time() . '.jpg';
                $destinationPath = public_path('images/property_photos');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $img = $manager->read($file->getRealPath());

                $watermarkText = 'ZendoIndia | ' . ($entry->code ?? 'NV');
                $img->scale(width: 1200);

                $img->save($destinationPath . '/' . $filename, quality: 80);

                $existingPhoto = PropertyEntryPhoto::where('property_entry_id', $entry->id)
                    ->where('slot_label', $slotLabel)
                    ->first();

                if ($existingPhoto) {
                    if ($existingPhoto->file_path && file_exists(public_path($existingPhoto->file_path))) {
                        @unlink(public_path($existingPhoto->file_path));
                    }
                    $existingPhoto->update([
                        'file_path' => 'images/property_photos/' . $filename,
                    ]);
                } else {
                    PropertyEntryPhoto::create([
                        'property_entry_id' => $entry->id,
                        'slot_label'        => $slotLabel,
                        'file_path'         => 'images/property_photos/' . $filename,
                    ]);
                }
            }
        }
    }
}
