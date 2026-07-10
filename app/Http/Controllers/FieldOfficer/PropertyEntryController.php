<?php

namespace App\Http\Controllers\FieldOfficer;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
use App\Models\PropertyFieldConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // Verified entries verified less than 6 hours ago are still shown.
        // Verified entries verified 6+ hours ago are excluded from the table.
        $query = PropertyEntry::where('field_officer_id', $userId)
            ->where(function ($q) {
                $q->where('status', '!=', 'verified')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'verified')
                         ->where('verified_at', '>', now()->subHours(6));
                  });
            })
            ->latest();

        $entries = $query->paginate(15)->appends($request->query());

        // Counters always reflect the full dataset (no 6h filter)
        $counters = [
            'total'     => PropertyEntry::where('field_officer_id', $userId)->count(),
            'draft'     => PropertyEntry::where('field_officer_id', $userId)->where('status', 'draft')->count(),
            'submitted' => PropertyEntry::where('field_officer_id', $userId)->where('status', 'submitted')->count(),
            'verified'  => PropertyEntry::where('field_officer_id', $userId)->where('status', 'verified')->count(),
            'recheck'   => PropertyEntry::where('field_officer_id', $userId)->where('status', 'recheck')->count(),
            'rejected'  => PropertyEntry::where('field_officer_id', $userId)->where('status', 'rejected')->count(),
        ];

        return view('field.properties.index', compact('entries', 'counters'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $slots        = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = []; // No remarks on create
        return view('field.properties.create', compact('slots', 'fieldConfigs', 'fieldRemarks'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');
        
        $data = $this->validateEntry($request, $isDraft);

        if ($isDraft) {
            // Save as draft — no status change, no submitted_at, no log
            $entry = PropertyEntry::create(array_merge($data, [
                'field_officer_id' => auth()->id(),
                'supply_head_id'   => auth()->user()->supply_head_id,
                'status'           => 'draft',
                'submitted_at'     => null,
                'area_unit'        => $request->input('area_unit', 'sq_ft'),
            ]));

            $this->handlePhotos($entry, $request);

            return redirect()->route('field.properties.edit', $entry)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.');
        }

        // Default: submit
        $entry = PropertyEntry::create(array_merge($data, [
            'field_officer_id' => auth()->id(),
            'supply_head_id'   => auth()->user()->supply_head_id,
            'status'           => 'submitted',
            'submitted_at'     => now(),
            'area_unit'        => $request->input('area_unit', 'sq_ft'),
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
        $slots = self::PHOTO_SLOTS;

        return view('field.properties.show', compact('property', 'slots'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        
        // Check if the property is editable using the model's isEditable() method
        abort_if(! $property->isEditable(), 403, 'This entry cannot be edited. It may have been permanently rejected or is in a non-editable state.');

        $property->load(['photos', 'fieldReviews']);
        $slots        = self::PHOTO_SLOTS;
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
        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        
        // Check if the property is editable using the model's isEditable() method
        abort_if(! $property->isEditable(), 403, 'This entry cannot be edited. It may have been permanently rejected or is in a non-editable state.');

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');
        $oldStatus = $property->status;
        
        $data = $this->validateEntry($request, $isDraft);

        if ($isDraft) {
            // Save as draft — keep current status if already draft, otherwise set to draft
            $property->update(array_merge($data, [
                'status'         => 'draft',
                'submitted_at'   => null,
                'allow_resubmit' => null,
                'area_unit'      => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
            ]));

            $this->handlePhotos($property, $request);

            return redirect()->route('field.properties.edit', $property)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.');
        }

        // Default: submit
        $property->update(array_merge($data, [
            'status'         => 'submitted',
            'submitted_at'   => now(),
            'allow_resubmit' => null,
            'area_unit'      => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
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

    // ── Validation ────────────────────────────────────────────────────────────

    private function validateEntry(Request $request, bool $isDraft = false): array
    {
        $configs = PropertyFieldConfig::allKeyed();

        // Base type constraints per field — independent of required/nullable
        $typeRules = [
            // A
            'facility_type'                => 'string',
            'name_full_address'            => 'string',
            'village'                      => 'string|max:255',
            'tehsil'                       => 'string|max:255',
            'district'                     => 'string|max:255',
            'state'                        => 'string|max:255',
            'country'                      => 'string|max:255',
            'postal_address_pin'           => 'string|max:50',
            'nearest_highway'              => 'string|max:255',
            'nearest_city'                 => 'string|max:255',
            'nearest_railway_station'      => 'string|max:255',
            'nearest_airport'              => 'string|max:255',
            'owner_contact_name'           => 'string|max:255',
            'owner_contact_phone'          => 'string|max:50',
            'owner_email'                  => 'email|max:255',
            // B
            'tenure'                       => 'string|max:50',
            'approved_land_use'            => 'string|max:100',
            'fire_noc'                     => 'string|max:50',
            'clu_conversion_status'        => 'string|max:255',
            'occupancy_certificate'        => 'string|max:50',
            'pollution_noc'                => 'string|max:50',
            'pollution_category'           => 'string|max:100',
            // C — dimensions
            'area_unit'                    => 'string|in:sq_ft,sq_mt,sq_yd',
            'plot_area'                    => 'numeric|min:0',
            'built_up_area'                => 'numeric|min:0',
            'carpet_area'                  => 'numeric|min:0',
            'available_area'               => 'numeric|min:0',
            'clear_height_highest'         => 'numeric|min:0',
            'clear_height_side'            => 'numeric|min:0',
            'shed_width'                   => 'numeric|min:0',
            'shed_length'                  => 'numeric|min:0',
            'number_of_floors'             => 'integer|min:0',
            'fsi_far'                      => 'string|max:50',
            // C — docks/sides
            'dock_door_count'              => 'integer|min:0',
            'dock_front'                   => 'integer|min:0',
            'dock_left'                    => 'integer|min:0',
            'dock_right'                   => 'integer|min:0',
            'dock_back'                    => 'integer|min:0',
            'dock_leveller_front'          => 'integer|min:0',
            'dock_leveller_left'           => 'integer|min:0',
            'dock_leveller_right'          => 'integer|min:0',
            'dock_leveller_back'           => 'integer|min:0',
            'fire_exit_front'              => 'integer|min:0',
            'fire_exit_left'               => 'integer|min:0',
            'fire_exit_right'              => 'integer|min:0',
            'fire_exit_back'               => 'integer|min:0',
            'canopy_width_front'           => 'numeric|min:0',
            'canopy_length_front'          => 'numeric|min:0',
            'canopy_width_left'            => 'numeric|min:0',
            'canopy_length_left'           => 'numeric|min:0',
            'canopy_width_right'           => 'numeric|min:0',
            'canopy_length_right'          => 'numeric|min:0',
            'canopy_width_back'            => 'numeric|min:0',
            'canopy_length_back'           => 'numeric|min:0',
            'has_dock_leveller'            => 'boolean',
            'road_width_front'             => 'numeric|min:0',
            'road_width_left'              => 'numeric|min:0',
            'road_width_right'             => 'numeric|min:0',
            'road_width_back'              => 'numeric|min:0',
            'no_of_offices'                => 'integer|min:0',
            'has_offices'                  => 'boolean',
            'office_sizes'                 => 'nullable|string',
            'canteen'                      => 'boolean',
            'canteen_size'                 => 'string|max:255',
            'stp_plant'                    => 'boolean',
            'stp_capacity'                 => 'string|max:255',
            'no_of_urinals'                => 'integer|min:0',
            'no_of_closets'                => 'integer|min:0',
            'female_washroom'              => 'boolean',
            'driver_rest_room'             => 'boolean',
            'mezzanine'                    => 'boolean',
            'mezzanine_size'               => 'string|max:255',
            'structure_type'               => 'string|max:100',
            'insulation_roof'              => 'string|max:100',
            'insulation_side'              => 'string|max:100',
            'fire_sprinkler'               => 'string|max:50',
            'scrap_yard'                   => 'boolean',
            'no_of_companies_same_premise' => 'integer|min:0',
            'extension_possible'           => 'boolean',
            // D
            'dock_type'                    => 'string|max:100',
            'dock_height'                  => 'numeric|min:0',
            'truck_movement'               => 'string|max:100',
            // E
            'flooring_type'                => 'string|max:100',
            'office_cabin_area'            => 'numeric|min:0',
            'washrooms'                    => 'integer|min:0',
            'ventilation_lighting'         => 'string|max:50',
            // F
            'power_sanctioned_kva'         => 'numeric|min:0',
            'discom_name'                  => 'string|max:255',
            'water_source'                 => 'string|max:100',
            'water_tank_capacity'          => 'string|max:100',
            'fire_fighting_system'         => 'string|max:100',
            'solar'                        => 'boolean',
            // G
            'deal_type'                    => 'string|max:50',
            'expected_rent'                => 'numeric|min:0',
            'expected_sale_price'          => 'numeric|min:0',
            'security_deposit_months'      => 'numeric|min:0|max:60',
            'lock_in_years'                => 'numeric|min:0|max:99',
            'available_from'               => 'date',
            // H
            'approach_road_width'          => 'numeric|min:0',
            'top_neighbouring_companies'   => 'string',
            'flood_risk'                   => 'string|max:50',
            // I
            'nearest_hospital_km'          => 'numeric|min:0',
            'nearest_fire_station_km'      => 'numeric|min:0',
            'nearest_police_station_km'    => 'numeric|min:0',
            // K
            'remarks'                      => 'string',
        ];

        $rules = [];

        foreach ($typeRules as $field => $typeConstraint) {
            $cfg = $configs->get($field);

            // If config says keep_field = false, skip validation entirely
            if ($cfg && $cfg->keep_field === false) {
                continue;
            }

            // For drafts, all fields are nullable regardless of config
            // For submissions, use config to determine required vs nullable
            if ($isDraft) {
                $presence = 'nullable';
            } else {
                $presence = ($cfg && $cfg->mandatory_field) ? 'required' : 'nullable';
            }

            $rules[$field] = $presence . '|' . $typeConstraint;
        }

        // Photos are always optional — not driven by field config
        $rules['photos']   = 'nullable|array';
        $rules['photos.*'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';

        return $request->validate($rules, [
            'photos.*.image' => 'Only camera photos are allowed.',
        ]);
    }

    // ── Photo Handler ─────────────────────────────────────────────────────────

    private function handlePhotos(PropertyEntry $entry, Request $request): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $manager = new ImageManager(new Driver());

        foreach (self::PHOTO_SLOTS as $index => $slotLabel) {
            $inputKey = 'photos.' . $index;

            if (! $request->hasFile($inputKey)) {
                continue;
            }

            $file = $request->file($inputKey);

            $image   = $manager->read($file->getRealPath());
            $webpData = $image->toWebp(75)->toString();

            $publicPath = public_path('images/property_photos');
            if (! file_exists($publicPath)) {
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
                'file_path'  => 'images/property_photos/' . $filename,
            ]);
        }
    }
}
