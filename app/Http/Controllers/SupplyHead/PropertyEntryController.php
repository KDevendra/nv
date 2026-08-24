<?php

namespace App\Http\Controllers\SupplyHead;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
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
        $user = auth()->user();
        abort_if($user->role !== 'supply_head', 403);

        // Field officers are reached through the zones this supply head
        // covers; owners are added when can_approve_owner_listings is on.
        $zoneIds = $user->zoneIds();
        $fieldOfficerIds = User::where('role', 'field_officer')->whereIn('zone_id', $zoneIds)->pluck('id');
        $ownerIds = $user->can_approve_owner_listings
            ? User::where('role', 'owner')->pluck('id')
            : collect();
        $assigneeIds = $fieldOfficerIds->concat($ownerIds);

        $fieldOfficers = User::where(function($q) use ($user, $zoneIds) {
            $q->where('role', 'field_officer')->whereIn('zone_id', $zoneIds);
            if ($user->can_approve_owner_listings) {
                $q->orWhere('role', 'owner');
            }
        })->get();

        $inScope = fn($q) => $q->whereIn('field_officer_id', $assigneeIds)
            ->orWhere('supply_head_id', $user->id)
            ->orWhereIn('zone_id', $zoneIds);

        // ── Not-opened entries (always shown at top, separate table) ──────────
        $notOpenedQuery = PropertyEntry::with(['fieldOfficer'])
            ->where($inScope)
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
            ->where($inScope)
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
            'total' => PropertyEntry::where($inScope)->where('status', '!=', 'draft')->count(),
            'pending' => PropertyEntry::where($inScope)->where('status', 'submitted')->count(),
            'verified' => PropertyEntry::where($inScope)->where('status', 'verified')->count(),
            'rejected' => PropertyEntry::where($inScope)->where('status', 'rejected')->count(),
            'recheck' => PropertyEntry::where($inScope)->where('status', 'recheck')->count(),
            'not_opened' => PropertyEntry::where($inScope)->where('status', '!=', 'draft')->whereNull('supply_head_viewed_at')->count(),
        ];

        // The supply head's own unfinished entries — kept out of both tables
        // above (they filter drafts out) and shown in their own resume panel.
        $drafts = PropertyEntry::where('field_officer_id', $user->id)
            ->where('status', 'draft')
            ->latest('updated_at')
            ->get();

        return view('supplyhead.properties.index', compact('entries', 'notOpenedEntries', 'counters', 'fieldOfficers', 'drafts'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];
        return view('supplyhead.properties.create', compact('slots', 'fieldConfigs', 'fieldRemarks'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        abort_if(auth()->user()->role !== 'supply_head', 403);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');

        $data = $this->validateEntry($request, $isDraft);

        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        if ($isDraft) {
            $entry = PropertyEntry::create(array_merge($data, [
                'field_officer_id' => auth()->id(),
                'supply_head_id'   => auth()->id(),
                'status'           => 'draft',
                'submitted_at'     => null,
                'area_unit'        => $request->input('area_unit', 'sq_ft'),
            ]));

            $this->handlePhotos($entry, $request);

            return redirect()->route('supplyhead.properties.edit', $entry)
                ->with('success', 'Draft saved. You can continue editing and submit when ready. Code: ' . $entry->code)
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        // Supply head is both the submitter and the reviewer for entries they
        // add themselves — skip the "submitted, pending supply-head review"
        // stage entirely and go straight to "verified", which is what the
        // admin approval queue actually looks for.
        $entry = PropertyEntry::create(array_merge($data, [
            'field_officer_id'      => auth()->id(),
            'supply_head_id'        => auth()->id(),
            'status'                => 'verified',
            'submitted_at'          => now(),
            'reviewed_at'           => now(),
            'reviewed_by'           => auth()->id(),
            'verified_at'           => now(),
            'supply_head_viewed_at' => now(),
            'area_unit'             => $request->input('area_unit', 'sq_ft'),
        ]));

        $this->handlePhotos($entry, $request);

        PropertyEntryLog::logAction($entry, 'verified', null, 'verified', 'Added directly by supply head — sent straight to admin for approval.');

        return redirect()->route('supplyhead.properties.index')
            ->with('success', 'Property added successfully and sent for admin approval. Code: ' . $entry->code);
    }

    // ── Edit (own drafts only) ────────────────────────────────────────────────

    public function edit(PropertyEntry $property): View
    {
        $this->authorizeDraft($property);

        $property->load('photos');

        $slots = self::PHOTO_SLOTS;
        $fieldConfigs = PropertyFieldConfig::allKeyed();
        $fieldRemarks = [];

        return view('supplyhead.properties.edit', compact('property', 'slots', 'fieldConfigs', 'fieldRemarks'));
    }

    // ── Update (own drafts only) ──────────────────────────────────────────────

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        $this->authorizeDraft($property);

        $action = $request->input('action', 'submit');
        $isDraft = ($action === 'draft');

        $data = $this->validateEntry($request, $isDraft);

        if (isset($data['office_sizes']) && is_string($data['office_sizes'])) {
            $data['office_sizes'] = json_decode($data['office_sizes'], true) ?: [];
        }

        if ($isDraft) {
            $property->update(array_merge($data, [
                'status'       => 'draft',
                'submitted_at' => null,
                'area_unit'    => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
            ]));

            $this->handlePhotos($property, $request);

            return redirect()->route('supplyhead.properties.edit', $property)
                ->with('success', 'Draft saved. You can continue editing and submit when ready.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        // Same one-shot flow as store(): a supply head reviewing their own
        // entry adds nothing, so skip straight to "verified" for admin approval.
        $property->update(array_merge($data, [
            'status'                => 'verified',
            'submitted_at'          => now(),
            'reviewed_at'           => now(),
            'reviewed_by'           => auth()->id(),
            'verified_at'           => now(),
            'supply_head_viewed_at' => now(),
            'area_unit'             => $request->input('area_unit', $property->area_unit ?? 'sq_ft'),
        ]));

        $this->handlePhotos($property, $request);

        PropertyEntryLog::logAction($property, 'verified', 'draft', 'verified', 'Added directly by supply head — sent straight to admin for approval.');

        return redirect()->route('supplyhead.properties.index')
            ->with('success', 'Property added successfully and sent for admin approval. Code: ' . $property->code);
    }

    /**
     * Editing through this controller is limited to the supply head's own
     * drafts — everything else they see belongs to a field officer or owner
     * and is handled through the review actions instead.
     */
    private function authorizeDraft(PropertyEntry $property): void
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);
        abort_if($property->field_officer_id !== auth()->id(), 403);
        abort_if($property->status !== 'draft', 403, 'Only your own drafts can be edited here.');
    }

    // ── Reverse Geocode (Mappls proxy) ───────────────────────────────────────
    // Same proxy as FieldOfficer\PropertyEntryController::reverseGeocode — kept
    // as its own endpoint (rather than reusing that one) so the access check
    // matches this role and the route stays under the supply-head prefix.
    public function reverseGeocode(Request $request): JsonResponse
    {
        abort_if(auth()->user()->role !== 'supply_head', 403);

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

        if ($address && $country) {
            $address = trim(preg_replace('/\s*\(' . preg_quote($country, '/') . '\)\s*$/i', '', $address));
        }

        return response()->json([
            'address' => $address,
            'country' => $country,
        ]);
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

    // ── Validation & Photos (create/store) ───────────────────────────────────
    // Mirrors FieldOfficer\PropertyEntryController's rules/labels/photo
    // handling — same PropertyFieldConfig-driven mandatory/nullable logic and
    // the same 8 photo slots — so a property added by a supply head goes
    // through the exact same data-quality bar as one added in the field.

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

    /**
     * Whether the mandatory fields configured in PropertyFieldConfig are
     * actually enforced for supply-head entries. Driven by
     * SUPPLY_HEAD_ENFORCE_REQUIRED_FIELDS — when it's off, every field is
     * optional so a supply head can record whatever they have on hand. The
     * form partial reads the same config flag for its client-side markers.
     */
    public static function enforcesRequiredFields(): bool
    {
        return (bool) config('property.supply_head.enforce_required_fields', true);
    }

    private function validateEntry(Request $request, bool $isDraft = false): array
    {
        $configs = PropertyFieldConfig::allKeyed();

        // Drafts are always fields-optional; full submissions are only
        // fields-optional when required-field enforcement is switched off.
        $allOptional = $isDraft || !self::enforcesRequiredFields();

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
            'form_submited_location' => 'nullable|string|max:1000',
        ];

        $rules = [];

        foreach ($typeRules as $field => $typeConstraint) {
            $cfg = $configs->get($field);

            if ($cfg && $cfg->keep_field === false) {
                continue;
            }

            if ($allOptional) {
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

        $rules['photos'] = 'nullable|array';
        $rules['photos.*'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240';

        $data = $request->validate($rules, [
            'photos.*.image' => 'Only camera photos are allowed for :attribute.',
            'postal_address_pin.regex' => 'PIN code must be exactly 6 digits.',
            'owner_contact_phone.regex' => 'Contact number must be a valid 10-digit Indian mobile number.',
        ], self::FIELD_LABELS);

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

        if (!$allOptional && (string) $request->input('has_dock_leveller') === '1') {
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
}
