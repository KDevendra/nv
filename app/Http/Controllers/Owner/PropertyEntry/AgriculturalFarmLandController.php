<?php

namespace App\Http\Controllers\Owner\PropertyEntry;

use App\Http\Controllers\Controller;
use App\Models\PropertyEntry;
use App\Models\PropertyEntryPhoto;
use App\Models\PropertyEntryLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgriculturalFarmLandController extends Controller
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
        return view('owner.properties.agricultural-farm-land.create', compact('slots'));
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
        $data = ['property_type' => 'agricultural_farm_land', 'field_officer_id' => auth()->id(), 'status' => $status];

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
        if (isset($data['property_type']) && $data['property_type'] !== 'agricultural_farm_land') {
            $customFields['property_sub_type'] = $data['property_type'];
        }
        $data['property_type'] = 'agricultural_farm_land';

        if (!empty($customFields)) {
            $data['custom_fields'] = json_encode($customFields);
        }

        $entry = PropertyEntry::create($data);

        // Upload photos
        for ($i = 0; $i <= 5; $i++) {
            if ($request->hasFile("photo_{$i}")) {
                $path = $request->file("photo_{$i}")->store('property-photos', 'public');
                PropertyEntryPhoto::create([
                    'property_entry_id' => $entry->id,
                    'slot'              => $i,
                    'photo_path'        => $path,
                ]);
            }
        }

        PropertyEntryLog::create([
            'property_entry_id' => $entry->id,
            'user_id'           => auth()->id(),
            'action'            => $status === 'draft' ? 'draft_saved' : 'submitted',
            'note'              => $status === 'draft' ? 'Draft saved' : 'Agricultural / Farm Land submitted',
        ]);

        $msg = $status === 'draft' ? 'Draft saved successfully.' : 'Agricultural / Farm Land submitted successfully.';
        return redirect()->route('owner.properties.index')->with('success', $msg);
    }

    public function edit(PropertyEntry $property): View
    {
        abort_if(auth()->user()->role !== 'owner', 403);
        $slots = self::PHOTO_SLOTS;
        return view('owner.properties.agricultural-farm-land.create', compact('property', 'slots'));
    }

    public function update(Request $request, PropertyEntry $property): RedirectResponse
    {
        abort_if(auth()->user()->role !== 'owner', 403);

        $validated = $request->except(['_token', '_method', 'action']);
        $status = $request->input('action') === 'draft' ? 'draft' : 'submitted';

        $customFields = [];
        $fillable = (new PropertyEntry())->getFillable();
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
        if (isset($data['property_type']) && $data['property_type'] !== 'agricultural_farm_land') {
            $customFields['property_sub_type'] = $data['property_type'];
        }
        $data['property_type'] = 'agricultural_farm_land';

        if (!empty($customFields)) {
            $data['custom_fields'] = json_encode($customFields);
        }

        $property->update($data);

        return redirect()->route('owner.properties.index')->with('success', 'Agricultural / Farm Land updated successfully.');
    }
}
