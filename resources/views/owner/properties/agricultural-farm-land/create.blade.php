@extends('layouts.owner')
@php if (!isset($property)) { $property = null; } @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Agricultural / Farm Land - Owner Portal')

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
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Agricultural / Farm Land' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Agricultural / Farm Land</p>
        </div>
        <a href="{{ route('owner.properties.select-type') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Choose Other Type
        </a>
    </div>

    

    <form method="POST" action="{{ isset($property) ? route('owner.properties.agricultural-farm-land.update', $property) : route('owner.properties.agricultural-farm-land.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'Agricultural / Farm Land Submission Form'" :propertyType="'agricultural_farm_land'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distinct legal regime (conversion, irrigation, ceiling laws) · 99acres category </label>
                            <input type="text" name="distinct_legal_regime_conversion_irrigation_ceiling_laws_99a" value="{{ old('distinct_legal_regime_conversion_irrigation_ceiling_laws_99a', $property?->fieldValue('distinct_legal_regime_conversion_irrigation_ceiling_laws_99a') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS  (stored internally · never published)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Full Name <span class="text-red-500">*</span></label>
                            <input required  type="text" name="submitter_full_name" value="{{ old('submitter_full_name', $property?->fieldValue('submitter_full_name') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Phone <span class="text-red-500">*</span></label>
                            <input required  type="text" name="submitter_phone" value="{{ old('submitter_phone', $property?->fieldValue('submitter_phone') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Email <span class="text-red-500">*</span></label>
                            <input required  type="text" name="submitter_email" value="{{ old('submitter_email', $property?->fieldValue('submitter_email') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Role <span class="text-red-500">*</span></label>
                            <select required  name="submitter_role" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Owner" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                <option value="Builder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Builder' ? 'selected' : '' }}>Builder</option>
                                <option value="Authorised Agent" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Authorised Agent' ? 'selected' : '' }}>Authorised Agent</option>
                                <option value="Broker" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'Broker' ? 'selected' : '' }}>Broker</option>
                                <option value="GPA holder" {{ old('submitter_role', $property?->fieldValue('submitter_role') ?? '') === 'GPA holder' ? 'selected' : '' }}>GPA holder</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Full Name <span class="text-red-500">*</span></label>
                            <input required  type="text" name="owner_full_name" value="{{ old('owner_full_name', $property?->fieldValue('owner_full_name') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Contact Number <span class="text-red-500">*</span></label>
                            <input required  type="text" name="owner_contact_number" value="{{ old('owner_contact_number', $property?->fieldValue('owner_contact_number') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                            <input required  type="number" step="any" name="pin_code" value="{{ old('pin_code', $property?->fieldValue('pin_code') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                            <select required  name="city" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="active cities + Other" {{ old('city', $property?->fieldValue('city') ?? '') === 'active cities + Other' ? 'selected' : '' }}>active cities + Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Locality / Broad Area <span class="text-red-500">*</span></label>
                            <input required  type="text" name="locality_broad_area" value="{{ old('locality_broad_area', $property?->fieldValue('locality_broad_area') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Name </label>
                            <input type="text" name="project_name" value="{{ old('project_name', $property?->fieldValue('project_name') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Builder / Developer Name </label>
                            <input type="text" name="builder_developer_name" value="{{ old('builder_developer_name', $property?->fieldValue('builder_developer_name') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Address (house/plot no., street) <span class="text-red-500">*</span></label>
                            <input required  type="text" name="full_address_house_plot_no_street" value="{{ old('full_address_house_plot_no_street', $property?->fieldValue('full_address_house_plot_no_street') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        
                                                <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                            <input required  type="text" name="state" value="{{ old('state', $property?->fieldValue('state') ?? '') }}" required placeholder="State" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GPS Latitude <span class="text-red-500">*</span></label>
                            <input required  type="number" step="0.000001" name="gps_latitude" value="{{ old('gps_latitude', $property?->fieldValue('gps_latitude') ?? '') }}" required placeholder="e.g. 28.459512" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-medium text-gray-700">GPS Longitude <span class="text-red-500">*</span></label>
                                <button type="button" class="btn-use-gps-location text-xs text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-1 focus:outline-none">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Use Current Location</span>
                                </button>
                            </div>
                            <input type="number" step="0.000001" name="gps_longitude" value="{{ old('gps_longitude', $property?->fieldValue('gps_longitude') ?? '') }}" required placeholder="e.g. 77.026634" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nearby Landmarks / Key Distances </label>
                            <input type="text" name="nearby_landmarks_key_distances" value="{{ old('nearby_landmarks_key_distances', $property?->fieldValue('nearby_landmarks_key_distances') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facing / Orientation </label>
                            <select name="facing_orientation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
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
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — LAND SPECIFICATIONS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select required  name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Agricultural Land" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Agricultural Land' ? 'selected' : '' }}>Agricultural Land</option>
                                <option value="Farm Land" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Farm Land' ? 'selected' : '' }}>Farm Land</option>
                                <option value="Plantation" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Plantation' ? 'selected' : '' }}>Plantation</option>
                                <option value="Orchard" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Orchard' ? 'selected' : '' }}>Orchard</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Land Area <span class="text-red-500">*</span></label>
                            <input required  type="number" step="any" name="total_land_area" value="{{ old('total_land_area', $property?->fieldValue('total_land_area') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Area in Standard Unit (sq ft) </label>
                            <input type="number" step="any" name="area_in_standard_unit_sq_ft" value="{{ old('area_in_standard_unit_sq_ft', $property?->fieldValue('area_in_standard_unit_sq_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Land Shape / Contour </label>
                            <select name="land_shape_contour" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Level" {{ old('land_shape_contour', $property?->fieldValue('land_shape_contour') ?? '') === 'Level' ? 'selected' : '' }}>Level</option>
                                <option value="Sloping" {{ old('land_shape_contour', $property?->fieldValue('land_shape_contour') ?? '') === 'Sloping' ? 'selected' : '' }}>Sloping</option>
                                <option value="Terraced" {{ old('land_shape_contour', $property?->fieldValue('land_shape_contour') ?? '') === 'Terraced' ? 'selected' : '' }}>Terraced</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currently Cultivated </label>
                            <select name="currently_cultivated" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('currently_cultivated', $property?->fieldValue('currently_cultivated') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="Fallow" {{ old('currently_cultivated', $property?->fieldValue('currently_cultivated') ?? '') === 'Fallow' ? 'selected' : '' }}>Fallow</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Crop / Plantation </label>
                            <input type="text" name="current_crop_plantation" value="{{ old('current_crop_plantation', $property?->fieldValue('current_crop_plantation') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boundary Demarcation </label>
                            <select name="boundary_demarcation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Fenced" {{ old('boundary_demarcation', $property?->fieldValue('boundary_demarcation') ?? '') === 'Fenced' ? 'selected' : '' }}>Fenced</option>
                                <option value="Bunded" {{ old('boundary_demarcation', $property?->fieldValue('boundary_demarcation') ?? '') === 'Bunded' ? 'selected' : '' }}>Bunded</option>
                                <option value="Open" {{ old('boundary_demarcation', $property?->fieldValue('boundary_demarcation') ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wizard-step-content space-y-6" style="display:none">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION D — LEGAL & COMPLIANCE  (India-specific)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership Type <span class="text-red-500">*</span></label>
                            <select required  name="ownership_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Freehold" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                <option value="Ancestral" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'Ancestral' ? 'selected' : '' }}>Ancestral</option>
                                <option value="GPA" {{ old('ownership_type', $property?->fieldValue('ownership_type') ?? '') === 'GPA' ? 'selected' : '' }}>GPA</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span class="text-red-500">*</span></label>
                            <select required  name="title_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Clear" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                <option value="Dispute" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Dispute' ? 'selected' : '' }}>Dispute</option>
                                <option value="Encumbrance" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Encumbrance' ? 'selected' : '' }}>Encumbrance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Land Classification <span class="text-red-500">*</span></label>
                            <select required  name="land_classification" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Agricultural" {{ old('land_classification', $property?->fieldValue('land_classification') ?? '') === 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="Convertible" {{ old('land_classification', $property?->fieldValue('land_classification') ?? '') === 'Convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="Mixed" {{ old('land_classification', $property?->fieldValue('land_classification') ?? '') === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Conversion (CLU) Feasibility </label>
                            <select name="conversion_clu_feasibility" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Convertible" {{ old('conversion_clu_feasibility', $property?->fieldValue('conversion_clu_feasibility') ?? '') === 'Convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="Applied" {{ old('conversion_clu_feasibility', $property?->fieldValue('conversion_clu_feasibility') ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not convertible" {{ old('conversion_clu_feasibility', $property?->fieldValue('conversion_clu_feasibility') ?? '') === 'Not convertible' ? 'selected' : '' }}>Not convertible</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buyer Eligibility Restriction <span class="text-red-500">*</span></label>
                            <select required  name="buyer_eligibility_restriction" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Agriculturist only" {{ old('buyer_eligibility_restriction', $property?->fieldValue('buyer_eligibility_restriction') ?? '') === 'Agriculturist only' ? 'selected' : '' }}>Agriculturist only</option>
                                <option value="Open" {{ old('buyer_eligibility_restriction', $property?->fieldValue('buyer_eligibility_restriction') ?? '') === 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="State-specific" {{ old('buyer_eligibility_restriction', $property?->fieldValue('buyer_eligibility_restriction') ?? '') === 'State-specific' ? 'selected' : '' }}>State-specific</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Land Ceiling Compliance </label>
                            <select name="land_ceiling_compliance" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Within ceiling" {{ old('land_ceiling_compliance', $property?->fieldValue('land_ceiling_compliance') ?? '') === 'Within ceiling' ? 'selected' : '' }}>Within ceiling</option>
                                <option value="Needs check" {{ old('land_ceiling_compliance', $property?->fieldValue('land_ceiling_compliance') ?? '') === 'Needs check' ? 'selected' : '' }}>Needs check</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Khasra / Survey / Khata Number </label>
                            <input type="text" name="khasra_survey_khata_number" value="{{ old('khasra_survey_khata_number', $property?->fieldValue('khasra_survey_khata_number') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Litigation <span class="text-red-500">*</span></label>
                            <select required  name="encumbrance_litigation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('encumbrance_litigation', $property?->fieldValue('encumbrance_litigation') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Ongoing" {{ old('encumbrance_litigation', $property?->fieldValue('encumbrance_litigation') ?? '') === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mutation / Records Updated </label>
                            <select name="mutation_records_updated" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('mutation_records_updated', $property?->fieldValue('mutation_records_updated') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="Pending" {{ old('mutation_records_updated', $property?->fieldValue('mutation_records_updated') ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION E — ACCESS, WATER & INFRASTRUCTURE</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Road Access <span class="text-red-500">*</span></label>
                            <select required  name="road_access" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Metalled" {{ old('road_access', $property?->fieldValue('road_access') ?? '') === 'Metalled' ? 'selected' : '' }}>Metalled</option>
                                <option value="Kutcha" {{ old('road_access', $property?->fieldValue('road_access') ?? '') === 'Kutcha' ? 'selected' : '' }}>Kutcha</option>
                                <option value="None" {{ old('road_access', $property?->fieldValue('road_access') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Approach Road Width (ft) </label>
                            <input type="number" step="any" name="approach_road_width_ft" value="{{ old('approach_road_width_ft', $property?->fieldValue('approach_road_width_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water Source <span class="text-red-500">*</span></label>
                            <select required  name="water_source" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Canal" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Canal' ? 'selected' : '' }}>Canal</option>
                                <option value="Borewell" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Borewell' ? 'selected' : '' }}>Borewell</option>
                                <option value="River" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'River' ? 'selected' : '' }}>River</option>
                                <option value="Rain-fed" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Rain-fed' ? 'selected' : '' }}>Rain-fed</option>
                                <option value="Tank" {{ old('water_source', $property?->fieldValue('water_source') ?? '') === 'Tank' ? 'selected' : '' }}>Tank</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Irrigation Type </label>
                            <select name="irrigation_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Drip" {{ old('irrigation_type', $property?->fieldValue('irrigation_type') ?? '') === 'Drip' ? 'selected' : '' }}>Drip</option>
                                <option value="Sprinkler" {{ old('irrigation_type', $property?->fieldValue('irrigation_type') ?? '') === 'Sprinkler' ? 'selected' : '' }}>Sprinkler</option>
                                <option value="Flood" {{ old('irrigation_type', $property?->fieldValue('irrigation_type') ?? '') === 'Flood' ? 'selected' : '' }}>Flood</option>
                                <option value="None" {{ old('irrigation_type', $property?->fieldValue('irrigation_type') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Electricity Connection </label>
                            <select name="electricity_connection" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Agri connection" {{ old('electricity_connection', $property?->fieldValue('electricity_connection') ?? '') === 'Agri connection' ? 'selected' : '' }}>Agri connection</option>
                                <option value="None" {{ old('electricity_connection', $property?->fieldValue('electricity_connection') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Soil Type </label>
                            <select name="soil_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Black" {{ old('soil_type', $property?->fieldValue('soil_type') ?? '') === 'Black' ? 'selected' : '' }}>Black</option>
                                <option value="Red" {{ old('soil_type', $property?->fieldValue('soil_type') ?? '') === 'Red' ? 'selected' : '' }}>Red</option>
                                <option value="Alluvial" {{ old('soil_type', $property?->fieldValue('soil_type') ?? '') === 'Alluvial' ? 'selected' : '' }}>Alluvial</option>
                                <option value="Loamy" {{ old('soil_type', $property?->fieldValue('soil_type') ?? '') === 'Loamy' ? 'selected' : '' }}>Loamy</option>
                                <option value="Other" {{ old('soil_type', $property?->fieldValue('soil_type') ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distance from Town / Highway (km) </label>
                            <input type="text" name="distance_from_town_highway_km" value="{{ old('distance_from_town_highway_km', $property?->fieldValue('distance_from_town_highway_km') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Flood / Drought Risk </label>
                            <select name="flood_drought_risk" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('flood_drought_risk', $property?->fieldValue('flood_drought_risk') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Low" {{ old('flood_drought_risk', $property?->fieldValue('flood_drought_risk') ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Moderate" {{ old('flood_drought_risk', $property?->fieldValue('flood_drought_risk') ?? '') === 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                <option value="High" {{ old('flood_drought_risk', $property?->fieldValue('flood_drought_risk') ?? '') === 'High' ? 'selected' : '' }}>High</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Listing Purpose (Transaction Type) <span class="text-red-500">*</span></label>
                            <select required  name="listing_purpose_transaction_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Sale" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                                <option value="Lease" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Lease' ? 'selected' : '' }}>Lease</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Price (₹/acre or ₹/bigha) </label>
                            <input type="text" name="expected_price_acre_or_bigha" value="{{ old('expected_price_acre_or_bigha', $property?->fieldValue('expected_price_acre_or_bigha') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Band (shown live) </label>
                            <select name="price_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('price_band_shown_live', $property?->fieldValue('price_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('price_band_shown_live', $property?->fieldValue('price_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lease Rent (₹/acre/year) </label>
                            <input type="number" step="any" name="lease_rent_acre_year" value="{{ old('lease_rent_acre_year', $property?->fieldValue('lease_rent_acre_year') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lease Rent Band (shown live) </label>
                            <select name="lease_rent_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('lease_rent_band_shown_live', $property?->fieldValue('lease_rent_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('lease_rent_band_shown_live', $property?->fieldValue('lease_rent_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price </label>
                            <input type="number" step="any" name="negotiable_floor_price" value="{{ old('negotiable_floor_price', $property?->fieldValue('negotiable_floor_price') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes </label>
                            <textarea name="owner_flexibility_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property?->fieldValue('owner_flexibility_notes') ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Availability <span class="text-red-500">*</span></label>
                            <select required  name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
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
                        <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION K — TEAM REMARKS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer / Submitter Remarks <span class="text-red-500">*</span></label>
                            <textarea required  name="field_officer_submitter_remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('field_officer_submitter_remarks', $property?->fieldValue('field_officer_submitter_remarks') ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Description (public) </label>
                            <textarea name="property_description_public" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('property_description_public', $property?->fieldValue('property_description_public') ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Officer Name <span class="text-red-500">*</span></label>
                            <input required  type="text" name="field_officer_name" value="{{ old('field_officer_name', $property?->fieldValue('field_officer_name') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Verified </label>
                            <input type="text" name="field_verified" value="{{ old('field_verified', $property?->fieldValue('field_verified') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inspection / Submission Date <span class="text-red-500">*</span></label>
                            <input required  type="date" name="inspection_submission_date" value="{{ old('inspection_submission_date', ($property?->fieldValue('inspection_submission_date') !== null) ? $property?->fieldValue('inspection_submission_date')->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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

    window.wizardNext = function() { if (typeof window.wizardValidateStep === 'function' && !window.wizardValidateStep(window.wizCurrent)) return; window.wizardGoTo(window.wizCurrent + 1); };
    window.wizardPrev = function() { window.wizardGoTo(window.wizCurrent - 1); };

    $(document).ready(function() {
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
