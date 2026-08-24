@extends('layouts.owner')
@php if (!isset($property)) { $property = null; } @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Retail Shop / Showroom - Owner Portal')

@section('content')
    @php
        $steps = [
            ['key' => 'A', 'title' => 'Submitter & Owner'],
            ['key' => 'B', 'title' => 'Location & Project'],
            ['key' => 'C', 'title' => 'Property & Config'],
            ['key' => 'D', 'title' => 'Legal & Amenities'],
            ['key' => 'E', 'title' => 'Commercials & Terms'],
            ['key' => 'F', 'title' => 'Photos & Remarks'],
        ];
    @endphp

    <div class="max-w-5xl mx-auto space-y-6" x-data="stepWizard()">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">
                    {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Retail Shop / Showroom' }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Retail Shop / Showroom
                </p>
            </div>
            <a href="{{ route('owner.properties.select-type') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Choose Other Type
            </a>
        </div>



        <form method="POST"
            action="{{ isset($property) ? route('owner.properties.retail-shop-showroom.update', $property) : route('owner.properties.retail-shop-showroom.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif

            <x-property-wizard-shell :steps="$steps" :title="'Retail Shop / Showroom Submission Form'"
                :propertyType="'retail_shop_showroom'">

                <div class="wizard-step-content space-y-6" style="display:block">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Commercial Shop · Showroom ·
                                    Space in Retail Mall · SCO · 99acres-aligned </label>
                                <input type="text" name="commercial_shop_showroom_space_in_retail_mall_sco_99acres_al"
                                    value="{{ old('commercial_shop_showroom_space_in_retail_mall_sco_99acres_al', $property?->fieldValue('commercial_shop_showroom_space_in_retail_mall_sco_99acres_al') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS (stored
                                internally · never published)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Full Name <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="submitter_full_name"
                                    value="{{ old('submitter_full_name', $property?->fieldValue('submitter_full_name') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Phone <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="submitter_phone"
                                    value="{{ old('submitter_phone', $property?->fieldValue('submitter_phone') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Email <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="submitter_email"
                                    value="{{ old('submitter_email', $property?->fieldValue('submitter_email') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Role <span
                                        class="text-red-500">*</span></label>
                                <select required name="submitter_role"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Owner" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="Builder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Builder' ? 'selected' : '' }}>Builder</option>
                                    <option value="Authorised Agent" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Authorised Agent' ? 'selected' : '' }}>Authorised Agent</option>
                                    <option value="Broker" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Broker' ? 'selected' : '' }}>Broker</option>
                                    <option value="GPA holder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'GPA holder' ? 'selected' : '' }}>GPA holder</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Owner Full Name <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="owner_full_name"
                                    value="{{ old('owner_full_name', $property?->fieldValue('owner_full_name') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Owner Contact Number <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="owner_contact_number"
                                    value="{{ old('owner_contact_number', $property?->fieldValue('owner_contact_number') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION B — LOCATION & IDENTIFICATION</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pin Code <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="pin_code"
                                    value="{{ old('pin_code', $property?->fieldValue('pin_code') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City <span
                                        class="text-red-500">*</span></label>
                                <select required name="city"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="active cities + Other" {{ old('city', $property?->fieldValue('city') ?? '') === 'active cities + Other' ? 'selected' : '' }}>active cities + Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Locality / Broad Area <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="locality_broad_area"
                                    value="{{ old('locality_broad_area', $property?->fieldValue('locality_broad_area') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project Name </label>
                                <input type="text" name="project_name"
                                    value="{{ old('project_name', $property?->fieldValue('project_name') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Builder / Developer Name
                                </label>
                                <input type="text" name="builder_developer_name"
                                    value="{{ old('builder_developer_name', $property?->fieldValue('builder_developer_name') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Address (house/plot no.,
                                    street) <span class="text-red-500">*</span></label>
                                <input required type="text" name="full_address_house_plot_no_street"
                                    value="{{ old('full_address_house_plot_no_street', $property?->fieldValue('full_address_house_plot_no_street') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="state" value="{{ old('state', $property?->fieldValue('state') ?? '') }}"
                                    required placeholder="State"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GPS Latitude <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="0.000001" name="gps_latitude"
                                    value="{{ old('gps_latitude', $property?->fieldValue('gps_latitude') ?? '') }}" required
                                    placeholder="e.g. 28.459512"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-sm font-medium text-gray-700">GPS Longitude <span
                                            class="text-red-500">*</span></label>
                                    <button type="button"
                                        class="btn-use-gps-location text-xs text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1 focus:outline-none">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Use Current Location</span>
                                    </button>
                                </div>
                                <input type="number" step="0.000001" name="gps_longitude"
                                    value="{{ old('gps_longitude', $property?->fieldValue('gps_longitude') ?? '') }}" required
                                    placeholder="e.g. 77.026634"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nearby Landmarks / Key Distances
                                </label>
                                <input type="text" name="nearby_landmarks_key_distances"
                                    value="{{ old('nearby_landmarks_key_distances', $property?->fieldValue('nearby_landmarks_key_distances') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Facing / Orientation </label>
                                <select name="facing_orientation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="N" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'N' ? 'selected' : '' }}>North</option>
                                    <option value="E" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'E' ? 'selected' : '' }}>East</option>
                                    <option value="W" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'W' ? 'selected' : '' }}>West</option>
                                    <option value="S" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'S' ? 'selected' : '' }}>South</option>
                                    <option value="NE" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'NE' ? 'selected' : '' }}>North-East</option>
                                    <option value="NW" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'NW' ? 'selected' : '' }}>North-West</option>
                                    <option value="SE" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'SE' ? 'selected' : '' }}>South-East</option>
                                    <option value="SW" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'SW' ? 'selected' : '' }}>South-West</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION B2 — PROJECT / SOCIETY (if unit is part
                                of a builder project)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Part of a Project / Society
                                    <span class="text-red-500">*</span></label>
                                <select name="part_of_a_project_society" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('part_of_a_project_society', $property?->fieldValue('part_of_a_project_society') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('part_of_a_project_society', $property?->fieldValue('part_of_a_project_society') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project RERA ID </label>
                                <input type="text" name="project_rera_id"
                                    value="{{ old('project_rera_id', $property?->fieldValue('project_rera_id') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Towers / Blocks </label>
                                <input type="number" step="any" name="total_towers_blocks"
                                    value="{{ old('total_towers_blocks', $property?->fieldValue('total_towers_blocks') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Configurations Offered </label>
                                <select name="configurations_offered[]" multiple class="select2-multiple w-full">
                                    @php $sel = old('configurations_offered', $property?->fieldValue('configurations_offered') ?? []); @endphp
                                    <option value="1" {{ in_array('1', (array) $sel) ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ in_array('2', (array) $sel) ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ in_array('3', (array) $sel) ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ in_array('4', (array) $sel) ? 'selected' : '' }}>4</option>
                                    <option value="4+ BHK" {{ in_array('4+ BHK', (array) $sel) ? 'selected' : '' }}>4+ BHK
                                    </option>
                                    <option value="Studio" {{ in_array('Studio', (array) $sel) ? 'selected' : '' }}>Studio
                                    </option>
                                    <option value="Shop" {{ in_array('Shop', (array) $sel) ? 'selected' : '' }}>Shop</option>
                                    <option value="Office" {{ in_array('Office', (array) $sel) ? 'selected' : '' }}>Office
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project Amenities </label>
                                <select name="project_amenities[]" multiple class="select2-multiple w-full">
                                    @php $sel = old('project_amenities', $property?->fieldValue('project_amenities') ?? []); @endphp
                                    <option value="Clubhouse" {{ in_array('Clubhouse', (array) $sel) ? 'selected' : '' }}>
                                        Clubhouse</option>
                                    <option value="Pool" {{ in_array('Pool', (array) $sel) ? 'selected' : '' }}>Pool</option>
                                    <option value="Gym" {{ in_array('Gym', (array) $sel) ? 'selected' : '' }}>Gym</option>
                                    <option value="Park" {{ in_array('Park', (array) $sel) ? 'selected' : '' }}>Park</option>
                                    <option value="Sports" {{ in_array('Sports', (array) $sel) ? 'selected' : '' }}>Sports
                                    </option>
                                    <option value="Power backup" {{ in_array('Power backup', (array) $sel) ? 'selected' : '' }}>Power backup</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C — RETAIL UNIT CONFIGURATION</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span
                                        class="text-red-500">*</span></label>
                                <select required name="property_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Shop" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Shop' ? 'selected' : '' }}>Shop</option>
                                    <option value="Showroom" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Showroom' ? 'selected' : '' }}>Showroom</option>
                                    <option value="Space in Retail Mall" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Space in Retail Mall' ? 'selected' : '' }}>Space in Retail Mall</option>
                                    <option value="High-Street" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'High-Street' ? 'selected' : '' }}>High-Street</option>
                                    <option value="SCO" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'SCO' ? 'selected' : '' }}>SCO</option>
                                    <option value="Food Court" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Food Court' ? 'selected' : '' }}>Food Court</option>
                                    <option value="Restaurant space" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Restaurant space' ? 'selected' : '' }}>Restaurant space</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carpet Area (sq ft) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="carpet_area_sq_ft"
                                    value="{{ old('carpet_area_sq_ft', $property?->fieldValue('carpet_area_sq_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Built-up / Chargeable Area (sq
                                    ft) </label>
                                <input type="number" step="any" name="built_up_chargeable_area_sq_ft"
                                    value="{{ old('built_up_chargeable_area_sq_ft', $property?->fieldValue('built_up_chargeable_area_sq_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frontage Width (ft) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="frontage_width_ft"
                                    value="{{ old('frontage_width_ft', $property?->fieldValue('frontage_width_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frontage Height / Signage (ft)
                                </label>
                                <input type="number" step="any" name="frontage_height_signage_ft"
                                    value="{{ old('frontage_height_signage_ft', $property?->fieldValue('frontage_height_signage_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Floor <span
                                        class="text-red-500">*</span></label>
                                <select required name="floor"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Ground" {{ old('floor', $property?->fieldValue('floor') ?? '') === 'Ground' ? 'selected' : '' }}>Ground</option>
                                    <option value="Upper" {{ old('floor', $property?->fieldValue('floor') ?? '') === 'Upper' ? 'selected' : '' }}>Upper</option>
                                    <option value="Basement" {{ old('floor', $property?->fieldValue('floor') ?? '') === 'Basement' ? 'selected' : '' }}>Basement</option>
                                    <option value="Mezzanine" {{ old('floor', $property?->fieldValue('floor') ?? '') === 'Mezzanine' ? 'selected' : '' }}>Mezzanine</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ceiling Height (ft) </label>
                                <input type="text" name="ceiling_height_ft"
                                    value="{{ old('ceiling_height_ft', $property?->fieldValue('ceiling_height_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mezzanine Available </label>
                                <select name="mezzanine_available"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('mezzanine_available', $property?->fieldValue('mezzanine_available') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('mezzanine_available', $property?->fieldValue('mezzanine_available') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frontage Type </label>
                                <select name="frontage_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Main road" {{ old('frontage_type', $property?->fieldValue('frontage_type') ?? '') === 'Main road' ? 'selected' : '' }}>Main road</option>
                                    <option value="Internal" {{ old('frontage_type', $property?->fieldValue('frontage_type') ?? '') === 'Internal' ? 'selected' : '' }}>Internal</option>
                                    <option value="Corner" {{ old('frontage_type', $property?->fieldValue('frontage_type') ?? '') === 'Corner' ? 'selected' : '' }}>Corner</option>
                                    <option value="Mall atrium" {{ old('frontage_type', $property?->fieldValue('frontage_type') ?? '') === 'Mall atrium' ? 'selected' : '' }}>Mall atrium</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Footfall Location Type </label>
                                <select name="footfall_location_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="High-street" {{ old('footfall_location_type', $property?->fieldValue('footfall_location_type') ?? '') === 'High-street' ? 'selected' : '' }}>
                                        High-street</option>
                                    <option value="Mall" {{ old('footfall_location_type', $property?->fieldValue('footfall_location_type') ?? '') === 'Mall' ? 'selected' : '' }}>Mall</option>
                                    <option value="Market" {{ old('footfall_location_type', $property?->fieldValue('footfall_location_type') ?? '') === 'Market' ? 'selected' : '' }}>Market
                                    </option>
                                    <option value="Standalone" {{ old('footfall_location_type', $property?->fieldValue('footfall_location_type') ?? '') === 'Standalone' ? 'selected' : '' }}>
                                        Standalone</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Suitable For (activity) </label>
                                <input type="text" name="suitable_for_activity"
                                    value="{{ old('suitable_for_activity', $property?->fieldValue('suitable_for_activity') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Washroom </label>
                                <select name="washroom"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Private" {{ old('washroom', $property?->fieldValue('washroom') ?? '') === 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Common" {{ old('washroom', $property?->fieldValue('washroom') ?? '') === 'Common' ? 'selected' : '' }}>Common</option>
                                    <option value="None" {{ old('washroom', $property?->fieldValue('washroom') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parking for Customers </label>
                                <select name="parking_for_customers"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Ample" {{ old('parking_for_customers', $property?->fieldValue('parking_for_customers') ?? '') === 'Ample' ? 'selected' : '' }}>Ample</option>
                                    <option value="Limited" {{ old('parking_for_customers', $property?->fieldValue('parking_for_customers') ?? '') === 'Limited' ? 'selected' : '' }}>Limited</option>
                                    <option value="None" {{ old('parking_for_customers', $property?->fieldValue('parking_for_customers') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C2 — POSSESSION, FURNISHING & LISTING
                                STATE (drives SEO filter pages)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Status </label>
                                <select name="property_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="New" {{ old('property_status', $property?->fieldValue('property_status') ?? '') === 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Resale" {{ old('property_status', $property?->fieldValue('property_status') ?? '') === 'Resale' ? 'selected' : '' }}>Resale</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Construction / Listing Status
                                    <span class="text-red-500">*</span></label>
                                <select required name="construction_listing_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Ready to Move" {{ old('construction_listing_status', $property?->fieldValue('construction_listing_status') ?? '') === 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                                    <option value="Under Construction" {{ old('construction_listing_status', $property?->fieldValue('construction_listing_status') ?? '') === 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                                    <option value="New Launch" {{ old('construction_listing_status', $property?->fieldValue('construction_listing_status') ?? '') === 'New Launch' ? 'selected' : '' }}>
                                        New Launch</option>
                                    <option value="Pre-launch" {{ old('construction_listing_status', $property?->fieldValue('construction_listing_status') ?? '') === 'Pre-launch' ? 'selected' : '' }}>
                                        Pre-launch</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Possession By (if under-constr.)
                                </label>
                                <input type="date" name="possession_by_if_under_constr"
                                    value="{{ old('possession_by_if_under_constr', ($property?->fieldValue('possession_by_if_under_constr') !== null) ? $property?->fieldValue('possession_by_if_under_constr')->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing / Fit-out <span
                                        class="text-red-500">*</span></label>
                                <select required name="furnishing_fit_out"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Bare shell" {{ old('furnishing_fit_out', $property?->fieldValue('furnishing_fit_out') ?? '') === 'Bare shell' ? 'selected' : '' }}>Bare shell</option>
                                    <option value="Fitted" {{ old('furnishing_fit_out', $property?->fieldValue('furnishing_fit_out') ?? '') === 'Fitted' ? 'selected' : '' }}>Fitted</option>
                                    <option value="Furnished" {{ old('furnishing_fit_out', $property?->fieldValue('furnishing_fit_out') ?? '') === 'Furnished' ? 'selected' : '' }}>Furnished</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Located Inside </label>
                                <select name="located_inside"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="SEZ" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'SEZ' ? 'selected' : '' }}>SEZ</option>
                                    <option value="IT Park" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'IT Park' ? 'selected' : '' }}>IT Park</option>
                                    <option value="Business Park" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'Business Park' ? 'selected' : '' }}>Business Park</option>
                                    <option value="Industrial Estate" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'Industrial Estate' ? 'selected' : '' }}>Industrial Estate</option>
                                    <option value="Standalone" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'Standalone' ? 'selected' : '' }}>Standalone</option>
                                    <option value="Mall" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'Mall' ? 'selected' : '' }}>Mall</option>
                                    <option value="High-street" {{ old('located_inside', $property?->fieldValue('located_inside') ?? '') === 'High-street' ? 'selected' : '' }}>High-street</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age of Building </label>
                                <select name="age_of_building"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="<1" {{ old('age_of_building', $property?->fieldValue('age_of_building') ?? '') === '<1' ? 'selected' : '' }}>
                                        <1< /option>
                                    <option value="1-5" {{ old('age_of_building', $property?->fieldValue('age_of_building') ?? '') === '1-5' ? 'selected' : '' }}>1-5</option>
                                    <option value="5-10" {{ old('age_of_building', $property?->fieldValue('age_of_building') ?? '') === '5-10' ? 'selected' : '' }}>5-10</option>
                                    <option value="10+ yrs" {{ old('age_of_building', $property?->fieldValue('age_of_building') ?? '') === '10+ yrs' ? 'selected' : '' }}>10+ yrs</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Availability <span
                                        class="text-red-500">*</span></label>
                                <select required name="availability"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Immediate" {{ old('availability', $property?->fieldValue('availability') ?? '') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                                    <option value="From date" {{ old('availability', $property?->fieldValue('availability') ?? '') === 'From date' ? 'selected' : '' }}>From date</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Available From Date </label>
                                <input type="date" name="available_from_date"
                                    value="{{ old('available_from_date', ($property?->fieldValue('available_from_date') !== null) ? $property?->fieldValue('available_from_date')->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Loan / Lease Financing
                                    Available </label>
                                <select name="bank_loan_lease_financing_available"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('bank_loan_lease_financing_available', $property?->fieldValue('bank_loan_lease_financing_available') ?? '') === 'Yes' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="No" {{ old('bank_loan_lease_financing_available', $property?->fieldValue('bank_loan_lease_financing_available') ?? '') === 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION D — LEGAL & COMPLIANCE</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ownership / Title Type <span
                                        class="text-red-500">*</span></label>
                                <select required name="ownership_title_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Freehold" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                    <option value="Leasehold-Govt" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Leasehold-Govt' ? 'selected' : '' }}>
                                        Leasehold-Govt</option>
                                    <option value="Leasehold-Private" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Leasehold-Private' ? 'selected' : '' }}>
                                        Leasehold-Private</option>
                                    <option value="Society" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Society' ? 'selected' : '' }}>Society</option>
                                    <option value="Other" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="title_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Clear" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                    <option value="Under Dispute" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Under Dispute' ? 'selected' : '' }}>Under Dispute</option>
                                    <option value="Encumbrance Being Resolved" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Encumbrance Being Resolved' ? 'selected' : '' }}>
                                        Encumbrance Being Resolved</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registered <span
                                        class="text-red-500">*</span></label>
                                <select required name="rera_registered"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Not Applicable" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registration ID <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="rera_registration_id"
                                    value="{{ old('rera_registration_id', $property?->fieldValue('rera_registration_id') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Occupancy Certificate (OC) <span
                                        class="text-red-500">*</span></label>
                                <select required name="occupancy_certificate_oc"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Received" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Received' ? 'selected' : '' }}>
                                        Received</option>
                                    <option value="Applied" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Applied' ? 'selected' : '' }}>Applied
                                    </option>
                                    <option value="Not Received" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Not Received' ? 'selected' : '' }}>Not
                                        Received</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CLU / Land Use Approval <span
                                        class="text-red-500">*</span></label>
                                <select required name="clu_land_use_approval"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Commercial Approved" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Commercial Approved' ? 'selected' : '' }}>Commercial Approved</option>
                                    <option value="Mixed-Use" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use
                                    </option>
                                    <option value="Conversion Applied" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Conversion Applied' ? 'selected' : '' }}>
                                        Conversion Applied</option>
                                    <option value="Not Converted" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Not Converted' ? 'selected' : '' }}>Not
                                        Converted</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fire NOC Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="fire_noc_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Obtained" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                    <option value="Applied — In Process" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Applied — In Process' ? 'selected' : '' }}>
                                        Applied — In Process</option>
                                    <option value="Not Yet Applied" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Not Yet Applied' ? 'selected' : '' }}>Not Yet Applied</option>
                                    <option value="Not Required" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Not Required' ? 'selected' : '' }}>Not Required</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pollution / Environment NOC
                                </label>
                                <select name="pollution_environment_noc"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Obtained" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Obtained' ? 'selected' : '' }}>
                                        Obtained</option>
                                    <option value="Applied" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Applied' ? 'selected' : '' }}>Applied
                                    </option>
                                    <option value="Not Applicable" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Not Applicable' ? 'selected' : '' }}>
                                        Not Applicable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Loan on Property
                                    <span class="text-red-500">*</span></label>
                                <select required name="encumbrance_loan_on_property"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="None" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'None' ? 'selected' : '' }}>None
                                    </option>
                                    <option value="Loan" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Loan' ? 'selected' : '' }}>Loan
                                    </option>
                                    <option value="Mortgage" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Mortgage' ? 'selected' : '' }}>
                                        Mortgage</option>
                                    <option value="Other" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION E — INFRASTRUCTURE</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Power Load Sanctioned (KW)
                                </label>
                                <input type="number" step="any" name="power_load_sanctioned_kw"
                                    value="{{ old('power_load_sanctioned_kw', $property?->fieldValue('power_load_sanctioned_kw') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup </label>
                                <select name="power_backup"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Provision" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Provision' ? 'selected' : '' }}>Provision</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Connection </label>
                                <select name="water_connection"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('water_connection', $property?->fieldValue('water_connection') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('water_connection', $property?->fieldValue('water_connection') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kitchen / Exhaust Provision
                                    (F&B) </label>
                                <select name="kitchen_exhaust_provision_f_b"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('kitchen_exhaust_provision_f_b', $property?->fieldValue('kitchen_exhaust_provision_f_b') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('kitchen_exhaust_provision_f_b', $property?->fieldValue('kitchen_exhaust_provision_f_b') ?? '') === 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Air Conditioning </label>
                                <select name="air_conditioning"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Central" {{ old('air_conditioning', $property?->fieldValue('air_conditioning') ?? '') === 'Central' ? 'selected' : '' }}>Central</option>
                                    <option value="Split" {{ old('air_conditioning', $property?->fieldValue('air_conditioning') ?? '') === 'Split' ? 'selected' : '' }}>Split</option>
                                    <option value="None" {{ old('air_conditioning', $property?->fieldValue('air_conditioning') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Escalator / Lift Access </label>
                                <select name="escalator_lift_access"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('escalator_lift_access', $property?->fieldValue('escalator_lift_access') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('escalator_lift_access', $property?->fieldValue('escalator_lift_access') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION G — COMMERCIAL TERMS</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Listing Purpose (Transaction
                                    Type) <span class="text-red-500">*</span></label>
                                <select required name="listing_purpose_transaction_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Lease" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Lease' ? 'selected' : '' }}>
                                        Lease</option>
                                    <option value="Sale" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Sale' ? 'selected' : '' }}>
                                        Sale</option>
                                    <option value="Both" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Both' ? 'selected' : '' }}>
                                        Both</option>
                                    <option value="Leave & License" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Leave & License' ? 'selected' : '' }}>Leave & License</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price on Request </label>
                                <select name="price_on_request"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('price_on_request', $property?->fieldValue('price_on_request') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('price_on_request', $property?->fieldValue('price_on_request') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Rent (₹/sq ft/month)
                                </label>
                                <input type="text" name="expected_rent_sq_ft_month"
                                    value="{{ old('expected_rent_sq_ft_month', $property?->fieldValue('expected_rent_sq_ft_month') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rent Range Band (shown live)
                                </label>
                                <select name="rent_range_band_shown_live"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('rent_range_band_shown_live', $property?->fieldValue('rent_range_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('rent_range_band_shown_live', $property?->fieldValue('rent_range_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹/sq ft)
                                </label>
                                <input type="text" name="expected_sale_price_sq_ft"
                                    value="{{ old('expected_sale_price_sq_ft', $property?->fieldValue('expected_sale_price_sq_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price Band (shown live)
                                </label>
                                <select name="sale_price_band_shown_live"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CAM Charges (₹/sq ft/month)
                                </label>
                                <input type="text" name="cam_charges_sq_ft_month"
                                    value="{{ old('cam_charges_sq_ft_month', $property?->fieldValue('cam_charges_sq_ft_month') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit </label>
                                <select name="security_deposit_months"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="3" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '3' ? 'selected' : '' }}>3</option>
                                    <option value="6" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '6' ? 'selected' : '' }}>6</option>
                                    <option value="12 months" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '12 months' ? 'selected' : '' }}>12 months</option>
                                    <option value="Negotiable" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Period (months) </label>
                                <input type="number" step="any" name="lock_in_period_months"
                                    value="{{ old('lock_in_period_months', $property?->fieldValue('lock_in_period_months') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Annual Escalation (%) </label>
                                <input type="text" name="annual_escalation"
                                    value="{{ old('annual_escalation', $property?->fieldValue('annual_escalation') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fit-out / Rent-free Period
                                    (months) </label>
                                <input type="number" step="any" name="fit_out_rent_free_period_months"
                                    value="{{ old('fit_out_rent_free_period_months', $property?->fieldValue('fit_out_rent_free_period_months') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price </label>
                                <input type="number" step="any" name="negotiable_floor_price"
                                    value="{{ old('negotiable_floor_price', $property?->fieldValue('negotiable_floor_price') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes </label>
                                <textarea name="owner_flexibility_notes" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property?->fieldValue('owner_flexibility_notes') ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION H — INVESTMENT / ROI (if property is
                                tenanted / pre-leased and being sold)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Currently Rented / Tenanted
                                    <span class="text-red-500">*</span></label>
                                <select required name="currently_rented_tenanted"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Partially" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'Partially' ? 'selected' : '' }}>
                                        Partially</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Monthly Rent Received
                                    (₹) </label>
                                <input type="number" step="any" name="current_monthly_rent_received"
                                    value="{{ old('current_monthly_rent_received', $property?->fieldValue('current_monthly_rent_received') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rental Income Band (shown live)
                                </label>
                                <select name="rental_income_band_shown_live"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('rental_income_band_shown_live', $property?->fieldValue('rental_income_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('rental_income_band_shown_live', $property?->fieldValue('rental_income_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rental Yield / ROI (% p.a.)
                                </label>
                                <input type="text" name="rental_yield_roi_p_a"
                                    value="{{ old('rental_yield_roi_p_a', $property?->fieldValue('rental_yield_roi_p_a') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Type (public) </label>
                                <select name="tenant_type_public"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="MNC" {{ old('tenant_type_public', $property?->fieldValue('tenant_type_public') ?? '') === 'MNC' ? 'selected' : '' }}>MNC</option>
                                    <option value="Corporate" {{ old('tenant_type_public', $property?->fieldValue('tenant_type_public') ?? '') === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                    <option value="Bank" {{ old('tenant_type_public', $property?->fieldValue('tenant_type_public') ?? '') === 'Bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="Brand Retail" {{ old('tenant_type_public', $property?->fieldValue('tenant_type_public') ?? '') === 'Brand Retail' ? 'selected' : '' }}>Brand Retail</option>
                                    <option value="Individual" {{ old('tenant_type_public', $property?->fieldValue('tenant_type_public') ?? '') === 'Individual' ? 'selected' : '' }}>Individual</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lease Tenure (years) </label>
                                <input type="number" step="any" name="lease_tenure_years"
                                    value="{{ old('lease_tenure_years', $property?->fieldValue('lease_tenure_years') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Remaining (months)
                                </label>
                                <input type="number" step="any" name="lock_in_remaining_months"
                                    value="{{ old('lock_in_remaining_months', $property?->fieldValue('lock_in_remaining_months') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CAM / Outgoings Borne By
                                </label>
                                <select name="cam_outgoings_borne_by"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Tenant" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Tenant' ? 'selected' : '' }}>Tenant
                                    </option>
                                    <option value="Owner" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                    <option value="Shared" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Shared' ? 'selected' : '' }}>Shared
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION J — PHOTOS & MEDIA</h4>
                        </div>
                        @php
                            $slots = $slots ?? [
                                'Photo 1 — Exterior / Front View',
                                'Photo 2 — Main Area / Interior',
                                'Photo 3 — Secondary View / Room',
                                'Photo 4 — Kitchen / Utility / Amenities',
                                'Photo 5 — Washroom / Restroom',
                                'Photo 6 — Balcony / Terrace / Open Area',
                                'Photo 7 — Floor Plan / Layout Map',
                                'Photo 8 — Additional Photos / Facilities',
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            @foreach($slots as $idx => $label)
                                <x-property-photo-card :idx="$idx" :label="$label" :property="$property ?? null" />
                            @endforeach
                        </div>
                        <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION K — TEAM REMARKS</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer / Submitter
                                    Remarks <span class="text-red-500">*</span></label>
                                <textarea required name="field_officer_submitter_remarks" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('field_officer_submitter_remarks', $property?->fieldValue('field_officer_submitter_remarks') ?? '') }}</textarea>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Description (public)
                                </label>
                                <textarea name="property_description_public" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('property_description_public', $property?->fieldValue('property_description_public') ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Inspection / Submission Date
                                    <span class="text-red-500">*</span></label>
                                <input required type="date" name="inspection_submission_date"
                                    value="{{ old('inspection_submission_date', ($property?->fieldValue('inspection_submission_date') !== null) ? $property?->fieldValue('inspection_submission_date')->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </x-property-wizard-shell>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function stepWizard() {
            return {};
        }

        window.wizCurrent = 0;
        window.WIZ_TOTAL = 6;

        function initSelect2() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2-multiple').each(function () {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            placeholder: "— Select option(s) —",
                            allowClear: true,
                            width: '100%'
                        });
                    }
                });
            }
        }

        window.wizardGoTo = function (s) {
            if (s < 0 || s >= window.WIZ_TOTAL) return;
            if (s > (window.wizCurrent || 0)) {
                if (typeof window.wizardValidateStep === 'function' && !window.wizardValidateStep(window.wizCurrent || 0)) {
                    const lockMsg = document.getElementById('wiz-lock-msg');
                    if (lockMsg) {
                        lockMsg.classList.remove('hidden');
                        setTimeout(() => lockMsg.classList.add('hidden'), 4000);
                    }
                    return false;
                }
            }
            window.wizCurrent = s; const stepInput = document.getElementById('wizard_step_input'); if (stepInput) stepInput.value = s;

            document.querySelectorAll('.wizard-step-content').forEach((el, idx) => {
                el.style.display = (idx === s) ? 'block' : 'none';
            });

            document.querySelectorAll('.wiz-dot').forEach((dot, idx) => {
                dot.classList.remove('bg-zendo-navy', 'text-white', 'border-zendo-navy', 'bg-green-500', 'bg-gray-100', 'text-gray-400');
                if (idx === s) {
                    dot.classList.add('bg-zendo-navy', 'text-white', 'border-zendo-navy');
                } else if (idx < s) {
                    dot.classList.add('bg-green-500', 'text-white', 'border-green-500');
                } else {
                    dot.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
                }
            });

            document.querySelectorAll('.wiz-line').forEach((line, idx) => {
                line.classList.remove('bg-green-400', 'bg-gray-200');
                line.classList.add(idx < s ? 'bg-green-400' : 'bg-gray-200');
            });

            setTimeout(initSelect2, 50);

            const prevBtn = document.getElementById('wiz-prev-btn');
            if (prevBtn) {
                prevBtn.disabled = (s === 0);
                prevBtn.classList.toggle('opacity-40', s === 0);
                prevBtn.classList.toggle('cursor-not-allowed', s === 0);
            }

            const nextBtn = document.getElementById('wiz-next-btn');
            const submitBtn = document.getElementById('wiz-submit-btn');
            if (nextBtn && submitBtn) {
                if (s === window.WIZ_TOTAL - 1) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-flex';
                } else {
                    nextBtn.style.display = 'inline-flex';
                    submitBtn.style.display = 'none';
                }
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        window.wizardNext = function () { if (typeof window.wizardValidateStep === 'function' && !window.wizardValidateStep(window.wizCurrent)) return; window.wizardGoTo(window.wizCurrent + 1); };
        window.wizardPrev = function () { window.wizardGoTo(window.wizCurrent - 1); };

        $(document).ready(function () {
            initSelect2();
            const sessionStep = parseInt("{{ session('wizard_step', -1) }}", 10);
            const initialStep = (sessionStep >= 0) ? sessionStep : 0;
            if (typeof window.wizFrontier !== 'undefined') {
                window.wizFrontier = Math.max(window.wizFrontier || 0, initialStep);
            }
            window.wizardGoTo(initialStep);
        });
    </script>
@endsection