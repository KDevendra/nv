@extends('layouts.owner')
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Commercial / Institutional Land - Owner Portal')

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
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Commercial / Institutional Land' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Commercial / Institutional Land</p>
        </div>
        <a href="{{ route('owner.properties.select-type') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Choose Other Type
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm">
            <p class="font-bold mb-1">Please fix validation errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($property) ? route('owner.properties.commercial-institutional-land.update', $property) : route('owner.properties.commercial-institutional-land.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'Commercial / Institutional Land Submission Form'" :propertyType="'commercial_institutional_land'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS  (stored internally · never published)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="submitter_full_name" value="{{ old('submitter_full_name', $property->submitter_full_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Phone <span class="text-red-500">*</span></label>
                            <input type="text" name="submitter_phone" value="{{ old('submitter_phone', $property->submitter_phone ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Email <span class="text-red-500">*</span></label>
                            <input type="text" name="submitter_email" value="{{ old('submitter_email', $property->submitter_email ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Role <span class="text-red-500">*</span></label>
                            <select name="submitter_role" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Owner" {{ old('submitter_role', $property->submitter_role ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                <option value="Builder" {{ old('submitter_role', $property->submitter_role ?? '') === 'Builder' ? 'selected' : '' }}>Builder</option>
                                <option value="Authorised Agent" {{ old('submitter_role', $property->submitter_role ?? '') === 'Authorised Agent' ? 'selected' : '' }}>Authorised Agent</option>
                                <option value="Broker" {{ old('submitter_role', $property->submitter_role ?? '') === 'Broker' ? 'selected' : '' }}>Broker</option>
                                <option value="GPA holder" {{ old('submitter_role', $property->submitter_role ?? '') === 'GPA holder' ? 'selected' : '' }}>GPA holder</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="owner_full_name" value="{{ old('owner_full_name', $property->owner_full_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Contact Number <span class="text-red-500">*</span></label>
                            <input type="text" name="owner_contact_number" value="{{ old('owner_contact_number', $property->owner_contact_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pin Code <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="pin_code" value="{{ old('pin_code', $property->pin_code ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                            <select name="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="active cities + Other" {{ old('city', $property->city ?? '') === 'active cities + Other' ? 'selected' : '' }}>active cities + Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Locality / Broad Area <span class="text-red-500">*</span></label>
                            <input type="text" name="locality_broad_area" value="{{ old('locality_broad_area', $property->locality_broad_area ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Name </label>
                            <input type="text" name="project_name" value="{{ old('project_name', $property->project_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Builder / Developer Name </label>
                            <input type="text" name="builder_developer_name" value="{{ old('builder_developer_name', $property->builder_developer_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Address (house/plot no., street) <span class="text-red-500">*</span></label>
                            <input type="text" name="full_address_house_plot_no_street" value="{{ old('full_address_house_plot_no_street', $property->full_address_house_plot_no_street ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                            <select name="state" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Indian states" {{ old('state', $property->state ?? '') === 'Indian states' ? 'selected' : '' }}>Indian states</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GPS Coordinates (Lat, Long) <span class="text-red-500">*</span></label>
                            <input type="text" name="gps_coordinates_lat_long" value="{{ old('gps_coordinates_lat_long', $property->gps_coordinates_lat_long ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nearby Landmarks / Key Distances </label>
                            <input type="text" name="nearby_landmarks_key_distances" value="{{ old('nearby_landmarks_key_distances', $property->nearby_landmarks_key_distances ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facing / Orientation </label>
                            <select name="facing_orientation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="N" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'N' ? 'selected' : '' }}>North</option>
                                <option value="E" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'E' ? 'selected' : '' }}>East</option>
                                <option value="W" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'W' ? 'selected' : '' }}>West</option>
                                <option value="S" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'S' ? 'selected' : '' }}>South</option>
                                <option value="NE" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'NE' ? 'selected' : '' }}>North-East</option>
                                <option value="NW" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'NW' ? 'selected' : '' }}>North-West</option>
                                <option value="SE" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'SE' ? 'selected' : '' }}>South-East</option>
                                <option value="SW" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'SW' ? 'selected' : '' }}>South-West</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wizard-step-content space-y-6" style="display:none">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — LAND SPECIFICATIONS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Commercial Plot" {{ old('property_type', $property->property_type ?? '') === 'Commercial Plot' ? 'selected' : '' }}>Commercial Plot</option>
                                <option value="Institutional Land" {{ old('property_type', $property->property_type ?? '') === 'Institutional Land' ? 'selected' : '' }}>Institutional Land</option>
                                <option value="Mixed-use" {{ old('property_type', $property->property_type ?? '') === 'Mixed-use' ? 'selected' : '' }}>Mixed-use</option>
                                <option value="Industrial-convertible" {{ old('property_type', $property->property_type ?? '') === 'Industrial-convertible' ? 'selected' : '' }}>Industrial-convertible</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Land Area <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="total_land_area" value="{{ old('total_land_area', $property->total_land_area ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plot Dimensions (ft × ft) </label>
                            <input type="text" name="plot_dimensions_ft_ft" value="{{ old('plot_dimensions_ft_ft', $property->plot_dimensions_ft_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plot Frontage (ft) </label>
                            <input type="number" step="any" name="plot_frontage_ft" value="{{ old('plot_frontage_ft', $property->plot_frontage_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. of Open Sides </label>
                            <select name="no_of_open_sides" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="1" {{ old('no_of_open_sides', $property->no_of_open_sides ?? '') === '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('no_of_open_sides', $property->no_of_open_sides ?? '') === '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('no_of_open_sides', $property->no_of_open_sides ?? '') === '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('no_of_open_sides', $property->no_of_open_sides ?? '') === '4' ? 'selected' : '' }}>4</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Corner Plot </label>
                            <select name="corner_plot" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('corner_plot', $property->corner_plot ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('corner_plot', $property->corner_plot ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Permitted Land Use <span class="text-red-500">*</span></label>
                            <select name="permitted_land_use" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Commercial" {{ old('permitted_land_use', $property->permitted_land_use ?? '') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="Mixed" {{ old('permitted_land_use', $property->permitted_land_use ?? '') === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                                <option value="Institutional" {{ old('permitted_land_use', $property->permitted_land_use ?? '') === 'Institutional' ? 'selected' : '' }}>Institutional</option>
                                <option value="Industrial" {{ old('permitted_land_use', $property->permitted_land_use ?? '') === 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">FAR / FSI Permitted <span class="text-red-500">*</span></label>
                            <input type="text" name="far_fsi_permitted" value="{{ old('far_fsi_permitted', $property->far_fsi_permitted ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ground Coverage Allowed (%) </label>
                            <input type="number" step="any" name="ground_coverage_allowed" value="{{ old('ground_coverage_allowed', $property->ground_coverage_allowed ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Height / Floors Permitted </label>
                            <input type="text" name="max_height_floors_permitted" value="{{ old('max_height_floors_permitted', $property->max_height_floors_permitted ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Wall </label>
                            <select name="boundary_wall" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('boundary_wall', $property->boundary_wall ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('boundary_wall', $property->boundary_wall ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Partial" {{ old('boundary_wall', $property->boundary_wall ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership Type <span class="text-red-500">*</span></label>
                            <select name="ownership_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Freehold" {{ old('ownership_type', $property->ownership_type ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                <option value="Leasehold-Govt" {{ old('ownership_type', $property->ownership_type ?? '') === 'Leasehold-Govt' ? 'selected' : '' }}>Leasehold-Govt</option>
                                <option value="Leasehold-Private" {{ old('ownership_type', $property->ownership_type ?? '') === 'Leasehold-Private' ? 'selected' : '' }}>Leasehold-Private</option>
                                <option value="Industrial Estate" {{ old('ownership_type', $property->ownership_type ?? '') === 'Industrial Estate' ? 'selected' : '' }}>Industrial Estate</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span class="text-red-500">*</span></label>
                            <select name="title_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Clear" {{ old('title_status', $property->title_status ?? '') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                <option value="Dispute" {{ old('title_status', $property->title_status ?? '') === 'Dispute' ? 'selected' : '' }}>Dispute</option>
                                <option value="Encumbrance" {{ old('title_status', $property->title_status ?? '') === 'Encumbrance' ? 'selected' : '' }}>Encumbrance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CLU / Land Use Approval <span class="text-red-500">*</span></label>
                            <select name="clu_land_use_approval" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Approved" {{ old('clu_land_use_approval', $property->clu_land_use_approval ?? '') === 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Applied" {{ old('clu_land_use_approval', $property->clu_land_use_approval ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not Converted" {{ old('clu_land_use_approval', $property->clu_land_use_approval ?? '') === 'Not Converted' ? 'selected' : '' }}>Not Converted</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Khasra / Survey Number </label>
                            <input type="text" name="khasra_survey_number" value="{{ old('khasra_survey_number', $property->khasra_survey_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Litigation <span class="text-red-500">*</span></label>
                            <select name="encumbrance_litigation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('encumbrance_litigation', $property->encumbrance_litigation ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Ongoing" {{ old('encumbrance_litigation', $property->encumbrance_litigation ?? '') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Environment Clearance (if needed) </label>
                            <select name="environment_clearance_if_needed" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('environment_clearance_if_needed', $property->environment_clearance_if_needed ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied" {{ old('environment_clearance_if_needed', $property->environment_clearance_if_needed ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not required" {{ old('environment_clearance_if_needed', $property->environment_clearance_if_needed ?? '') === 'Not required' ? 'selected' : '' }}>Not required</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION E — INFRASTRUCTURE ACCESS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Road Frontage / Approach Width (ft) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="road_frontage_approach_width_ft" value="{{ old('road_frontage_approach_width_ft', $property->road_frontage_approach_width_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distance from Main Road / Highway (km) </label>
                            <input type="text" name="distance_from_main_road_highway_km" value="{{ old('distance_from_main_road_highway_km', $property->distance_from_main_road_highway_km ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Availability at Boundary </label>
                            <select name="power_availability_at_boundary" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('power_availability_at_boundary', $property->power_availability_at_boundary ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('power_availability_at_boundary', $property->power_availability_at_boundary ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="KW available" {{ old('power_availability_at_boundary', $property->power_availability_at_boundary ?? '') === 'KW available' ? 'selected' : '' }}>KW available</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water Availability </label>
                            <select name="water_availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Municipal" {{ old('water_availability', $property->water_availability ?? '') === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                                <option value="Borewell" {{ old('water_availability', $property->water_availability ?? '') === 'Borewell' ? 'selected' : '' }}>Borewell</option>
                                <option value="Not arranged" {{ old('water_availability', $property->water_availability ?? '') === 'Not arranged' ? 'selected' : '' }}>Not arranged</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sewage / Drainage </label>
                            <select name="sewage_drainage" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Available" {{ old('sewage_drainage', $property->sewage_drainage ?? '') === 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="To be built" {{ old('sewage_drainage', $property->sewage_drainage ?? '') === 'To be built' ? 'selected' : '' }}>To be built</option>
                                <option value="None" {{ old('sewage_drainage', $property->sewage_drainage ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Flood / Water-logging Risk </label>
                            <select name="flood_water_logging_risk" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('flood_water_logging_risk', $property->flood_water_logging_risk ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Low" {{ old('flood_water_logging_risk', $property->flood_water_logging_risk ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Moderate" {{ old('flood_water_logging_risk', $property->flood_water_logging_risk ?? '') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="High" {{ old('flood_water_logging_risk', $property->flood_water_logging_risk ?? '') === 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Surrounding Development </label>
                            <input type="text" name="surrounding_development" value="{{ old('surrounding_development', $property->surrounding_development ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Listing Purpose (Transaction Type) <span class="text-red-500">*</span></label>
                            <select name="listing_purpose_transaction_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Sale" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                                <option value="Lease" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Lease' ? 'selected' : '' }}>Lease</option>
                                <option value="Revenue Share" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Revenue Share' ? 'selected' : '' }}>Revenue Share</option>
                                <option value="JV" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'JV' ? 'selected' : '' }}>JV</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹/sq ft or ₹/acre) </label>
                            <input type="text" name="expected_sale_price_sq_ft_or_acre" value="{{ old('expected_sale_price_sq_ft_or_acre', $property->expected_sale_price_sq_ft_or_acre ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price Band (shown live) </label>
                            <select name="sale_price_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('sale_price_band_shown_live', $property->sale_price_band_shown_live ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('sale_price_band_shown_live', $property->sale_price_band_shown_live ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Lease Rent (₹/month) </label>
                            <input type="number" step="any" name="expected_lease_rent_month" value="{{ old('expected_lease_rent_month', $property->expected_lease_rent_month ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lease Rent Band (shown live) </label>
                            <select name="lease_rent_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('lease_rent_band_shown_live', $property->lease_rent_band_shown_live ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('lease_rent_band_shown_live', $property->lease_rent_band_shown_live ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price </label>
                            <input type="number" step="any" name="negotiable_floor_price" value="{{ old('negotiable_floor_price', $property->negotiable_floor_price ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes </label>
                            <textarea name="owner_flexibility_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property->owner_flexibility_notes ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Availability <span class="text-red-500">*</span></label>
                            <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Immediate" {{ old('availability', $property->availability ?? '') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                                <option value="From date" {{ old('availability', $property->availability ?? '') === 'From date' ? 'selected' : '' }}>From date</option>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Land / site photos (facing inward) <span class="text-red-500">*</span></label>
                            <input type="text" name="land_site_photos_facing_inward" value="{{ old('land_site_photos_facing_inward', $property->land_site_photos_facing_inward ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exterior / Building-face Photos </label>
                            <input type="text" name="exterior_building_face_photos" value="{{ old('exterior_building_face_photos', $property->exterior_building_face_photos ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Floor Plan / Layout </label>
                            <input type="text" name="floor_plan_layout" value="{{ old('floor_plan_layout', $property->floor_plan_layout ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Video / Virtual Tour Link </label>
                            <input type="text" name="video_virtual_tour_link" value="{{ old('video_virtual_tour_link', $property->video_virtual_tour_link ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION K — TEAM REMARKS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer / Submitter Remarks <span class="text-red-500">*</span></label>
                            <textarea name="field_officer_submitter_remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('field_officer_submitter_remarks', $property->field_officer_submitter_remarks ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Description (public) </label>
                            <textarea name="property_description_public" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('property_description_public', $property->property_description_public ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer Name <span class="text-red-500">*</span></label>
                            <input type="text" name="field_officer_name" value="{{ old('field_officer_name', $property->field_officer_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Verified </label>
                            <input type="text" name="field_verified" value="{{ old('field_verified', $property->field_verified ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inspection / Submission Date <span class="text-red-500">*</span></label>
                            <input type="date" name="inspection_submission_date" value="{{ old('inspection_submission_date', isset($property->inspection_submission_date) ? $property->inspection_submission_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
            $('.select2-multiple').each(function() {
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

    window.wizardGoTo = function(s) {
        if (s < 0 || s >= window.WIZ_TOTAL) return;
        window.wizCurrent = s;

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

    window.wizardNext = function() { if (typeof window.wizardValidateStep === 'function' && !window.wizardValidateStep(window.wizCurrent)) return; window.wizardGoTo(window.wizCurrent + 1); };
    window.wizardPrev = function() { window.wizardGoTo(window.wizCurrent - 1); };

    $(document).ready(function() {
        initSelect2();
        window.wizardGoTo(0);
    });
</script>
@endsection
