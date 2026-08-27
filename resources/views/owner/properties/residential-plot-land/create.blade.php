@extends('layouts.owner')
@php if (!isset($property)) {
    $property = null;
} @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Residential Plot / Land - Owner Portal')

@section('content')
    @php
        // One entry per real section in this type's Excel spec sheet — see
        // config('property_entry_sections.residential_plot_land'). Letters skip
        // (no F, H or I here) because the spec sheet itself skips them; each tab
        // must correspond to exactly one wizard-step-content panel below, in the
        // same order, since they're matched by position, not by key.
        $steps = [
            ['key' => 'A', 'title' => 'Submitter & Owner Details'],
            ['key' => 'B', 'title' => 'Location & Identification'],
            ['key' => 'C', 'title' => 'Plot Specifications'],
            ['key' => 'D', 'title' => 'Legal & Compliance'],
            ['key' => 'E', 'title' => 'Infrastructure Access'],
            ['key' => 'G', 'title' => 'Commercial Terms'],
            ['key' => 'J', 'title' => 'Photos & Media'],
            ['key' => 'K', 'title' => 'Team Remarks'],
        ];
    @endphp

    <div class="max-w-5xl mx-auto space-y-6" x-data="stepWizard()">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">
                    {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Residential Plot / Land' }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Residential Plot /
                    Land</p>
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
            action="{{ isset($property) ? route('owner.properties.residential-plot-land.update', $property) : route('owner.properties.residential-plot-land.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif

            <x-property-wizard-shell :steps="$steps" :title="'Residential Plot / Land Submission Form'"
                :propertyType="'residential_plot_land'">

                <div class="wizard-step-content space-y-6" style="display:block">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS </h4>
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
                                    <option value="Builder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Builder' ? 'selected' : '' }}>
                                        Builder</option>
                                    <option value="Authorised Agent" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Authorised Agent' ? 'selected' : '' }}>Authorised Agent</option>
                                    <option value="Broker" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Broker' ? 'selected' : '' }}>Broker</option>
                                    <option value="GPA holder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'GPA holder' ? 'selected' : '' }}>
                                        GPA holder</option>
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
                                <input required type="text" name="state"
                                    value="{{ old('state', $property?->fieldValue('state') ?? '') }}" required
                                    placeholder="State" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GPS Latitude <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.000001" name="gps_latitude"
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
                                    value="{{ old('gps_longitude', $property?->fieldValue('gps_longitude') ?? '') }}"
                                    required placeholder="e.g. 77.026634"
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
                                    <option value="N" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'N' ? 'selected' : '' }}>North
                                    </option>
                                    <option value="E" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'E' ? 'selected' : '' }}>East
                                    </option>
                                    <option value="W" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'W' ? 'selected' : '' }}>West
                                    </option>
                                    <option value="S" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'S' ? 'selected' : '' }}>South
                                    </option>
                                    <option value="NE" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'NE' ? 'selected' : '' }}>
                                        North-East</option>
                                    <option value="NW" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'NW' ? 'selected' : '' }}>
                                        North-West</option>
                                    <option value="SE" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'SE' ? 'selected' : '' }}>
                                        South-East</option>
                                    <option value="SW" {{ old('facing_orientation', $property?->fieldValue('facing_orientation') ?? '') === 'SW' ? 'selected' : '' }}>
                                        South-West</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">

                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C — PLOT SPECIFICATIONS</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span
                                        class="text-red-500">*</span></label>
                                <select required name="property_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Residential Plot" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Residential Plot' ? 'selected' : '' }}>Residential Plot</option>
                                    <option value="Gated Plot" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Gated Plot' ? 'selected' : '' }}>
                                        Gated Plot</option>
                                    <option value="Agricultural (resi-convertible)" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Agricultural (resi-convertible)' ? 'selected' : '' }}>Agricultural (resi-convertible)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plot Area <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="any" name="plot_area"
                                    value="{{ old('plot_area', $property?->fieldValue('plot_area') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plot Dimensions (ft × ft)
                                </label>
                                <input type="text" name="plot_dimensions_ft_ft"
                                    value="{{ old('plot_dimensions_ft_ft', $property?->fieldValue('plot_dimensions_ft_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plot Shape </label>
                                <select name="plot_shape"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Rectangular" {{ old('plot_shape', $property?->fieldValue('plot_shape') ?? '') === 'Rectangular' ? 'selected' : '' }}>Rectangular</option>
                                    <option value="Square" {{ old('plot_shape', $property?->fieldValue('plot_shape') ?? '') === 'Square' ? 'selected' : '' }}>Square</option>
                                    <option value="Irregular" {{ old('plot_shape', $property?->fieldValue('plot_shape') ?? '') === 'Irregular' ? 'selected' : '' }}>Irregular</option>
                                    <option value="Corner" {{ old('plot_shape', $property?->fieldValue('plot_shape') ?? '') === 'Corner' ? 'selected' : '' }}>Corner</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Open Sides </label>
                                <select name="no_of_open_sides"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="1" {{ old('no_of_open_sides', $property?->fieldValue('no_of_open_sides') ?? '') === '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('no_of_open_sides', $property?->fieldValue('no_of_open_sides') ?? '') === '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('no_of_open_sides', $property?->fieldValue('no_of_open_sides') ?? '') === '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('no_of_open_sides', $property?->fieldValue('no_of_open_sides') ?? '') === '4' ? 'selected' : '' }}>4</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Corner Plot </label>
                                <select name="corner_plot"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('corner_plot', $property?->fieldValue('corner_plot') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('corner_plot', $property?->fieldValue('corner_plot') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Wall </label>
                                <select name="boundary_wall"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('boundary_wall', $property?->fieldValue('boundary_wall') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('boundary_wall', $property?->fieldValue('boundary_wall') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Partial" {{ old('boundary_wall', $property?->fieldValue('boundary_wall') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gated Community Plot </label>
                                <select name="gated_community_plot"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('gated_community_plot', $property?->fieldValue('gated_community_plot') ?? '') === 'Yes' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="No" {{ old('gated_community_plot', $property?->fieldValue('gated_community_plot') ?? '') === 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved Layout
                                    (DTCP/RERA/Local) <span class="text-red-500">*</span></label>
                                <select required name="approved_layout_dtcp_rera_local"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('approved_layout_dtcp_rera_local', $property?->fieldValue('approved_layout_dtcp_rera_local') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('approved_layout_dtcp_rera_local', $property?->fieldValue('approved_layout_dtcp_rera_local') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Construction Permitted (floors)
                                </label>
                                <input type="text" name="construction_permitted_floors"
                                    value="{{ old('construction_permitted_floors', $property?->fieldValue('construction_permitted_floors') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">FAR / FSI Permitted </label>
                                <input type="text" name="far_fsi_permitted"
                                    value="{{ old('far_fsi_permitted', $property?->fieldValue('far_fsi_permitted') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Facing / Road Position </label>
                                <select name="facing_road_position"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="facing options" {{ old('facing_road_position', $property?->fieldValue('facing_road_position') ?? '') === 'facing options' ? 'selected' : '' }}>facing options</option>
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
                                    <option value="Freehold" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Freehold' ? 'selected' : '' }}>
                                        Freehold</option>
                                    <option value="Leasehold" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Leasehold' ? 'selected' : '' }}>
                                        Leasehold</option>
                                    <option value="GPA" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'GPA' ? 'selected' : '' }}>GPA</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="title_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Clear" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                    <option value="Dispute" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Dispute' ? 'selected' : '' }}>Dispute</option>
                                    <option value="Encumbrance" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Encumbrance' ? 'selected' : '' }}>
                                        Encumbrance</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Land Use / Zoning <span
                                        class="text-red-500">*</span></label>
                                <select required name="land_use_zoning"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Residential" {{ old('land_use_zoning', $property?->fieldValue('land_use_zoning') ?? '') === 'Residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="Agricultural" {{ old('land_use_zoning', $property?->fieldValue('land_use_zoning') ?? '') === 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                                    <option value="Mixed" {{ old('land_use_zoning', $property?->fieldValue('land_use_zoning') ?? '') === 'Mixed' ? 'selected' : '' }}>
                                        Mixed</option>
                                    <option value="Conversion Applied" {{ old('land_use_zoning', $property?->fieldValue('land_use_zoning') ?? '') === 'Conversion Applied' ? 'selected' : '' }}>Conversion Applied</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CLU / Conversion Status <span
                                        class="text-red-500">*</span></label>
                                <select required name="clu_conversion_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Converted" {{ old('clu_conversion_status', $property?->fieldValue('clu_conversion_status') ?? '') === 'Converted' ? 'selected' : '' }}>Converted</option>
                                    <option value="Applied" {{ old('clu_conversion_status', $property?->fieldValue('clu_conversion_status') ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                    <option value="Not Converted" {{ old('clu_conversion_status', $property?->fieldValue('clu_conversion_status') ?? '') === 'Not Converted' ? 'selected' : '' }}>Not Converted</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registered </label>
                                <select name="rera_registered"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="NA" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'NA' ? 'selected' : '' }}>NA</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Khasra / Survey Number </label>
                                <input type="text" name="khasra_survey_number"
                                    value="{{ old('khasra_survey_number', $property?->fieldValue('khasra_survey_number') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance Certificate </label>
                                <select name="encumbrance_certificate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Available" {{ old('encumbrance_certificate', $property?->fieldValue('encumbrance_certificate') ?? '') === 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Pending" {{ old('encumbrance_certificate', $property?->fieldValue('encumbrance_certificate') ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Litigation / Dispute <span
                                        class="text-red-500">*</span></label>
                                <select required name="litigation_dispute"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="None" {{ old('litigation_dispute', $property?->fieldValue('litigation_dispute') ?? '') === 'None' ? 'selected' : '' }}>
                                        None</option>
                                    <option value="Ongoing" {{ old('litigation_dispute', $property?->fieldValue('litigation_dispute') ?? '') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div class="border-t pt-4 first:border-t-0 first:pt-0">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION E — INFRASTRUCTURE ACCESS</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Road Access to Plot <span
                                        class="text-red-500">*</span></label>
                                <select required name="road_access_to_plot"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes-paved" {{ old('road_access_to_plot', $property?->fieldValue('road_access_to_plot') ?? '') === 'Yes-paved' ? 'selected' : '' }}>Yes-paved</option>
                                    <option value="Kutcha" {{ old('road_access_to_plot', $property?->fieldValue('road_access_to_plot') ?? '') === 'Kutcha' ? 'selected' : '' }}>Kutcha</option>
                                    <option value="None" {{ old('road_access_to_plot', $property?->fieldValue('road_access_to_plot') ?? '') === 'None' ? 'selected' : '' }}>
                                        None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approach Road Width (ft)
                                </label>
                                <input type="number" step="any" name="approach_road_width_ft"
                                    value="{{ old('approach_road_width_ft', $property?->fieldValue('approach_road_width_ft') ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Connection Available
                                </label>
                                <select name="water_connection_available"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('water_connection_available', $property?->fieldValue('water_connection_available') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('water_connection_available', $property?->fieldValue('water_connection_available') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Borewell feasible" {{ old('water_connection_available', $property?->fieldValue('water_connection_available') ?? '') === 'Borewell feasible' ? 'selected' : '' }}>Borewell feasible</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Electricity at Boundary </label>
                                <select name="electricity_at_boundary"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('electricity_at_boundary', $property?->fieldValue('electricity_at_boundary') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('electricity_at_boundary', $property?->fieldValue('electricity_at_boundary') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sewage / Drainage Line </label>
                                <select name="sewage_drainage_line"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Available" {{ old('sewage_drainage_line', $property?->fieldValue('sewage_drainage_line') ?? '') === 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Not available" {{ old('sewage_drainage_line', $property?->fieldValue('sewage_drainage_line') ?? '') === 'Not available' ? 'selected' : '' }}>Not available</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Flood / Water-logging Risk
                                </label>
                                <select name="flood_water_logging_risk"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="None" {{ old('flood_water_logging_risk', $property?->fieldValue('flood_water_logging_risk') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                    <option value="Low" {{ old('flood_water_logging_risk', $property?->fieldValue('flood_water_logging_risk') ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Moderate" {{ old('flood_water_logging_risk', $property?->fieldValue('flood_water_logging_risk') ?? '') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="High" {{ old('flood_water_logging_risk', $property?->fieldValue('flood_water_logging_risk') ?? '') === 'High' ? 'selected' : '' }}>High</option>
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
                                    <option value="Sale" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="Lease" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Lease' ? 'selected' : '' }}>Lease</option>
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
                                    <option value="Yes" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price per sq ft / sq yd (₹)
                                </label>
                                <input type="number" step="any" name="price_per_sq_ft_sq_yd"
                                    value="{{ old('price_per_sq_ft_sq_yd', $property?->fieldValue('price_per_sq_ft_sq_yd') ?? '') }}"
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
        window.WIZ_TOTAL = 8;

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