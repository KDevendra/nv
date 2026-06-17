<?php

namespace App\Http\Controllers\FieldOfficer;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
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

        $slots = self::PHOTO_SLOTS;
        return view('field.properties.create', compact('slots'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);

        $data = $this->validateEntry($request);

        $entry = PropertyEntry::create(array_merge($data, [
            'field_officer_id' => auth()->id(),
            'supply_head_id'   => auth()->user()->supply_head_id,
            'status'           => 'submitted',
            'submitted_at'     => now(),
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

        // Block direct URL access to verified entries older than 6 hours
        if ($property->status === 'verified' && $property->verified_at?->lt(now()->subHours(6))) {
            abort(403, 'This entry is no longer accessible.');
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

        $property->load('photos');
        $slots = self::PHOTO_SLOTS;

        return view('field.properties.edit', compact('property', 'slots'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'field_officer', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        
        // Check if the property is editable using the model's isEditable() method
        abort_if(! $property->isEditable(), 403, 'This entry cannot be edited. It may have been permanently rejected or is in a non-editable state.');

        $data = $this->validateEntry($request);
        $oldStatus = $property->status;

        $property->update(array_merge($data, [
            'status'       => 'submitted',
            'submitted_at' => now(),
            'allow_resubmit' => null, // Clear the flag on resubmission
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

    private function validateEntry(Request $request): array
    {
        $rules = [
            'facility_type'             => 'required|string',
            'nearest_city'              => 'required|string|max:255',
            // A
            'name_full_address'         => 'nullable|string',
            'village_town_district'     => 'nullable|string|max:255',
            'postal_address_pin'        => 'nullable|string|max:50',
            'nearest_highway'           => 'nullable|string|max:255',
            'nearest_railway_station'   => 'nullable|string|max:255',
            'nearest_airport'           => 'nullable|string|max:255',
            // B
            'tenure'                    => 'nullable|string|max:50',
            'approved_land_use'         => 'nullable|string|max:100',
            'fire_noc'                  => 'nullable|string|max:50',
            'clu_conversion_status'     => 'nullable|string|max:255',
            'occupancy_certificate'     => 'nullable|string|max:50',
            // C
            'plot_area'                 => 'nullable|numeric|min:0',
            'built_up_area'             => 'nullable|numeric|min:0',
            'clear_height_highest'      => 'nullable|numeric|min:0',
            'clear_height_side'         => 'nullable|numeric|min:0',
            'number_of_floors'          => 'nullable|integer|min:0',
            'fsi_far'                   => 'nullable|string|max:50',
            // D
            'dock_door_count'           => 'nullable|integer|min:0',
            'dock_type'                 => 'nullable|string|max:100',
            'dock_height'               => 'nullable|numeric|min:0',
            'truck_movement'            => 'nullable|string|max:100',
            // E
            'flooring_type'             => 'nullable|string|max:100',
            'office_cabin_area'         => 'nullable|numeric|min:0',
            'washrooms'                 => 'nullable|integer|min:0',
            'ventilation_lighting'      => 'nullable|string|max:50',
            // F
            'power_sanctioned_kva'      => 'nullable|numeric|min:0',
            'discom_name'               => 'nullable|string|max:255',
            'water_source'              => 'nullable|string|max:100',
            'fire_fighting_system'      => 'nullable|string|max:100',
            // G
            'deal_type'                 => 'nullable|string|max:50',
            'expected_rent'             => 'nullable|numeric|min:0',
            'expected_sale_price'       => 'nullable|numeric|min:0',
            'security_deposit_months'   => 'nullable|numeric|min:0',
            'lock_in_years'             => 'nullable|numeric|min:0',
            'available_from'            => 'nullable|date',
            // H
            'approach_road_width'       => 'nullable|numeric|min:0',
            'top_neighbouring_companies'=> 'nullable|string',
            'flood_risk'                => 'nullable|string|max:50',
            // I
            'nearest_hospital_km'       => 'nullable|numeric|min:0',
            'nearest_fire_station_km'   => 'nullable|numeric|min:0',
            'nearest_police_station_km' => 'nullable|numeric|min:0',
            // K
            'remarks'                   => 'nullable|string',
            'owner_contact_name'        => 'nullable|string|max:255',
            'owner_contact_phone'       => 'nullable|string|max:50',
            // Photos
            'photos'                    => 'nullable|array',
            'photos.*'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ];

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
