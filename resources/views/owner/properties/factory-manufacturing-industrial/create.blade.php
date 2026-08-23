@extends('layouts.owner')
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Factory / Manufacturing / Industrial - Owner Portal')

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

<div class="max-w-4xl mx-auto space-y-6" x-data="stepWizard()">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Factory / Manufacturing / Industrial' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Factory / Manufacturing / Industrial</p>
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

    <form method="POST" action="{{ isset($property) ? route('owner.properties.factory-manufacturing-industrial.update', $property) : route('owner.properties.factory-manufacturing-industrial.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'Factory / Manufacturing / Industrial Submission Form'" :propertyType="'factory_manufacturing_industrial'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Running/ready industrial unit with plant, power & effluent · distinct from bare warehouse forms </label>
                            <input type="text" name="running_ready_industrial_unit_with_plant_power_effluent_dist" value="{{ old('running_ready_industrial_unit_with_plant_power_effluent_dist', $property->running_ready_industrial_unit_with_plant_power_effluent_dist ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS  (stored internally · never published)</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pin Code <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="pin_code" value="{{ old('pin_code', $property->pin_code ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                                <option value="N" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'N' ? 'selected' : '' }}>N</option>
                                <option value="E" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'E' ? 'selected' : '' }}>E</option>
                                <option value="W" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'W' ? 'selected' : '' }}>W</option>
                                <option value="S" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'S' ? 'selected' : '' }}>S</option>
                                <option value="NE" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'NE' ? 'selected' : '' }}>NE</option>
                                <option value="NW" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'NW' ? 'selected' : '' }}>NW</option>
                                <option value="SE" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'SE' ? 'selected' : '' }}>SE</option>
                                <option value="SW" {{ old('facing_orientation', $property->facing_orientation ?? '') === 'SW' ? 'selected' : '' }}>SW</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wizard-step-content space-y-6" style="display:none">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — UNIT SPECIFICATIONS</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Factory" {{ old('property_type', $property->property_type ?? '') === 'Factory' ? 'selected' : '' }}>Factory</option>
                                <option value="Manufacturing Unit" {{ old('property_type', $property->property_type ?? '') === 'Manufacturing Unit' ? 'selected' : '' }}>Manufacturing Unit</option>
                                <option value="Industrial Building" {{ old('property_type', $property->property_type ?? '') === 'Industrial Building' ? 'selected' : '' }}>Industrial Building</option>
                                <option value="Industrial Floor" {{ old('property_type', $property->property_type ?? '') === 'Industrial Floor' ? 'selected' : '' }}>Industrial Floor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plot Area (sq ft / acre) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="plot_area_sq_ft_acre" value="{{ old('plot_area_sq_ft_acre', $property->plot_area_sq_ft_acre ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Covered / Built-up Area (sq ft) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="covered_built_up_area_sq_ft" value="{{ old('covered_built_up_area_sq_ft', $property->covered_built_up_area_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carpet / Production Area (sq ft) </label>
                            <input type="number" step="any" name="carpet_production_area_sq_ft" value="{{ old('carpet_production_area_sq_ft', $property->carpet_production_area_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. of Floors </label>
                            <input type="number" step="any" name="no_of_floors" value="{{ old('no_of_floors', $property->no_of_floors ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Which Floor (if floor) </label>
                            <select name="which_floor_if_floor" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Ground" {{ old('which_floor_if_floor', $property->which_floor_if_floor ?? '') === 'Ground' ? 'selected' : '' }}>Ground</option>
                                <option value="Upper" {{ old('which_floor_if_floor', $property->which_floor_if_floor ?? '') === 'Upper' ? 'selected' : '' }}>Upper</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Clear Height (ft) </label>
                            <input type="text" name="clear_height_ft" value="{{ old('clear_height_ft', $property->clear_height_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shed Type </label>
                            <select name="shed_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="RCC" {{ old('shed_type', $property->shed_type ?? '') === 'RCC' ? 'selected' : '' }}>RCC</option>
                                <option value="PEB" {{ old('shed_type', $property->shed_type ?? '') === 'PEB' ? 'selected' : '' }}>PEB</option>
                                <option value="Load-bearing" {{ old('shed_type', $property->shed_type ?? '') === 'Load-bearing' ? 'selected' : '' }}>Load-bearing</option>
                                <option value="Hybrid" {{ old('shed_type', $property->shed_type ?? '') === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Crane / Gantry Provision </label>
                            <select name="crane_gantry_provision" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes (capacity)" {{ old('crane_gantry_provision', $property->crane_gantry_provision ?? '') === 'Yes (capacity)' ? 'selected' : '' }}>Yes (capacity)</option>
                                <option value="No" {{ old('crane_gantry_provision', $property->crane_gantry_provision ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Office / Admin Block Area (sq ft) </label>
                            <input type="number" step="any" name="office_admin_block_area_sq_ft" value="{{ old('office_admin_block_area_sq_ft', $property->office_admin_block_area_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plant & Machinery Included <span class="text-red-500">*</span></label>
                            <select name="plant_machinery_included" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('plant_machinery_included', $property->plant_machinery_included ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('plant_machinery_included', $property->plant_machinery_included ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Partial" {{ old('plant_machinery_included', $property->plant_machinery_included ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing / Readiness <span class="text-red-500">*</span></label>
                            <select name="furnishing_readiness" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Bare structure" {{ old('furnishing_readiness', $property->furnishing_readiness ?? '') === 'Bare structure' ? 'selected' : '' }}>Bare structure</option>
                                <option value="Ready to operate" {{ old('furnishing_readiness', $property->furnishing_readiness ?? '') === 'Ready to operate' ? 'selected' : '' }}>Ready to operate</option>
                                <option value="Plug-and-play" {{ old('furnishing_readiness', $property->furnishing_readiness ?? '') === 'Plug-and-play' ? 'selected' : '' }}>Plug-and-play</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age of Building </label>
                            <select name="age_of_building" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="<1" {{ old('age_of_building', $property->age_of_building ?? '') === '<1' ? 'selected' : '' }}><1</option>
                                <option value="1-5" {{ old('age_of_building', $property->age_of_building ?? '') === '1-5' ? 'selected' : '' }}>1-5</option>
                                <option value="5-10" {{ old('age_of_building', $property->age_of_building ?? '') === '5-10' ? 'selected' : '' }}>5-10</option>
                                <option value="10+ yrs" {{ old('age_of_building', $property->age_of_building ?? '') === '10+ yrs' ? 'selected' : '' }}>10+ yrs</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wizard-step-content space-y-6" style="display:none">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION D — LEGAL & STATUTORY COMPLIANCE</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership / Title Type <span class="text-red-500">*</span></label>
                            <select name="ownership_title_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Freehold" {{ old('ownership_title_type', $property->ownership_title_type ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                <option value="Leasehold-MIDC" {{ old('ownership_title_type', $property->ownership_title_type ?? '') === 'Leasehold-MIDC' ? 'selected' : '' }}>Leasehold-MIDC</option>
                                <option value="GIDC" {{ old('ownership_title_type', $property->ownership_title_type ?? '') === 'GIDC' ? 'selected' : '' }}>GIDC</option>
                                <option value="etc" {{ old('ownership_title_type', $property->ownership_title_type ?? '') === 'etc' ? 'selected' : '' }}>etc</option>
                                <option value="Industrial Estate" {{ old('ownership_title_type', $property->ownership_title_type ?? '') === 'Industrial Estate' ? 'selected' : '' }}>Industrial Estate</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">CLU / Industrial Use Approved <span class="text-red-500">*</span></label>
                            <select name="clu_industrial_use_approved" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Approved" {{ old('clu_industrial_use_approved', $property->clu_industrial_use_approved ?? '') === 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Applied" {{ old('clu_industrial_use_approved', $property->clu_industrial_use_approved ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not Converted" {{ old('clu_industrial_use_approved', $property->clu_industrial_use_approved ?? '') === 'Not Converted' ? 'selected' : '' }}>Not Converted</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Factory Licence </label>
                            <select name="factory_licence" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Held" {{ old('factory_licence', $property->factory_licence ?? '') === 'Held' ? 'selected' : '' }}>Held</option>
                                <option value="Applied" {{ old('factory_licence', $property->factory_licence ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="None" {{ old('factory_licence', $property->factory_licence ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pollution / Consent to Operate <span class="text-red-500">*</span></label>
                            <select name="pollution_consent_to_operate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('pollution_consent_to_operate', $property->pollution_consent_to_operate ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied" {{ old('pollution_consent_to_operate', $property->pollution_consent_to_operate ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="NA" {{ old('pollution_consent_to_operate', $property->pollution_consent_to_operate ?? '') === 'NA' ? 'selected' : '' }}>NA</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pollution Category </label>
                            <select name="pollution_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Green" {{ old('pollution_category', $property->pollution_category ?? '') === 'Green' ? 'selected' : '' }}>Green</option>
                                <option value="Orange" {{ old('pollution_category', $property->pollution_category ?? '') === 'Orange' ? 'selected' : '' }}>Orange</option>
                                <option value="Red" {{ old('pollution_category', $property->pollution_category ?? '') === 'Red' ? 'selected' : '' }}>Red</option>
                                <option value="White" {{ old('pollution_category', $property->pollution_category ?? '') === 'White' ? 'selected' : '' }}>White</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fire NOC <span class="text-red-500">*</span></label>
                            <select name="fire_noc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('fire_noc', $property->fire_noc ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied" {{ old('fire_noc', $property->fire_noc ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not applied" {{ old('fire_noc', $property->fire_noc ?? '') === 'Not applied' ? 'selected' : '' }}>Not applied</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Environment Clearance (if applicable) </label>
                            <select name="environment_clearance_if_applicable" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('environment_clearance_if_applicable', $property->environment_clearance_if_applicable ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied" {{ old('environment_clearance_if_applicable', $property->environment_clearance_if_applicable ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="NA" {{ old('environment_clearance_if_applicable', $property->environment_clearance_if_applicable ?? '') === 'NA' ? 'selected' : '' }}>NA</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Loan <span class="text-red-500">*</span></label>
                            <select name="encumbrance_loan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('encumbrance_loan', $property->encumbrance_loan ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Loan" {{ old('encumbrance_loan', $property->encumbrance_loan ?? '') === 'Loan' ? 'selected' : '' }}>Loan</option>
                                <option value="Mortgage" {{ old('encumbrance_loan', $property->encumbrance_loan ?? '') === 'Mortgage' ? 'selected' : '' }}>Mortgage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION E — UTILITIES & INFRASTRUCTURE</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sanctioned Power Load (KW/KVA) <span class="text-red-500">*</span></label>
                            <input type="text" name="sanctioned_power_load_kw_kva" value="{{ old('sanctioned_power_load_kw_kva', $property->sanctioned_power_load_kw_kva ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Connection Type </label>
                            <select name="power_connection_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="HT" {{ old('power_connection_type', $property->power_connection_type ?? '') === 'HT' ? 'selected' : '' }}>HT</option>
                                <option value="LT" {{ old('power_connection_type', $property->power_connection_type ?? '') === 'LT' ? 'selected' : '' }}>LT</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DISCOM Name </label>
                            <input type="text" name="discom_name" value="{{ old('discom_name', $property->discom_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DG Set / Backup </label>
                            <select name="dg_set_backup" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes (KVA)" {{ old('dg_set_backup', $property->dg_set_backup ?? '') === 'Yes (KVA)' ? 'selected' : '' }}>Yes (KVA)</option>
                                <option value="No" {{ old('dg_set_backup', $property->dg_set_backup ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water Source / Capacity </label>
                            <input type="text" name="water_source_capacity" value="{{ old('water_source_capacity', $property->water_source_capacity ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Effluent Treatment (ETP/STP) </label>
                            <select name="effluent_treatment_etp_stp" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="ETP" {{ old('effluent_treatment_etp_stp', $property->effluent_treatment_etp_stp ?? '') === 'ETP' ? 'selected' : '' }}>ETP</option>
                                <option value="STP" {{ old('effluent_treatment_etp_stp', $property->effluent_treatment_etp_stp ?? '') === 'STP' ? 'selected' : '' }}>STP</option>
                                <option value="CETP connected" {{ old('effluent_treatment_etp_stp', $property->effluent_treatment_etp_stp ?? '') === 'CETP connected' ? 'selected' : '' }}>CETP connected</option>
                                <option value="None" {{ old('effluent_treatment_etp_stp', $property->effluent_treatment_etp_stp ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boiler / Steam / Gas Line </label>
                            <select name="boiler_steam_gas_line" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('boiler_steam_gas_line', $property->boiler_steam_gas_line ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('boiler_steam_gas_line', $property->boiler_steam_gas_line ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Approach Road Width (ft) </label>
                            <input type="number" step="any" name="approach_road_width_ft" value="{{ old('approach_road_width_ft', $property->approach_road_width_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Truck Movement / Container Access </label>
                            <select name="truck_movement_container_access" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('truck_movement_container_access', $property->truck_movement_container_access ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="Restricted" {{ old('truck_movement_container_access', $property->truck_movement_container_access ?? '') === 'Restricted' ? 'selected' : '' }}>Restricted</option>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Listing Purpose (Transaction Type) <span class="text-red-500">*</span></label>
                            <select name="listing_purpose_transaction_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Lease" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Lease' ? 'selected' : '' }}>Lease</option>
                                <option value="Sale" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                                <option value="Both" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Both' ? 'selected' : '' }}>Both</option>
                                <option value="Leave & License" {{ old('listing_purpose_transaction_type', $property->listing_purpose_transaction_type ?? '') === 'Leave & License' ? 'selected' : '' }}>Leave & License</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price on Request </label>
                            <select name="price_on_request" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('price_on_request', $property->price_on_request ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('price_on_request', $property->price_on_request ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Rent (₹/sq ft/month) </label>
                            <input type="text" name="expected_rent_sq_ft_month" value="{{ old('expected_rent_sq_ft_month', $property->expected_rent_sq_ft_month ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rent Range Band (shown live) </label>
                            <select name="rent_range_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('rent_range_band_shown_live', $property->rent_range_band_shown_live ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('rent_range_band_shown_live', $property->rent_range_band_shown_live ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹/sq ft) </label>
                            <input type="text" name="expected_sale_price_sq_ft" value="{{ old('expected_sale_price_sq_ft', $property->expected_sale_price_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">CAM Charges (₹/sq ft/month) </label>
                            <input type="text" name="cam_charges_sq_ft_month" value="{{ old('cam_charges_sq_ft_month', $property->cam_charges_sq_ft_month ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit </label>
                            <select name="security_deposit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="3" {{ old('security_deposit', $property->security_deposit ?? '') === '3' ? 'selected' : '' }}>3</option>
                                <option value="6" {{ old('security_deposit', $property->security_deposit ?? '') === '6' ? 'selected' : '' }}>6</option>
                                <option value="12 months" {{ old('security_deposit', $property->security_deposit ?? '') === '12 months' ? 'selected' : '' }}>12 months</option>
                                <option value="Negotiable" {{ old('security_deposit', $property->security_deposit ?? '') === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Period (months) </label>
                            <input type="number" step="any" name="lock_in_period_months" value="{{ old('lock_in_period_months', $property->lock_in_period_months ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Annual Escalation (%) </label>
                            <input type="text" name="annual_escalation" value="{{ old('annual_escalation', $property->annual_escalation ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fit-out / Rent-free Period (months) </label>
                            <input type="number" step="any" name="fit_out_rent_free_period_months" value="{{ old('fit_out_rent_free_period_months', $property->fit_out_rent_free_period_months ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price </label>
                            <input type="number" step="any" name="negotiable_floor_price" value="{{ old('negotiable_floor_price', $property->negotiable_floor_price ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes </label>
                            <textarea name="owner_flexibility_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property->owner_flexibility_notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION H — INVESTMENT / ROI  (if property is tenanted / pre-leased and being sold)</h4>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currently Rented / Tenanted <span class="text-red-500">*</span></label>
                            <select name="currently_rented_tenanted" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Partially" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? '') === 'Partially' ? 'selected' : '' }}>Partially</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Monthly Rent Received (₹) </label>
                            <input type="number" step="any" name="current_monthly_rent_received" value="{{ old('current_monthly_rent_received', $property->current_monthly_rent_received ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rental Income Band (shown live) </label>
                            <select name="rental_income_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('rental_income_band_shown_live', $property->rental_income_band_shown_live ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('rental_income_band_shown_live', $property->rental_income_band_shown_live ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rental Yield / ROI (% p.a.) </label>
                            <input type="text" name="rental_yield_roi_p_a" value="{{ old('rental_yield_roi_p_a', $property->rental_yield_roi_p_a ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Type (public) </label>
                            <select name="tenant_type_public" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="MNC" {{ old('tenant_type_public', $property->tenant_type_public ?? '') === 'MNC' ? 'selected' : '' }}>MNC</option>
                                <option value="Corporate" {{ old('tenant_type_public', $property->tenant_type_public ?? '') === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="Bank" {{ old('tenant_type_public', $property->tenant_type_public ?? '') === 'Bank' ? 'selected' : '' }}>Bank</option>
                                <option value="Brand Retail" {{ old('tenant_type_public', $property->tenant_type_public ?? '') === 'Brand Retail' ? 'selected' : '' }}>Brand Retail</option>
                                <option value="Individual" {{ old('tenant_type_public', $property->tenant_type_public ?? '') === 'Individual' ? 'selected' : '' }}>Individual</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lease Tenure (years) </label>
                            <input type="number" step="any" name="lease_tenure_years" value="{{ old('lease_tenure_years', $property->lease_tenure_years ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Remaining (months) </label>
                            <input type="number" step="any" name="lock_in_remaining_months" value="{{ old('lock_in_remaining_months', $property->lock_in_remaining_months ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CAM / Outgoings Borne By </label>
                            <select name="cam_outgoings_borne_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Tenant" {{ old('cam_outgoings_borne_by', $property->cam_outgoings_borne_by ?? '') === 'Tenant' ? 'selected' : '' }}>Tenant</option>
                                <option value="Owner" {{ old('cam_outgoings_borne_by', $property->cam_outgoings_borne_by ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                <option value="Shared" {{ old('cam_outgoings_borne_by', $property->cam_outgoings_borne_by ?? '') === 'Shared' ? 'selected' : '' }}>Shared</option>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interior shop-floor / unit photos <span class="text-red-500">*</span></label>
                            <input type="text" name="interior_shop_floor_unit_photos" value="{{ old('interior_shop_floor_unit_photos', $property->interior_shop_floor_unit_photos ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer / Submitter Remarks <span class="text-red-500">*</span></label>
                            <textarea name="field_officer_submitter_remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('field_officer_submitter_remarks', $property->field_officer_submitter_remarks ?? '') }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
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

    window.wizardNext = function() { window.wizardGoTo(window.wizCurrent + 1); };
    window.wizardPrev = function() { window.wizardGoTo(window.wizCurrent - 1); };

    $(document).ready(function() {
        initSelect2();
        window.wizardGoTo(0);
    });
</script>
@endsection
