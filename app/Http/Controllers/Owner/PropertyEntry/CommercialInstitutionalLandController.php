<?php

namespace App\Http\Controllers\Owner\PropertyEntry;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommercialInstitutionalLandController extends Controller
{
    private const PHOTO_SLOTS = [
        0 => 'Front / Exterior / Living room',
        1 => 'Interior room / Space',
        2 => 'Key feature / Balcony',
        3 => 'Washroom / Kitchen',
        4 => 'Parking / Garden / View',
    ];

    public function create(): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        $slots = self::PHOTO_SLOTS;
        return view('owner.properties.commercial-institutional-land.create', compact('slots'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $validated = $request->except(['_token', 'action']);

        if ($request->input('action') === 'draft') {
            $status = 'draft';
        } else {
            $status = 'submitted';
        }

        $customFields = [];
        $fillable = (new PropertyEntry())->getFillable();
        
        if ($status === 'submitted') {
            $request->validate([
                'available_from' => 'nullable|required_if:availability,From date|date',
                'possession_by' => 'nullable|required_if:construction_status,Under Construction|required_if:property_status,Under Construction|date',
                'possession_by_if_under_constr' => 'nullable|required_if:construction_status,Under Construction|required_if:property_status,Under Construction|date',
                'project_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'project_society_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'builder_developer_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'developer_builder_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'project_rera_id' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'rera_registration_id' => 'nullable|required_if:rera_registered,Yes|string|max:120',
            ]);
        }

        $data = ['property_type' => 'commercial_institutional_land', 'field_officer_id' => auth()->id(), 'status' => $status];

        foreach ($validated as $key => $val) {
            if (in_array($key, $fillable)) {
                $data[$key] = $val;
            } else {
                $customFields[$key] = $val;
            }
        }

        // The wizard's own "Property Type" dropdown collects a SUB-type
        // (e.g. Service Apartment / Co-living / PG) but shares its name with
        // the property_type column that records which of the 13 forms this
        // row belongs to. Left alone the submitted value overwrites the
        // canonical key, which breaks the code prefix (a PG listing was
        // getting a ZI-WH- warehouse code), ofType() filtering and the
        // type-aware admin detail view. Keep the officer's choice as a
        // custom field and re-assert the canonical type.
        if (isset($data['property_type']) && $data['property_type'] !== 'commercial_institutional_land') {
            $customFields['property_sub_type'] = $data['property_type'];
        }
        $data['property_type'] = 'commercial_institutional_land';

        if (!empty($customFields)) {
            $data['custom_fields'] = json_encode($customFields);
        }

        $entry = PropertyEntry::create($data);

        // Reject non-image / oversized uploads before touching storage —
        // this is validation-free for every other field on this form, but a
        // malformed upload can't be allowed through regardless.
        $request->validate([
            'photo_0' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_4' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_5' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        // Upload photos
        for ($i = 0; $i <= 5; $i++) {
            if ($request->hasFile("photo_{$i}")) {
                $path = $request->file("photo_{$i}")->store('property-photos', 'public');
                PropertyEntryPhoto::create([
                    'property_entry_id' => $entry->id,
                    'slot_label'        => self::PHOTO_SLOTS[$i] ?? "Photo {$i}",
                    'file_path'         => $path,
                ]);
            }
        }

        PropertyEntryLog::create([
            'property_entry_id' => $entry->id,
            'user_id'           => auth()->id(),
            'action'            => $status === 'draft' ? 'draft_saved' : 'submitted',
            'note'              => $status === 'draft' ? 'Draft saved' : 'Commercial / Institutional Land submitted',
        ]);

        $msg = $status === 'draft' ? 'Draft saved successfully.' : 'Commercial / Institutional Land submitted successfully.';
        if ($status === 'draft') {
            return redirect()->route('owner.properties.commercial-institutional-land.edit', $entry)
                ->with('success', 'Draft saved successfully.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        return redirect()->route('owner.properties.index')->with('success', $msg);
    }

    public function edit(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        $slots = self::PHOTO_SLOTS;
        return view('owner.properties.commercial-institutional-land.create', compact('property', 'slots'));
    }

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $validated = $request->except(['_token', '_method', 'action']);
        $status = $request->input('action') === 'draft' ? 'draft' : 'submitted';

        $customFields = [];
        $fillable = (new PropertyEntry())->getFillable();
        
        if ($status === 'submitted') {
            $request->validate([
                'available_from' => 'nullable|required_if:availability,From date|date',
                'possession_by' => 'nullable|required_if:construction_status,Under Construction|required_if:property_status,Under Construction|date',
                'possession_by_if_under_constr' => 'nullable|required_if:construction_status,Under Construction|required_if:property_status,Under Construction|date',
                'project_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'project_society_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'builder_developer_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'developer_builder_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'project_rera_id' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',
                'rera_registration_id' => 'nullable|required_if:rera_registered,Yes|string|max:120',
            ]);
        }

        $data = ['status' => $status];

        foreach ($validated as $key => $val) {
            if (in_array($key, $fillable)) {
                $data[$key] = $val;
            } else {
                $customFields[$key] = $val;
            }
        }

        // The wizard's own "Property Type" dropdown collects a SUB-type
        // (e.g. Service Apartment / Co-living / PG) but shares its name with
        // the property_type column that records which of the 13 forms this
        // row belongs to. Left alone the submitted value overwrites the
        // canonical key, which breaks the code prefix (a PG listing was
        // getting a ZI-WH- warehouse code), ofType() filtering and the
        // type-aware admin detail view. Keep the officer's choice as a
        // custom field and re-assert the canonical type.
        if (isset($data['property_type']) && $data['property_type'] !== 'commercial_institutional_land') {
            $customFields['property_sub_type'] = $data['property_type'];
        }
        $data['property_type'] = 'commercial_institutional_land';

        if (!empty($customFields)) {
            $data['custom_fields'] = json_encode($customFields);
        }

        $property->update($data);

        // update() never uploaded photos at all — a field officer editing a
        // draft to attach/replace photos had every image silently dropped.
        // Mirrors store()'s validation + upload loop, and replaces (not
        // duplicates) an existing slot's photo on re-upload.
        $request->validate([
            'photo_0' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_4' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'photo_5' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        for ($i = 0; $i <= 5; $i++) {
            if ($request->hasFile("photo_{$i}")) {
                $path = $request->file("photo_{$i}")->store('property-photos', 'public');
                PropertyEntryPhoto::updateOrCreate(
                    ['property_entry_id' => $property->id, 'slot_label' => self::PHOTO_SLOTS[$i] ?? "Photo {$i}"],
                    ['file_path' => $path]
                );
            }
        }

        if ($status === 'draft') {
            return redirect()->route('owner.properties.commercial-institutional-land.edit', $property)
                ->with('success', 'Draft saved successfully.')
                ->with('wizard_step', $request->input('wizard_step', 0));
        }

        return redirect()->route('owner.properties.index')->with('success', 'Property updated successfully.');
    }
}
