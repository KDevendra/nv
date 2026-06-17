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
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // Get field officers under this supply head
        $fieldOfficerIds = User::where('supply_head_id', auth()->id())->pluck('id');
        $fieldOfficers = User::where('supply_head_id', auth()->id())->get();
        
        $query = PropertyEntry::with(['fieldOfficer'])->whereIn('field_officer_id', $fieldOfficerIds)->latest('submitted_at');

        // Field Officer filter
        if ($request->filled('field_officer')) {
            $query->where('field_officer_id', $request->field_officer);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('nearest_city', 'like', "%{$search}%")
                  ->orWhere('facility_type', 'like', "%{$search}%")
                  ->orWhereHas('fieldOfficer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $entries = $query->paginate(15)->appends($request->query());

        $counters = [
            'total'    => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->count(),
            'pending'  => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'submitted')->count(),
            'verified' => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'verified')->count(),
            'rejected' => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'rejected')->count(),
            'recheck'  => PropertyEntry::whereIn('field_officer_id', $fieldOfficerIds)->where('status', 'recheck')->count(),
        ];

        return view('supplyhead.properties.index', compact('entries', 'counters', 'fieldOfficers'));
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        $property->load(['photos', 'fieldOfficer', 'reviewer', 'logs.user', 'fieldReviews']);
        $slots = self::PHOTO_SLOTS;
        
        // Get all reviewable fields with their review status
        $fields = collect($this->getReviewableFields($property))->map(function ($field) use ($property) {
            $review = $property->fieldReviews->firstWhere('field_name', $field['name']);
            return array_merge($field, [
                'is_correct' => $review?->is_correct,
                'remark' => $review?->remark,
            ]);
        });

        return view('supplyhead.properties.show', compact('property', 'slots', 'fields'));
    }

    // ── Action ────────────────────────────────────────────────────────────────

    public function action(Request $request, PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        $request->validate([
            'action' => 'required|in:verified,rejected,recheck',
            'note'   => 'required_if:action,rejected,recheck|nullable|string|max:1000',
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

        $property->status           = $newStatus;
        $property->supply_head_note = $request->note;
        $property->reviewed_at      = now();
        $property->reviewed_by      = auth()->id();

        if ($newStatus === 'verified') {
            $property->verified_at    = now();
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

        return redirect()->back()
            ->with('success', 'Action taken: ' . ucfirst($newStatus));
    }

    // ── Toggle Allow Resubmit ─────────────────────────────────────────────────

    public function toggleResubmit(PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);
        abort_if($property->status !== 'rejected', 403, 'Only rejected entries can be toggled.');

        $property->allow_resubmit = ! $property->allow_resubmit;
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
            'field_name'  => 'required|string',
            'field_label' => 'required|string',
            'field_value' => 'nullable|string',
            'is_correct'  => 'required|boolean',
            'remark'      => 'required_if:is_correct,false|nullable|string|max:500',
        ]);

        $review = \App\Models\PropertyEntryFieldReview::updateOrCreate(
            [
                'property_entry_id' => $property->id,
                'field_name'        => $request->field_name,
            ],
            [
                'reviewed_by'  => auth()->id(),
                'field_label'  => $request->field_label,
                'field_value'  => $request->field_value,
                'is_correct'   => $request->is_correct,
                'remark'       => $request->remark,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->is_correct ? 'Field marked as correct' : 'Field marked as incorrect with remark',
            'review'  => $review,
        ]);
    }

    // ── Mark All Correct ──────────────────────────────────────────────────────

    public function markAllCorrect(PropertyEntry $property)
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        // Get all reviewable fields
        $fields = $this->getReviewableFields($property);

        foreach ($fields as $field) {
            \App\Models\PropertyEntryFieldReview::updateOrCreate(
                [
                    'property_entry_id' => $property->id,
                    'field_name'        => $field['name'],
                ],
                [
                    'reviewed_by'  => auth()->id(),
                    'field_label'  => $field['label'],
                    'field_value'  => $field['value'],
                    'is_correct'   => true,
                    'remark'       => null,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'All fields marked as correct',
        ]);
    }

    // ── Helper: Get Reviewable Fields ────────────────────────────────────────

    private function getReviewableFields(PropertyEntry $property): array
    {
        return [
            // A. Location
            ['name' => 'facility_type', 'label' => 'Facility Type', 'value' => $property->facility_type],
            ['name' => 'nearest_city', 'label' => 'Nearest City', 'value' => $property->nearest_city],
            ['name' => 'village_town_district', 'label' => 'Village/Town/District', 'value' => $property->village_town_district],
            ['name' => 'postal_address_pin', 'label' => 'PIN Code', 'value' => $property->postal_address_pin],
            ['name' => 'nearest_highway', 'label' => 'Nearest Highway', 'value' => $property->nearest_highway],
            ['name' => 'nearest_railway_station', 'label' => 'Nearest Railway Station', 'value' => $property->nearest_railway_station],
            ['name' => 'nearest_airport', 'label' => 'Nearest Airport', 'value' => $property->nearest_airport],
            ['name' => 'name_full_address', 'label' => 'Full Address', 'value' => $property->name_full_address],
            
            // B. Legal
            ['name' => 'tenure', 'label' => 'Tenure', 'value' => $property->tenure],
            ['name' => 'approved_land_use', 'label' => 'Approved Land Use', 'value' => $property->approved_land_use],
            ['name' => 'fire_noc', 'label' => 'Fire NOC', 'value' => $property->fire_noc],
            ['name' => 'clu_conversion_status', 'label' => 'CLU Conversion Status', 'value' => $property->clu_conversion_status],
            ['name' => 'occupancy_certificate', 'label' => 'Occupancy Certificate', 'value' => $property->occupancy_certificate],
            
            // C. Dimensions
            ['name' => 'plot_area', 'label' => 'Plot Area (sq ft)', 'value' => $property->plot_area],
            ['name' => 'built_up_area', 'label' => 'Built-up Area (sq ft)', 'value' => $property->built_up_area],
            ['name' => 'clear_height_highest', 'label' => 'Clear Height — Highest (ft)', 'value' => $property->clear_height_highest],
            ['name' => 'clear_height_side', 'label' => 'Clear Height — Side (ft)', 'value' => $property->clear_height_side],
            ['name' => 'number_of_floors', 'label' => 'Number of Floors', 'value' => $property->number_of_floors],
            ['name' => 'fsi_far', 'label' => 'FSI/FAR', 'value' => $property->fsi_far],
            
            // D. Docking
            ['name' => 'dock_door_count', 'label' => 'Dock Door Count', 'value' => $property->dock_door_count],
            ['name' => 'dock_type', 'label' => 'Dock Type', 'value' => $property->dock_type],
            ['name' => 'dock_height', 'label' => 'Dock Height (ft)', 'value' => $property->dock_height],
            ['name' => 'truck_movement', 'label' => 'Truck Movement', 'value' => $property->truck_movement],
            
            // E-F. Environment & Utilities
            ['name' => 'flooring_type', 'label' => 'Flooring Type', 'value' => $property->flooring_type],
            ['name' => 'office_cabin_area', 'label' => 'Office/Cabin Area (sq ft)', 'value' => $property->office_cabin_area],
            ['name' => 'washrooms', 'label' => 'Washrooms', 'value' => $property->washrooms],
            ['name' => 'ventilation_lighting', 'label' => 'Ventilation & Lighting', 'value' => $property->ventilation_lighting],
            ['name' => 'power_sanctioned_kva', 'label' => 'Power Sanctioned (KVA)', 'value' => $property->power_sanctioned_kva],
            ['name' => 'discom_name', 'label' => 'DISCOM Name', 'value' => $property->discom_name],
            ['name' => 'water_source', 'label' => 'Water Source', 'value' => $property->water_source],
            ['name' => 'fire_fighting_system', 'label' => 'Fire Fighting System', 'value' => $property->fire_fighting_system],
            
            // G. Financial
            ['name' => 'deal_type', 'label' => 'Deal Type', 'value' => $property->deal_type],
            ['name' => 'expected_rent', 'label' => 'Expected Rent (₹/sq ft/mo)', 'value' => $property->expected_rent],
            ['name' => 'expected_sale_price', 'label' => 'Expected Sale Price (₹)', 'value' => $property->expected_sale_price],
            ['name' => 'security_deposit_months', 'label' => 'Security Deposit (months)', 'value' => $property->security_deposit_months],
            ['name' => 'lock_in_years', 'label' => 'Lock-in Period (years)', 'value' => $property->lock_in_years],
            ['name' => 'available_from', 'label' => 'Available From', 'value' => $property->available_from?->format('d M Y')],
            
            // H-I. Surroundings
            ['name' => 'approach_road_width', 'label' => 'Approach Road Width (ft)', 'value' => $property->approach_road_width],
            ['name' => 'top_neighbouring_companies', 'label' => 'Top Neighbouring Companies', 'value' => $property->top_neighbouring_companies],
            ['name' => 'flood_risk', 'label' => 'Flood Risk', 'value' => $property->flood_risk],
            ['name' => 'nearest_hospital_km', 'label' => 'Nearest Hospital (km)', 'value' => $property->nearest_hospital_km],
            ['name' => 'nearest_fire_station_km', 'label' => 'Nearest Fire Station (km)', 'value' => $property->nearest_fire_station_km],
            ['name' => 'nearest_police_station_km', 'label' => 'Nearest Police Station (km)', 'value' => $property->nearest_police_station_km],
            
            // K. Remarks
            ['name' => 'owner_contact_name', 'label' => 'Owner Contact Name', 'value' => $property->owner_contact_name],
            ['name' => 'owner_contact_phone', 'label' => 'Owner Contact Phone', 'value' => $property->owner_contact_phone],
            ['name' => 'remarks', 'label' => 'Remarks', 'value' => $property->remarks],
        ];
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
