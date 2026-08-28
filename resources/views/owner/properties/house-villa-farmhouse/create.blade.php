@extends('layouts.owner')
@php if (!isset($property)) { $property = null; } @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' House / Villa / Farmhouse - Owner Portal')

@section('content')
    @php
        // One entry per real section in this type's Excel spec sheet — see
        // config('property_entry_sections.house_villa_farmhouse'). Letters skip
        // (no F, H or I here) because the spec sheet itself skips them; each tab
        // must correspond to exactly one wizard-step-content panel below, in the
        // same order, since they're matched by position, not by key.
        $steps = [
            ['key' => 'A', 'title' => 'Submitter & Owner Details'],
            ['key' => 'B', 'title' => 'Location & Identification'],
            ['key' => 'B2', 'title' => 'Project / Society'],
            ['key' => 'C', 'title' => 'Property Configuration'],
            ['key' => 'C2', 'title' => 'Transaction & Possession'],
            ['key' => 'D', 'title' => 'Legal & Compliance'],
            ['key' => 'E', 'title' => 'Utilities & Features'],
            ['key' => 'G', 'title' => 'Commercial Terms'],
            ['key' => 'G2', 'title' => 'Tenant Preferences & Rental Terms'],
            ['key' => 'J', 'title' => 'Photos & Media'],
            ['key' => 'K', 'title' => 'Team Remarks'],
        ];
    @endphp

    <div class="max-w-5xl mx-auto space-y-6" x-data="stepWizard()">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">
                    {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List House / Villa / Farmhouse' }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for House / Villa /
                    Farmhouse</p>
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
            action="{{ isset($property) ? route('owner.properties.house-villa-farmhouse.update', $property) : route('owner.properties.house-villa-farmhouse.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif

            <x-property-wizard-shell :steps="$steps" :title="'House / Villa / Farmhouse Submission Form'"
                :propertyType="'house_villa_farmhouse'">

                <div class="wizard-step-content space-y-6" style="display:block">

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
                                <input required type="tel" maxlength="10" inputmode="numeric" pattern="[6-9][0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="submitter_phone"
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
                                <input required type="text" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" name="pin_code"
                                    value="{{ old('pin_code', $property?->fieldValue('pin_code') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="city"
                                    value="{{ old('city', $property?->fieldValue('city') ?? $property?->city ?? $property?->nearest_city ?? '') }}"
                                    placeholder="e.g. Mumbai, Gurgaon, Delhi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Locality / Broad Area <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="locality_broad_area"
                                    value="{{ old('locality_broad_area', $property?->fieldValue('locality_broad_area') ?? '') }}"
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
                                <input type="number" step="0.000001" name="gps_latitude"
                                    value="{{ old('gps_latitude', $property?->fieldValue('gps_latitude') ?? '') }}"
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
                                    value="{{ old('gps_longitude', $property?->fieldValue('gps_longitude') ?? '') }}"
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
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
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
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C — PROPERTY CONFIGURATION</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span
                                        class="text-red-500">*</span></label>
                                <select required name="property_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Independent House" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Independent House' ? 'selected' : '' }}>Independent House</option>
                                    <option value="Villa" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="Bungalow" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Bungalow' ? 'selected' : '' }}>Bungalow</option>
                                    <option value="Farmhouse" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Farmhouse' ? 'selected' : '' }}>Farmhouse</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Configuration (BHK) <span
                                        class="text-red-500">*</span></label>
                                <select required name="configuration_bhk"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="1" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '5' ? 'selected' : '' }}>5</option>
                                    <option value="5+ BHK" {{ old('configuration_bhk', $property?->fieldValue('configuration_bhk') ?? '') === '5+ BHK' ? 'selected' : '' }}>5+ BHK</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plot Area (sq ft / sq yd) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="plot_area_sq_ft_sq_yd"
                                    value="{{ old('plot_area_sq_ft_sq_yd', $property?->fieldValue('plot_area_sq_ft_sq_yd') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Constructed / Built-up Area (sq
                                    ft) <span class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="constructed_built_up_area_sq_ft"
                                    value="{{ old('constructed_built_up_area_sq_ft', $property?->fieldValue('constructed_built_up_area_sq_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carpet Area (sq ft) </label>
                                <input type="number" step="any" name="carpet_area_sq_ft"
                                    value="{{ old('carpet_area_sq_ft', $property?->fieldValue('carpet_area_sq_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Floors <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="no_of_floors"
                                    value="{{ old('no_of_floors', $property?->fieldValue('no_of_floors') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Bedrooms <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="no_of_bedrooms"
                                    value="{{ old('no_of_bedrooms', $property?->fieldValue('no_of_bedrooms') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Bathrooms <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="no_of_bathrooms"
                                    value="{{ old('no_of_bathrooms', $property?->fieldValue('no_of_bathrooms') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Rooms </label>
                                <select name="additional_rooms[]" multiple class="select2-multiple w-full">
                                    @php $sel = old('additional_rooms', $property?->fieldValue('additional_rooms') ?? []); @endphp
                                    <option value="Pooja" {{ in_array('Pooja', (array) $sel) ? 'selected' : '' }}>Pooja
                                    </option>
                                    <option value="Study" {{ in_array('Study', (array) $sel) ? 'selected' : '' }}>Study
                                    </option>
                                    <option value="Servant" {{ in_array('Servant', (array) $sel) ? 'selected' : '' }}>Servant
                                    </option>
                                    <option value="Store" {{ in_array('Store', (array) $sel) ? 'selected' : '' }}>Store
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Independent Entrance </label>
                                <select name="independent_entrance"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('independent_entrance', $property?->fieldValue('independent_entrance') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('independent_entrance', $property?->fieldValue('independent_entrance') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="furnishing_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Unfurnished" {{ old('furnishing_status', $property?->fieldValue('furnishing_status') ?? '') === 'Unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                    <option value="Semi" {{ old('furnishing_status', $property?->fieldValue('furnishing_status') ?? '') === 'Semi' ? 'selected' : '' }}>Semi</option>
                                    <option value="Fully" {{ old('furnishing_status', $property?->fieldValue('furnishing_status') ?? '') === 'Fully' ? 'selected' : '' }}>Fully</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Car Parking Capacity </label>
                                <input type="number" step="any" name="car_parking_capacity"
                                    value="{{ old('car_parking_capacity', $property?->fieldValue('car_parking_capacity') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Garden / Open Space </label>
                                <select name="garden_open_space"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('garden_open_space', $property?->fieldValue('garden_open_space') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('garden_open_space', $property?->fieldValue('garden_open_space') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Terrace Rights </label>
                                <select name="terrace_rights"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Exclusive" {{ old('terrace_rights', $property?->fieldValue('terrace_rights') ?? '') === 'Exclusive' ? 'selected' : '' }}>Exclusive</option>
                                    <option value="Shared" {{ old('terrace_rights', $property?->fieldValue('terrace_rights') ?? '') === 'Shared' ? 'selected' : '' }}>Shared</option>
                                    <option value="None" {{ old('terrace_rights', $property?->fieldValue('terrace_rights') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Extension / Vertical Expansion
                                    Possible </label>
                                <select name="extension_vertical_expansion_possible"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('extension_vertical_expansion_possible', $property?->fieldValue('extension_vertical_expansion_possible') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('extension_vertical_expansion_possible', $property?->fieldValue('extension_vertical_expansion_possible') ?? '') === 'No' ? 'selected' : '' }}>
                                        No</option>
                                    <option value="With permission" {{ old('extension_vertical_expansion_possible', $property?->fieldValue('extension_vertical_expansion_possible') ?? '') === 'With permission' ? 'selected' : '' }}>With permission</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C2 — TRANSACTION & POSSESSION</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="property_status"
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
                                    value="{{ old('possession_by_if_under_constr', $property?->dateFieldValue('possession_by_if_under_constr') ?: '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age of Property </label>
                                <select name="age_of_property"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="<1" {{ old('age_of_property', $property?->fieldValue('age_of_property') ?? '') === '<1' ? 'selected' : '' }}>
                                        <1< /option>
                                    <option value="1-5" {{ old('age_of_property', $property?->fieldValue('age_of_property') ?? '') === '1-5' ? 'selected' : '' }}>1-5</option>
                                    <option value="5-10" {{ old('age_of_property', $property?->fieldValue('age_of_property') ?? '') === '5-10' ? 'selected' : '' }}>5-10</option>
                                    <option value="10+ yrs" {{ old('age_of_property', $property?->fieldValue('age_of_property') ?? '') === '10+ yrs' ? 'selected' : '' }}>10+ yrs</option>
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
                                    value="{{ old('available_from_date', $property?->dateFieldValue('available_from_date') ?: '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Loan / EMI Available
                                </label>
                                <select name="bank_loan_emi_available"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes — approved banks" {{ old('bank_loan_emi_available', $property?->fieldValue('bank_loan_emi_available') ?? '') === 'Yes — approved banks' ? 'selected' : '' }}>Yes — approved banks</option>
                                    <option value="No" {{ old('bank_loan_emi_available', $property?->fieldValue('bank_loan_emi_available') ?? '') === 'No' ? 'selected' : '' }}>No</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ownership Type <span
                                        class="text-red-500">*</span></label>
                                <select required name="ownership_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Freehold" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                    <option value="Leasehold" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Leasehold' ? 'selected' : '' }}>Leasehold</option>
                                    <option value="GPA" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'GPA' ? 'selected' : '' }}>GPA</option>
                                    <option value="Co-op Society" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Co-op Society' ? 'selected' : '' }}>Co-op Society</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Loan on Property
                                    <span class="text-red-500">*</span></label>
                                <select required name="encumbrance_loan_on_property"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="None" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'None' ? 'selected' : '' }}>None
                                    </option>
                                    <option value="Home Loan" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Home Loan' ? 'selected' : '' }}>
                                        Home Loan</option>
                                    <option value="Mortgage" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Mortgage' ? 'selected' : '' }}>
                                        Mortgage</option>
                                    <option value="Other" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION E — UTILITIES & FEATURES</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Source </label>
                                <select name="water_source"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Municipal" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                                    <option value="Borewell" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Borewell' ? 'selected' : '' }}>Borewell</option>
                                    <option value="Both" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Both' ? 'selected' : '' }}>Both</option>
                                    <option value="Tanker" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Tanker' ? 'selected' : '' }}>Tanker</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup </label>
                                <select name="power_backup"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Full" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Full' ? 'selected' : '' }}>Full</option>
                                    <option value="Partial" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="None" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                    <option value="Inverter" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Inverter' ? 'selected' : '' }}>Inverter</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sanctioned Power Load (KW)
                                </label>
                                <input type="number" step="any" name="sanctioned_power_load_kw"
                                    value="{{ old('sanctioned_power_load_kw', $property?->fieldValue('sanctioned_power_load_kw') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sewage / Drainage </label>
                                <select name="sewage_drainage"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Municipal" {{ old('sewage_drainage', $property?->fieldValue('sewage_drainage') ?? '') === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                                    <option value="Septic" {{ old('sewage_drainage', $property?->fieldValue('sewage_drainage') ?? '') === 'Septic' ? 'selected' : '' }}>Septic</option>
                                    <option value="STP" {{ old('sewage_drainage', $property?->fieldValue('sewage_drainage') ?? '') === 'STP' ? 'selected' : '' }}>STP</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Solar Provision </label>
                                <select name="solar_provision"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('solar_provision', $property?->fieldValue('solar_provision') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('solar_provision', $property?->fieldValue('solar_provision') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security Arrangement </label>
                                <select name="security_arrangement"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Gated colony" {{ old('security_arrangement', $property?->fieldValue('security_arrangement') ?? '') === 'Gated colony' ? 'selected' : '' }}>Gated
                                        colony</option>
                                    <option value="Standalone" {{ old('security_arrangement', $property?->fieldValue('security_arrangement') ?? '') === 'Standalone' ? 'selected' : '' }}>
                                        Standalone</option>
                                    <option value="Guard" {{ old('security_arrangement', $property?->fieldValue('security_arrangement') ?? '') === 'Guard' ? 'selected' : '' }}>Guard</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amenities Checklist </label>
                                <select name="amenities_checklist[]" multiple class="select2-multiple w-full">
                                    @php $sel = old('amenities_checklist', $property?->fieldValue('amenities_checklist') ?? []); @endphp
                                    <option value="Yes" {{ in_array('Yes', (array) $sel) ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ in_array('No', (array) $sel) ? 'selected' : '' }}>No</option>
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
                                    <option value="Rent" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Rent' ? 'selected' : '' }}>
                                        Rent</option>
                                    <option value="Sale" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Sale' ? 'selected' : '' }}>
                                        Sale</option>
                                    <option value="Both" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Both' ? 'selected' : '' }}>
                                        Both</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Rent (₹/month) </label>
                                <input type="number" step="any" name="expected_rent_month"
                                    value="{{ old('expected_rent_month', $property?->fieldValue('expected_rent_month') ?? '') }}"
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Charge (₹/month)
                                </label>
                                <input type="number" step="any" name="maintenance_charge_month"
                                    value="{{ old('maintenance_charge_month', $property?->fieldValue('maintenance_charge_month') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit </label>
                                <select name="security_deposit_months"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="1" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '3' ? 'selected' : '' }}>3</option>
                                    <option value="6 months" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '6 months' ? 'selected' : '' }}>6 months</option>
                                    <option value="Negotiable" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹) </label>
                                <input type="number" step="any" name="expected_sale_price"
                                    value="{{ old('expected_sale_price', $property?->fieldValue('expected_sale_price') ?? '') }}"
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
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION G2 — TENANT PREFERENCES & RENTAL TERMS
                                (if listed for rent)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Tenant </label>
                                <select name="preferred_tenant[]" multiple class="select2-multiple w-full">
                                    @php $sel = old('preferred_tenant', $property?->fieldValue('preferred_tenant') ?? []); @endphp
                                    <option value="Family" {{ in_array('Family', (array) $sel) ? 'selected' : '' }}>Family
                                    </option>
                                    <option value="Bachelor Male" {{ in_array('Bachelor Male', (array) $sel) ? 'selected' : '' }}>Bachelor Male</option>
                                    <option value="Bachelor Female" {{ in_array('Bachelor Female', (array) $sel) ? 'selected' : '' }}>Bachelor Female</option>
                                    <option value="Company" {{ in_array('Company', (array) $sel) ? 'selected' : '' }}>Company
                                    </option>
                                    <option value="Any" {{ in_array('Any', (array) $sel) ? 'selected' : '' }}>Any</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Non-Veg / Pets Allowed </label>
                                <select name="non_veg_pets_allowed"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Both" {{ old('non_veg_pets_allowed', $property?->fieldValue('non_veg_pets_allowed') ?? '') === 'Both' ? 'selected' : '' }}>Both</option>
                                    <option value="Non-veg only" {{ old('non_veg_pets_allowed', $property?->fieldValue('non_veg_pets_allowed') ?? '') === 'Non-veg only' ? 'selected' : '' }}>Non-veg
                                        only</option>
                                    <option value="Pets only" {{ old('non_veg_pets_allowed', $property?->fieldValue('non_veg_pets_allowed') ?? '') === 'Pets only' ? 'selected' : '' }}>Pets only</option>
                                    <option value="Neither" {{ old('non_veg_pets_allowed', $property?->fieldValue('non_veg_pets_allowed') ?? '') === 'Neither' ? 'selected' : '' }}>Neither</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notice Period (months) </label>
                                <input type="number" step="any" name="notice_period_months"
                                    value="{{ old('notice_period_months', $property?->fieldValue('notice_period_months') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Lease / Agreement Term
                                    (months) </label>
                                <input type="number" step="any" name="minimum_lease_agreement_term_months"
                                    value="{{ old('minimum_lease_agreement_term_months', $property?->fieldValue('minimum_lease_agreement_term_months') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Utilities — Who Bears </label>
                                <select name="utilities_who_bears"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Included in rent" {{ old('utilities_who_bears', $property?->fieldValue('utilities_who_bears') ?? '') === 'Included in rent' ? 'selected' : '' }}>
                                        Included in rent</option>
                                    <option value="Extra as per usage" {{ old('utilities_who_bears', $property?->fieldValue('utilities_who_bears') ?? '') === 'Extra as per usage' ? 'selected' : '' }}>
                                        Extra as per usage</option>
                                    <option value="Mixed" {{ old('utilities_who_bears', $property?->fieldValue('utilities_who_bears') ?? '') === 'Mixed' ? 'selected' : '' }}>Mixed</option>
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
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
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
                                    value="{{ old('inspection_submission_date', $property?->dateFieldValue('inspection_submission_date') ?: '') }}"
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
        window.WIZ_TOTAL = 11;

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