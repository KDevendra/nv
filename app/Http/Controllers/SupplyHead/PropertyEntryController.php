<?php

namespace App\Http\Controllers\SupplyHead;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $user = auth()->user();
        abort_if($user->role !== 'supply_head', 403);

        // Get field officers under this supply head, and owners if can_approve_owner_listings is enabled
        $fieldOfficerIds = User::where('supply_head_id', $user->id)->pluck('id');
        $ownerIds = $user->can_approve_owner_listings
            ? User::where('role', 'owner')->pluck('id')
            : collect();
        $assigneeIds = $fieldOfficerIds->concat($ownerIds);

        $fieldOfficers = User::where(function($q) use ($user) {
            $q->where('supply_head_id', $user->id);
            if ($user->can_approve_owner_listings) {
                $q->orWhere('role', 'owner');
            }
        })->get();

        // ── Not-opened entries (always shown at top, separate table) ──────────
        $notOpenedQuery = PropertyEntry::with(['fieldOfficer'])
            ->where(function ($q) use ($assigneeIds, $user) {
                $q->whereIn('field_officer_id', $assigneeIds)
                  ->orWhere('supply_head_id', $user->id);
            })
            ->where('status', '!=', 'draft')
            ->whereNull('supply_head_viewed_at')
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC');

        // Apply same field_officer / search filters to not-opened too
        if ($request->filled('field_officer')) {
            $notOpenedQuery->where('field_officer_id', $request->field_officer);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $notOpenedQuery->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('nearest_city', 'like', "%{$search}%")
                    ->orWhere('facility_type', 'like', "%{$search}%")
                    ->orWhere('property_name', 'like', "%{$search}%")
                    ->orWhereHas('fieldOfficer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }
        $notOpenedEntries = $notOpenedQuery->get();

        // ── All entries (paginated, with filters) - EXCLUDE not-opened entries ──
        $query = PropertyEntry::with(['fieldOfficer'])
            ->where(function ($q) use ($assigneeIds, $user) {
                $q->whereIn('field_officer_id', $assigneeIds)
                  ->orWhere('supply_head_id', $user->id);
            })
            ->where('status', '!=', 'draft')
            ->whereNotNull('supply_head_viewed_at')
            ->orderByRaw('COALESCE(submitted_at, created_at) DESC');

        if ($request->filled('field_officer')) {
            $query->where('field_officer_id', $request->field_officer);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('nearest_city', 'like', "%{$search}%")
                    ->orWhere('facility_type', 'like', "%{$search}%")
                    ->orWhere('property_name', 'like', "%{$search}%")
                    ->orWhereHas('fieldOfficer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $entries = $query->paginate(15)->appends($request->query());

        $counters = [
            'total' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', '!=', 'draft')->count(),
            'pending' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'submitted')->count(),
            'verified' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'verified')->count(),
            'rejected' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'rejected')->count(),
            'recheck' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', 'recheck')->count(),
            'not_opened' => PropertyEntry::whereIn('field_officer_id', $assigneeIds)->where('status', '!=', 'draft')->whereNull('supply_head_viewed_at')->count(),
        ];

        return view('supplyhead.properties.index', compact('entries', 'notOpenedEntries', 'counters', 'fieldOfficers'));
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // Mark as viewed by supply head if not already viewed
        if (!$property->isViewedBySupplyHead()) {
            $property->update(['supply_head_viewed_at' => now()]);
        }

        $property->load(['photos', 'fieldOfficer', 'reviewer', 'logs.user', 'fieldReviews']);
        $slots = self::PHOTO_SLOTS;

        // Get all reviewable fields (text) with their review status
        $fields = collect($this->getReviewableFields($property))->map(function ($field) use ($property) {
            $review = $property->fieldReviews->firstWhere('field_name', $field['name']);
            return array_merge($field, [
                'is_correct' => $review?->is_correct,
                'remark' => $review?->remark,
            ]);
        });

        // Build photo slot reviews — one entry per slot regardless of upload status
        $photosByLabel = $property->photos->keyBy('slot_label');
        $photoReviews = collect(self::PHOTO_SLOTS)->map(function ($slotLabel, $slotIndex) use ($property, $photosByLabel) {
            $photo = $photosByLabel->get($slotLabel);
            $review = $property->fieldReviews->firstWhere('field_name', 'photo_' . $slotIndex);
            return [
                'name' => 'photo_' . $slotIndex,
                'label' => $slotLabel,
                'url' => $photo?->url,
                'uploaded' => (bool) $photo,
                'is_correct' => $review?->is_correct,
                'remark' => $review?->remark,
            ];
        })->values();

        return view('supplyhead.properties.show', compact('property', 'slots', 'fields', 'photoReviews'));
    }

    // ── Action ────────────────────────────────────────────────────────────────

    public function action(Request $request, PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        $request->validate([
            'action' => 'required|in:verified,rejected,recheck',
            'note' => 'required_if:action,rejected,recheck|nullable|string|max:1000',
            'allow_resubmit' => 'nullable|boolean',
        ]);

        $oldStatus = $property->status;
        $newStatus = $request->action;

        // If verifying, check that all fields have been reviewed and are correct
        if ($newStatus === 'verified') {
            $allFieldsCorrect = $this->allFieldsReviewedAndCorrect($property);

            if (!$allFieldsCorrect) {
                return redirect()->back()->withErrors([
                    'action' => 'Cannot verify: All fields must be reviewed and marked as correct before verification.'
                ])->withInput();
            }
        }

        $property->status = $newStatus;
        $property->supply_head_note = $request->note;
        $property->reviewed_at = now();
        $property->reviewed_by = auth()->id();

        if ($newStatus === 'verified') {
            $property->verified_at = now();
            $property->allow_resubmit = null;   // clear flag on verify
        } elseif ($newStatus === 'rejected') {
            // Use the checkbox value from the form (defaults to false if unchecked)
            $property->allow_resubmit = $request->has('allow_resubmit') && $request->allow_resubmit;
        } else {
            $property->allow_resubmit = null;   // recheck — flag not relevant
        }

        $property->save();

        // Log the action
        PropertyEntryLog::logAction(
            $property,
            $newStatus,
            $oldStatus,
            $newStatus,
            $request->note
        );

        $successMessages = [
            'verified' => 'Property has been verified and sent to administrator for next process.',
            'rejected' => 'Property has been rejected.',
            'recheck' => 'Property has been sent to Field Officer for recheck.',
        ];

        return redirect()->back()
            ->with('success', $successMessages[$newStatus] ?? ('Action taken: ' . ucfirst($newStatus)));
    }

    // ── Toggle Allow Resubmit ─────────────────────────────────────────────────

    public function toggleResubmit(PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);
        abort_if($property->status !== 'rejected', 403, 'Only rejected entries can be toggled.');

        $property->allow_resubmit = !$property->allow_resubmit;
        $property->save();

        PropertyEntryLog::logAction(
            $property,
            $property->allow_resubmit ? 'resubmit_allowed' : 'resubmit_revoked',
            'rejected',
            'rejected'
        );

        $msg = $property->allow_resubmit
            ? 'Field officer can now re-edit and resubmit this entry.'
            : 'Re-edit permission has been revoked.';

        return redirect()->back()->with('success', $msg);
    }

    // ── Review Field ──────────────────────────────────────────────────────────

    public function reviewField(Request $request, PropertyEntry $property)
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        $request->validate([
            'field_name' => 'required|string',
            'field_label' => 'required|string',
            'field_value' => 'nullable|string',
            'is_correct' => 'required|boolean',
            'remark' => 'required_if:is_correct,false|nullable|string|max:500',
        ]);

        $review = \App\Models\PropertyEntryFieldReview::updateOrCreate(
            [
                'property_entry_id' => $property->id,
                'field_name' => $request->field_name,
            ],
            [
                'reviewed_by' => auth()->id(),
                'field_label' => $request->field_label,
                'field_value' => $request->field_value,
                'is_correct' => $request->is_correct,
                'remark' => $request->remark,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->is_correct ? 'Field marked as correct' : 'Field marked as incorrect with remark',
            'review' => $review,
        ]);
    }

    // ── Mark All Correct ──────────────────────────────────────────────────────

    public function markAllCorrect(PropertyEntry $property)
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // Get all reviewable data fields
        $fields = $this->getReviewableFields($property);

        // Mark all data fields as correct
        foreach ($fields as $field) {
            \App\Models\PropertyEntryFieldReview::updateOrCreate(
                [
                    'property_entry_id' => $property->id,
                    'field_name' => $field['name'],
                ],
                [
                    'reviewed_by' => auth()->id(),
                    'field_label' => $field['label'],
                    'field_value' => $field['value'],
                    'is_correct' => true,
                    'remark' => null,
                ]
            );
        }

        // Mark all photo slots as correct
        foreach (self::PHOTO_SLOTS as $index => $slotLabel) {
            \App\Models\PropertyEntryFieldReview::updateOrCreate(
                [
                    'property_entry_id' => $property->id,
                    'field_name' => 'photo_' . $index,
                ],
                [
                    'reviewed_by' => auth()->id(),
                    'field_label' => $slotLabel,
                    'field_value' => 'photo',
                    'is_correct' => true,
                    'remark' => null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'All fields and photos marked as correct',
        ]);
    }

    // ── Mark All Incorrect ────────────────────────────────────────────────────

    public function markAllIncorrect(PropertyEntry $property)
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // A remark is normally required per-field when marking something
        // incorrect (see reviewField()) so the officer knows what to fix —
        // give every field a generic one here rather than leaving it blank.
        $defaultRemark = 'Marked incorrect by reviewer — please review and correct.';

        // Get all reviewable data fields
        $fields = $this->getReviewableFields($property);

        // Mark all data fields as incorrect
        foreach ($fields as $field) {
            \App\Models\PropertyEntryFieldReview::updateOrCreate(
                [
                    'property_entry_id' => $property->id,
                    'field_name' => $field['name'],
                ],
                [
                    'reviewed_by' => auth()->id(),
                    'field_label' => $field['label'],
                    'field_value' => $field['value'],
                    'is_correct' => false,
                    'remark' => $defaultRemark,
                ]
            );
        }

        // Mark all photo slots as incorrect
        foreach (self::PHOTO_SLOTS as $index => $slotLabel) {
            \App\Models\PropertyEntryFieldReview::updateOrCreate(
                [
                    'property_entry_id' => $property->id,
                    'field_name' => 'photo_' . $index,
                ],
                [
                    'reviewed_by' => auth()->id(),
                    'field_label' => $slotLabel,
                    'field_value' => 'photo',
                    'is_correct' => false,
                    'remark' => $defaultRemark,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'All fields and photos marked as incorrect',
        ]);
    }

    // ── Undo All Correct ──────────────────────────────────────────────────────

    public function undoAllCorrect(PropertyEntry $property)
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // Only clear fields currently marked "correct" — back to pending
        // (no review row = pending, same as a never-reviewed field). Fields
        // already marked "incorrect" keep their remark; this undoes a
        // "Mark All Correct" click, it doesn't wipe real reviewer feedback.
        \App\Models\PropertyEntryFieldReview::where('property_entry_id', $property->id)
            ->where('is_correct', true)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All "correct" markings have been cleared',
        ]);
    }

    // ── Helper: Get Reviewable Fields ────────────────────────────────────────

    private function getReviewableFields(PropertyEntry $property): array
    {
        return [
            // A. Location & Identification (16 fields)
            ['name' => 'facility_type', 'label' => 'Facility Type', 'value' => $property->facility_type],
            ['name' => 'property_name', 'label' => 'Name of Property', 'value' => $property->property_name],
            ['name' => 'name_full_address', 'label' => 'Address', 'value' => $property->name_full_address],
            ['name' => 'postal_address_pin', 'label' => 'PIN Code', 'value' => $property->postal_address_pin],
            ['name' => 'village', 'label' => 'Village', 'value' => $property->village],
            ['name' => 'tehsil', 'label' => 'Tehsil', 'value' => $property->tehsil],
            ['name' => 'district', 'label' => 'District', 'value' => $property->district],
            ['name' => 'state', 'label' => 'State', 'value' => $property->state],
            ['name' => 'country', 'label' => 'Country', 'value' => $property->country],
            ['name' => 'nearest_city', 'label' => 'Nearest City', 'value' => $property->nearest_city],
            ['name' => 'nearest_highway', 'label' => 'Nearest Highway', 'value' => $property->nearest_highway],
            ['name' => 'nearest_railway_station', 'label' => 'Nearest Railway Station', 'value' => $property->nearest_railway_station],
            ['name' => 'nearest_airport', 'label' => 'Nearest Airport', 'value' => $property->nearest_airport],
            ['name' => 'owner_contact_name', 'label' => 'Owner Contact Name', 'value' => $property->owner_contact_name],
            ['name' => 'owner_contact_phone', 'label' => 'Owner Contact Phone', 'value' => $property->owner_contact_phone],
            ['name' => 'owner_email', 'label' => 'Owner E-mail', 'value' => $property->owner_email],

            // B. Legal & Statutory Compliance (7 fields)
            ['name' => 'tenure', 'label' => 'Tenure', 'value' => $property->tenure],
            ['name' => 'approved_land_use', 'label' => 'Approved Land Use', 'value' => $property->approved_land_use],
            ['name' => 'fire_noc', 'label' => 'Fire NOC Availability', 'value' => $property->fire_noc],
            ['name' => 'clu_conversion_status', 'label' => 'CLU / Conversion Status', 'value' => $property->clu_conversion_status],
            ['name' => 'pollution_noc', 'label' => 'Pollution NOC', 'value' => $property->pollution_noc],
            ['name' => 'pollution_category', 'label' => 'Pollution Category', 'value' => $property->pollution_category],
            ['name' => 'occupancy_certificate', 'label' => 'Occupancy Certificate', 'value' => $property->occupancy_certificate],

            // C. Property Dimensions (10 fields)
            ['name' => 'plot_area', 'label' => 'Plot Area — as per CLU (sq ft)', 'value' => $property->plot_area],
            ['name' => 'built_up_area', 'label' => 'Built-up Area (sq ft)', 'value' => $property->built_up_area],
            ['name' => 'carpet_area', 'label' => 'Carpet Area (sq ft)', 'value' => $property->carpet_area],
            ['name' => 'available_area', 'label' => 'Available Area (sq ft)', 'value' => $property->available_area],
            ['name' => 'clear_height_highest', 'label' => 'Clear Height — Highest (ft)', 'value' => $property->clear_height_highest],
            ['name' => 'clear_height_side', 'label' => 'Clear Height — Side Wall (ft)', 'value' => $property->clear_height_side],
            ['name' => 'shed_width', 'label' => 'Shed Width (ft)', 'value' => $property->shed_width],
            ['name' => 'shed_length', 'label' => 'Shed Length (ft)', 'value' => $property->shed_length],
            ['name' => 'number_of_floors', 'label' => 'Number of Floors', 'value' => $property->number_of_floors],
            ['name' => 'fsi_far', 'label' => 'FSI / FAR', 'value' => $property->fsi_far],

            // C. Dock, Exit & Width Details (22 fields)
            ['name' => 'dock_door_count', 'label' => 'Dock Door Count — Total', 'value' => $property->dock_door_count],
            ['name' => 'dock_front', 'label' => 'Dock Doors — Front', 'value' => $property->dock_front],
            ['name' => 'dock_left', 'label' => 'Dock Doors — Left', 'value' => $property->dock_left],
            ['name' => 'dock_right', 'label' => 'Dock Doors — Right', 'value' => $property->dock_right],
            ['name' => 'dock_back', 'label' => 'Dock Doors — Back', 'value' => $property->dock_back],
            ['name' => 'has_dock_leveller', 'label' => 'Dock Levellers Available?', 'value' => is_null($property->has_dock_leveller) ? null : ($property->has_dock_leveller ? 'Yes' : 'No')],
            ['name' => 'dock_leveller_front', 'label' => 'Dock Leveller — Front', 'value' => $property->dock_leveller_front],
            ['name' => 'dock_leveller_left', 'label' => 'Dock Leveller — Left', 'value' => $property->dock_leveller_left],
            ['name' => 'dock_leveller_right', 'label' => 'Dock Leveller — Right', 'value' => $property->dock_leveller_right],
            ['name' => 'dock_leveller_back', 'label' => 'Dock Leveller — Back', 'value' => $property->dock_leveller_back],
            ['name' => 'fire_exit_front', 'label' => 'Fire Exit — Front', 'value' => $property->fire_exit_front],
            ['name' => 'fire_exit_left', 'label' => 'Fire Exit — Left', 'value' => $property->fire_exit_left],
            ['name' => 'fire_exit_right', 'label' => 'Fire Exit — Right', 'value' => $property->fire_exit_right],
            ['name' => 'fire_exit_back', 'label' => 'Fire Exit — Back', 'value' => $property->fire_exit_back],
            ['name' => 'canopy_width_front', 'label' => 'Canopy Width — Front (ft)', 'value' => $property->canopy_width_front],
            ['name' => 'canopy_width_left', 'label' => 'Canopy Width — Left (ft)', 'value' => $property->canopy_width_left],
            ['name' => 'canopy_width_right', 'label' => 'Canopy Width — Right (ft)', 'value' => $property->canopy_width_right],
            ['name' => 'canopy_width_back', 'label' => 'Canopy Width — Back (ft)', 'value' => $property->canopy_width_back],
            ['name' => 'road_width_front', 'label' => 'Road Width — Front (ft)', 'value' => $property->road_width_front],
            ['name' => 'road_width_left', 'label' => 'Road Width — Left (ft)', 'value' => $property->road_width_left],
            ['name' => 'road_width_right', 'label' => 'Road Width — Right (ft)', 'value' => $property->road_width_right],
            ['name' => 'road_width_back', 'label' => 'Road Width — Back (ft)', 'value' => $property->road_width_back],

            // C. Facility Details (22 fields)
            ['name' => 'no_of_offices', 'label' => 'No. of Offices', 'value' => $property->no_of_offices],
            ['name' => 'office_sizes', 'label' => 'Office Sizes', 'value' => is_array($property->office_sizes) ? implode(', ', array_map(fn($o) => ($o['l'] ?? 0) . ' × ' . ($o['w'] ?? 0) . ' ft (' . (($o['l'] ?? 0) * ($o['w'] ?? 0)) . ' sq ft)', array_filter($property->office_sizes, fn($o) => !empty($o['l']) || !empty($o['w'])))) : ($property->office_sizes ?: '—')],
            ['name' => 'canteen', 'label' => 'Canteen', 'value' => $property->canteen ? 'Yes' : 'No'],
            ['name' => 'canteen_size', 'label' => 'Canteen Size', 'value' => $property->canteen_size],
            ['name' => 'stp_plant', 'label' => 'STP Plant', 'value' => $property->stp_plant ? 'Yes' : 'No'],
            ['name' => 'stp_capacity', 'label' => 'STP Capacity', 'value' => $property->stp_capacity],
            ['name' => 'washrooms', 'label' => 'No. of Washrooms', 'value' => $property->washrooms],
            ['name' => 'no_of_urinals', 'label' => 'No. of Urinals', 'value' => $property->no_of_urinals],
            ['name' => 'no_of_closets', 'label' => 'No. of Closets', 'value' => $property->no_of_closets],
            ['name' => 'female_washroom', 'label' => 'Female Washroom', 'value' => $property->female_washroom ? 'Yes' : 'No'],
            ['name' => 'driver_rest_room', 'label' => 'Driver Rest Room', 'value' => $property->driver_rest_room ? 'Yes' : 'No'],
            ['name' => 'mezzanine', 'label' => 'Mezzanine', 'value' => $property->mezzanine ? 'Yes' : 'No'],
            ['name' => 'mezzanine_size', 'label' => 'Mezzanine Size', 'value' => $property->mezzanine_size],
            ['name' => 'structure_type', 'label' => 'Structure Type', 'value' => $property->structure_type],
            ['name' => 'flooring_type', 'label' => 'Flooring Type', 'value' => $property->flooring_type],
            ['name' => 'ventilation_lighting', 'label' => 'Ventilation & Lighting', 'value' => $property->ventilation_lighting],
            ['name' => 'insulation_roof', 'label' => 'Insulation — Roof', 'value' => $property->insulation_roof],
            ['name' => 'insulation_side', 'label' => 'Insulation — Side', 'value' => $property->insulation_side],
            ['name' => 'fire_sprinkler', 'label' => 'Fire Sprinkler', 'value' => $property->fire_sprinkler],
            ['name' => 'scrap_yard', 'label' => 'Scrap Yard', 'value' => $property->scrap_yard ? 'Yes' : 'No'],
            ['name' => 'no_of_companies_same_premise', 'label' => 'No. of Companies on Same Premise', 'value' => $property->no_of_companies_same_premise],
            ['name' => 'extension_possible', 'label' => 'Extension Possible', 'value' => $property->extension_possible ? 'Yes' : 'No'],

            // D. Loading & Docking (4 fields)
            ['name' => 'dock_type', 'label' => 'Dock Type', 'value' => $property->dock_type],
            ['name' => 'dock_height', 'label' => 'Dock Height (ft)', 'value' => $property->dock_height],
            ['name' => 'truck_movement', 'label' => 'Truck Movement', 'value' => $property->truck_movement],
            ['name' => 'office_cabin_area', 'label' => 'Office/Cabin Area (sq ft)', 'value' => $property->office_cabin_area],

            // F. Utilities & Infrastructure (6 fields)
            ['name' => 'power_sanctioned_kva', 'label' => 'Power Sanctioned (KVA)', 'value' => $property->power_sanctioned_kva],
            ['name' => 'discom_name', 'label' => 'DISCOM Name', 'value' => $property->discom_name],
            ['name' => 'water_source', 'label' => 'Water Source', 'value' => $property->water_source],
            ['name' => 'water_tank_capacity', 'label' => 'Water Tank Capacity', 'value' => $property->water_tank_capacity],
            ['name' => 'fire_fighting_system', 'label' => 'Fire Fighting System', 'value' => $property->fire_fighting_system],
            ['name' => 'solar', 'label' => 'Solar', 'value' => $property->solar ? 'Yes' : 'No'],

            // G. Financial & Lease Terms (6 fields)
            ['name' => 'deal_type', 'label' => 'Deal Type', 'value' => $property->deal_type],
            ['name' => 'expected_rent', 'label' => 'Expected Rent (₹/sq ft/mo)', 'value' => $property->expected_rent],
            ['name' => 'expected_sale_price', 'label' => 'Expected Sale Price (₹)', 'value' => $property->expected_sale_price],
            ['name' => 'security_deposit_months', 'label' => 'Security Deposit (months)', 'value' => $property->security_deposit_months],
            ['name' => 'lock_in_years', 'label' => 'Lock-in Period (years)', 'value' => $property->lock_in_years],
            ['name' => 'available_from', 'label' => 'Available From', 'value' => $property->available_from?->format('d M Y')],

            // H. Surroundings & Environment (3 fields)
            ['name' => 'approach_road_width', 'label' => 'Approach Road Width (ft)', 'value' => $property->approach_road_width],
            ['name' => 'top_neighbouring_companies', 'label' => 'Top Neighbouring Companies', 'value' => $property->top_neighbouring_companies],
            ['name' => 'flood_risk', 'label' => 'Flood Risk', 'value' => $property->flood_risk],

            // I. Health & Emergency Nearby (3 fields)
            ['name' => 'nearest_hospital_km', 'label' => 'Nearest Hospital (km)', 'value' => $property->nearest_hospital_km],
            ['name' => 'nearest_fire_station_km', 'label' => 'Nearest Fire Station (km)', 'value' => $property->nearest_fire_station_km],
            ['name' => 'nearest_police_station_km', 'label' => 'Nearest Police Station (km)', 'value' => $property->nearest_police_station_km],

            // K. General Remarks (1 field)
            ['name' => 'remarks', 'label' => 'Remarks', 'value' => $property->remarks],
        ];
        // Total: 15 + 7 + 10 + 21 + 22 + 4 + 6 + 6 + 3 + 3 + 1 = 98 fields
    }

    // ── Helper: Check if all fields reviewed and correct ─────────────────────

    private function allFieldsReviewedAndCorrect(PropertyEntry $property): bool
    {
        $fields = $this->getReviewableFields($property);
        $reviews = $property->fieldReviews()->get()->keyBy('field_name');

        foreach ($fields as $field) {
            $review = $reviews->get($field['name']);

            // If not reviewed or marked as incorrect, return false
            if (!$review || $review->is_correct !== true) {
                return false;
            }
        }

        return true;
    }
}
