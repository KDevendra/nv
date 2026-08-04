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

        $query->latest();

        $entries = $query->paginate(15)->appends($request->query());

        $counters = [
            'total'     => PropertyEntry::where('field_officer_id', $userId)->count(),
            'draft'     => PropertyEntry::where('field_officer_id', $userId)->where('status', 'draft')->count(),
            'submitted' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'submitted')->count(),
            'verified'  => PropertyEntry::where('field_officer_id', $userId)->where('status', 'verified')->count(),
            'recheck'   => PropertyEntry::where('field_officer_id', $userId)->where('status', 'recheck')->count(),
            'rejected'  => PropertyEntry::where('field_officer_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('owner.properties.index', compact('entries', 'counters'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];
        return view('owner.properties.create', compact('slots', 'fieldConfigs', 'fieldRemarks'));
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

        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        $supplyHeadId = auth()->user()->supply_head_id ?? User::where('role', 'supply_head')->first()?->id;

        if ($isDraft) {
            $entry = PropertyEntry::create(array_merge($data, [
                'field_officer_id' => auth()->id(),
                'supply_head_id'   => $supplyHeadId,
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
            'supply_head_id'   => $supplyHeadId,
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

    public function edit(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);

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

    private function validateEntry(Request $request, bool $isDraft): array
    {
        $rules = [
            'facility_type' => 'nullable|string|max:255',
            'property_name' => 'nullable|string|max:255',
            'name_full_address' => 'nullable|string',
            'village' => 'nullable|string|max:255',
            'tehsil' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_address_pin' => 'nullable|string|max:20',
            'nearest_city' => 'nullable|string|max:255',
            'nearest_highway' => 'nullable|string|max:255',
            'nearest_railway_station' => 'nullable|string|max:255',
            'nearest_airport' => 'nullable|string|max:255',
            'owner_contact_name' => 'nullable|string|max:255',
            'owner_contact_phone' => 'nullable|string|max:50',
            'owner_email' => 'nullable|email|max:255',
            'tenure' => 'nullable|string|max:255',
            'approved_land_use' => 'nullable|string|max:255',
            'fire_noc' => 'nullable|string|max:255',
            'clu_conversion_status' => 'nullable|string|max:255',
            'pollution_noc' => 'nullable|string|max:255',
            'pollution_category' => 'nullable|string|max:255',
            'occupancy_certificate' => 'nullable|string|max:255',
            'plot_area' => 'nullable|numeric|min:0',
            'built_up_area' => 'nullable|numeric|min:0',
            'carpet_area' => 'nullable|numeric|min:0',
            'available_area' => 'nullable|numeric|min:0',
            'clear_height_highest' => 'nullable|numeric|min:0',
            'clear_height_side' => 'nullable|numeric|min:0',
            'shed_width' => 'nullable|numeric|min:0',
            'shed_length' => 'nullable|numeric|min:0',
            'number_of_floors' => 'nullable|integer|min:0',
            'fsi_far' => 'nullable|string|max:255',
            'dock_door_count' => 'nullable|integer|min:0',
            'dock_front' => 'nullable|integer|min:0',
            'dock_left' => 'nullable|integer|min:0',
            'dock_right' => 'nullable|integer|min:0',
            'dock_back' => 'nullable|integer|min:0',
            'has_dock_leveller' => 'nullable|boolean',
            'dock_leveller_front' => 'nullable|integer|min:0',
            'dock_leveller_left' => 'nullable|integer|min:0',
            'dock_leveller_right' => 'nullable|integer|min:0',
            'dock_leveller_back' => 'nullable|integer|min:0',
            'fire_exit_front' => 'nullable|integer|min:0',
            'fire_exit_left' => 'nullable|integer|min:0',
            'fire_exit_right' => 'nullable|integer|min:0',
            'fire_exit_back' => 'nullable|integer|min:0',
            'canopy_width_front' => 'nullable|numeric|min:0',
            'canopy_width_left' => 'nullable|numeric|min:0',
            'canopy_width_right' => 'nullable|numeric|min:0',
            'canopy_width_back' => 'nullable|numeric|min:0',
            'road_width_front' => 'nullable|numeric|min:0',
            'road_width_left' => 'nullable|numeric|min:0',
            'road_width_right' => 'nullable|numeric|min:0',
            'road_width_back' => 'nullable|numeric|min:0',
            'no_of_offices' => 'nullable|integer|min:0',
            'office_sizes' => 'nullable',
            'canteen' => 'nullable|string|max:255',
            'canteen_size' => 'nullable|string|max:255',
            'stp_plant' => 'nullable|string|max:255',
            'stp_capacity' => 'nullable|string|max:255',
            'washrooms' => 'nullable|integer|min:0',
            'no_of_urinals' => 'nullable|integer|min:0',
            'no_of_closets' => 'nullable|integer|min:0',
            'female_washroom' => 'nullable|string|max:255',
            'driver_rest_room' => 'nullable|string|max:255',
            'mezzanine' => 'nullable|string|max:255',
            'mezzanine_size' => 'nullable|string|max:255',
            'structure_type' => 'nullable|string|max:255',
            'flooring_type' => 'nullable|string|max:255',
            'ventilation_lighting' => 'nullable|string|max:255',
            'insulation_roof' => 'nullable|string|max:255',
            'insulation_side' => 'nullable|string|max:255',
            'fire_sprinkler' => 'nullable|string|max:255',
            'scrap_yard' => 'nullable|string|max:255',
            'no_of_companies_same_premise' => 'nullable|integer|min:0',
            'extension_possible' => 'nullable|string|max:255',
            'dock_type' => 'nullable|string|max:255',
            'dock_height' => 'nullable|numeric|min:0',
            'truck_movement' => 'nullable|string|max:255',
            'office_cabin_area' => 'nullable|numeric|min:0',
            'power_sanctioned_kva' => 'nullable|numeric|min:0',
            'discom_name' => 'nullable|string|max:255',
            'water_source' => 'nullable|string|max:255',
            'water_tank_capacity' => 'nullable|string|max:255',
            'fire_fighting_system' => 'nullable|string|max:255',
            'solar' => 'nullable|string|max:255',
            'deal_type' => 'nullable|string|max:255',
            'expected_rent' => 'nullable|numeric|min:0',
            'expected_sale_price' => 'nullable|numeric|min:0',
            'security_deposit_months' => 'nullable|integer|min:0',
            'lock_in_years' => 'nullable|integer|min:0',
            'available_from' => 'nullable|date',
            'approach_road_width' => 'nullable|numeric|min:0',
            'top_neighbouring_companies' => 'nullable|string',
            'flood_risk' => 'nullable|string|max:255',
            'nearest_hospital_km' => 'nullable|numeric|min:0',
            'nearest_fire_station_km' => 'nullable|numeric|min:0',
            'nearest_police_station_km' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'form_submited_location' => 'nullable|string|max:1000',
            'form_submited_address'  => 'nullable|string',
            'form_submited_maps_url' => 'nullable|string|max:500',
        ];

        return $request->validate($rules);
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
