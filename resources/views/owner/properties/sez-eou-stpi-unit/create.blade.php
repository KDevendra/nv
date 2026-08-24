@extends('layouts.owner')
@section('title', (isset($property) ? 'Edit' : 'List New') . ' SEZ / EOU / STPI Unit - Owner Portal')

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
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List SEZ / EOU / STPI Unit' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for SEZ / EOU / STPI Unit</p>
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

    <form method="POST" action="{{ isset($property) ? route('owner.properties.sez-eou-stpi-unit.update', $property) : route('owner.properties.sez-eou-stpi-unit.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'SEZ / EOU / STPI Unit Submission Form'" :propertyType="'sez_eou_stpi_unit'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonded, export-oriented unit · distinct compliance (LoA, NFE, customs bonding) · not in warehousing spec </label>
                            <input type="text" name="bonded_export_oriented_unit_distinct_compliance_loa_nfe_cust" value="{{ old('bonded_export_oriented_unit_distinct_compliance_loa_nfe_cust', $property->bonded_export_oriented_unit_distinct_compliance_loa_nfe_cust ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — UNIT CONFIGURATION</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="SEZ Unit" {{ old('property_type', $property->property_type ?? '') === 'SEZ Unit' ? 'selected' : '' }}>SEZ Unit</option>
                                <option value="EOU" {{ old('property_type', $property->property_type ?? '') === 'EOU' ? 'selected' : '' }}>EOU</option>
                                <option value="STPI Unit" {{ old('property_type', $property->property_type ?? '') === 'STPI Unit' ? 'selected' : '' }}>STPI Unit</option>
                                <option value="FTWZ Unit" {{ old('property_type', $property->property_type ?? '') === 'FTWZ Unit' ? 'selected' : '' }}>FTWZ Unit</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zone / Park Name <span class="text-red-500">*</span></label>
                            <input type="text" name="zone_park_name" value="{{ old('zone_park_name', $property->zone_park_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Space Type <span class="text-red-500">*</span></label>
                            <select name="space_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Built-up office" {{ old('space_type', $property->space_type ?? '') === 'Built-up office' ? 'selected' : '' }}>Built-up office</option>
                                <option value="Built-up factory" {{ old('space_type', $property->space_type ?? '') === 'Built-up factory' ? 'selected' : '' }}>Built-up factory</option>
                                <option value="Bare shell" {{ old('space_type', $property->space_type ?? '') === 'Bare shell' ? 'selected' : '' }}>Bare shell</option>
                                <option value="Land for own build" {{ old('space_type', $property->space_type ?? '') === 'Land for own build' ? 'selected' : '' }}>Land for own build</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carpet / Usable Area (sq ft) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" name="carpet_usable_area_sq_ft" value="{{ old('carpet_usable_area_sq_ft', $property->carpet_usable_area_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Built-up Area (sq ft) </label>
                            <input type="number" step="any" name="built_up_area_sq_ft" value="{{ old('built_up_area_sq_ft', $property->built_up_area_sq_ft ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Floor / Total Floors </label>
                            <input type="text" name="floor_total_floors" value="{{ old('floor_total_floors', $property->floor_total_floors ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing / Fit-out <span class="text-red-500">*</span></label>
                            <select name="furnishing_fit_out" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Bare shell" {{ old('furnishing_fit_out', $property->furnishing_fit_out ?? '') === 'Bare shell' ? 'selected' : '' }}>Bare shell</option>
                                <option value="Warm shell" {{ old('furnishing_fit_out', $property->furnishing_fit_out ?? '') === 'Warm shell' ? 'selected' : '' }}>Warm shell</option>
                                <option value="Furnished" {{ old('furnishing_fit_out', $property->furnishing_fit_out ?? '') === 'Furnished' ? 'selected' : '' }}>Furnished</option>
                                <option value="Plug-and-play" {{ old('furnishing_fit_out', $property->furnishing_fit_out ?? '') === 'Plug-and-play' ? 'selected' : '' }}>Plug-and-play</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sector Focus </label>
                            <select name="sector_focus" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="IT" {{ old('sector_focus', $property->sector_focus ?? '') === 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="ITES" {{ old('sector_focus', $property->sector_focus ?? '') === 'ITES' ? 'selected' : '' }}>ITES</option>
                                <option value="Electronics" {{ old('sector_focus', $property->sector_focus ?? '') === 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Pharma" {{ old('sector_focus', $property->sector_focus ?? '') === 'Pharma' ? 'selected' : '' }}>Pharma</option>
                                <option value="Biotech" {{ old('sector_focus', $property->sector_focus ?? '') === 'Biotech' ? 'selected' : '' }}>Biotech</option>
                                <option value="Engineering" {{ old('sector_focus', $property->sector_focus ?? '') === 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Multi-product" {{ old('sector_focus', $property->sector_focus ?? '') === 'Multi-product' ? 'selected' : '' }}>Multi-product</option>
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
                        <h4 class="text-base font-bold text-zendo-navy">SECTION D — SEZ / EXPORT COMPLIANCE  (India-specific — key value-add)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Zone Notification Status <span class="text-red-500">*</span></label>
                            <select name="zone_notification_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Notified Operational SEZ" {{ old('zone_notification_status', $property->zone_notification_status ?? '') === 'Notified Operational SEZ' ? 'selected' : '' }}>Notified Operational SEZ</option>
                                <option value="STPI Notified" {{ old('zone_notification_status', $property->zone_notification_status ?? '') === 'STPI Notified' ? 'selected' : '' }}>STPI Notified</option>
                                <option value="EOU Bonded" {{ old('zone_notification_status', $property->zone_notification_status ?? '') === 'EOU Bonded' ? 'selected' : '' }}>EOU Bonded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Processing / Non-Processing Area <span class="text-red-500">*</span></label>
                            <select name="processing_non_processing_area" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Processing Area" {{ old('processing_non_processing_area', $property->processing_non_processing_area ?? '') === 'Processing Area' ? 'selected' : '' }}>Processing Area</option>
                                <option value="Non-Processing Area" {{ old('processing_non_processing_area', $property->processing_non_processing_area ?? '') === 'Non-Processing Area' ? 'selected' : '' }}>Non-Processing Area</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Developer Consent for Sub-lease <span class="text-red-500">*</span></label>
                            <select name="developer_consent_for_sub_lease" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Available" {{ old('developer_consent_for_sub_lease', $property->developer_consent_for_sub_lease ?? '') === 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="To be obtained" {{ old('developer_consent_for_sub_lease', $property->developer_consent_for_sub_lease ?? '') === 'To be obtained' ? 'selected' : '' }}>To be obtained</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Letter of Approval (LoA) Support </label>
                            <select name="letter_of_approval_loa_support" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Developer assists" {{ old('letter_of_approval_loa_support', $property->letter_of_approval_loa_support ?? '') === 'Developer assists' ? 'selected' : '' }}>Developer assists</option>
                                <option value="Applicant arranges" {{ old('letter_of_approval_loa_support', $property->letter_of_approval_loa_support ?? '') === 'Applicant arranges' ? 'selected' : '' }}>Applicant arranges</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customs Bonding Status </label>
                            <select name="customs_bonding_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Bonded" {{ old('customs_bonding_status', $property->customs_bonding_status ?? '') === 'Bonded' ? 'selected' : '' }}>Bonded</option>
                                <option value="To be bonded" {{ old('customs_bonding_status', $property->customs_bonding_status ?? '') === 'To be bonded' ? 'selected' : '' }}>To be bonded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ready for Unit Approval Committee </label>
                            <select name="ready_for_unit_approval_committee" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('ready_for_unit_approval_committee', $property->ready_for_unit_approval_committee ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('ready_for_unit_approval_committee', $property->ready_for_unit_approval_committee ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duty-free Import Benefit </label>
                            <select name="duty_free_import_benefit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Applicable" {{ old('duty_free_import_benefit', $property->duty_free_import_benefit ?? '') === 'Applicable' ? 'selected' : '' }}>Applicable</option>
                                <option value="Not applicable" {{ old('duty_free_import_benefit', $property->duty_free_import_benefit ?? '') === 'Not applicable' ? 'selected' : '' }}>Not applicable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GST / Zero-rated Supply Eligible </label>
                            <select name="gst_zero_rated_supply_eligible" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('gst_zero_rated_supply_eligible', $property->gst_zero_rated_supply_eligible ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('gst_zero_rated_supply_eligible', $property->gst_zero_rated_supply_eligible ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title / Lease Type <span class="text-red-500">*</span></label>
                            <select name="title_lease_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Developer Leasehold" {{ old('title_lease_type', $property->title_lease_type ?? '') === 'Developer Leasehold' ? 'selected' : '' }}>Developer Leasehold</option>
                                <option value="Sub-lease" {{ old('title_lease_type', $property->title_lease_type ?? '') === 'Sub-lease' ? 'selected' : '' }}>Sub-lease</option>
                                <option value="Co-developer" {{ old('title_lease_type', $property->title_lease_type ?? '') === 'Co-developer' ? 'selected' : '' }}>Co-developer</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Sanctioned (KVA) </label>
                            <input type="text" name="power_sanctioned_kva" value="{{ old('power_sanctioned_kva', $property->power_sanctioned_kva ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup </label>
                            <select name="power_backup" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="100%" {{ old('power_backup', $property->power_backup ?? '') === '100%' ? 'selected' : '' }}>100%</option>
                                <option value="Partial" {{ old('power_backup', $property->power_backup ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="None" {{ old('power_backup', $property->power_backup ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Redundant Power / DG </label>
                            <select name="redundant_power_dg" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="N+1" {{ old('redundant_power_dg', $property->redundant_power_dg ?? '') === 'N+1' ? 'selected' : '' }}>N+1</option>
                                <option value="N" {{ old('redundant_power_dg', $property->redundant_power_dg ?? '') === 'N' ? 'selected' : '' }}>N</option>
                                <option value="None" {{ old('redundant_power_dg', $property->redundant_power_dg ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Air Conditioning </label>
                            <select name="air_conditioning" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Central" {{ old('air_conditioning', $property->air_conditioning ?? '') === 'Central' ? 'selected' : '' }}>Central</option>
                                <option value="VRV" {{ old('air_conditioning', $property->air_conditioning ?? '') === 'VRV' ? 'selected' : '' }}>VRV</option>
                                <option value="None" {{ old('air_conditioning', $property->air_conditioning ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telecom / Fibre Redundancy </label>
                            <select name="telecom_fibre_redundancy" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Dual" {{ old('telecom_fibre_redundancy', $property->telecom_fibre_redundancy ?? '') === 'Dual' ? 'selected' : '' }}>Dual</option>
                                <option value="Single" {{ old('telecom_fibre_redundancy', $property->telecom_fibre_redundancy ?? '') === 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="None" {{ old('telecom_fibre_redundancy', $property->telecom_fibre_redundancy ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fire Safety System </label>
                            <select name="fire_safety_system" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Sprinkler+Hydrant" {{ old('fire_safety_system', $property->fire_safety_system ?? '') === 'Sprinkler+Hydrant' ? 'selected' : '' }}>Sprinkler+Hydrant</option>
                                <option value="Basic" {{ old('fire_safety_system', $property->fire_safety_system ?? '') === 'Basic' ? 'selected' : '' }}>Basic</option>
                                <option value="None" {{ old('fire_safety_system', $property->fire_safety_system ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Common Facilities </label>
                            <select name="common_facilities[]" multiple class="select2-multiple w-full">
                                @php $sel = old('common_facilities', $property->common_facilities ?? []); @endphp
                                    <option value="Cafeteria" {{ in_array('Cafeteria', (array)$sel) ? 'selected' : '' }}>Cafeteria</option>
                                    <option value="Crèche" {{ in_array('Crèche', (array)$sel) ? 'selected' : '' }}>Crèche</option>
                                    <option value="Bank" {{ in_array('Bank', (array)$sel) ? 'selected' : '' }}>Bank</option>
                                    <option value="Transport" {{ in_array('Transport', (array)$sel) ? 'selected' : '' }}>Transport</option>
                                    <option value="Security" {{ in_array('Security', (array)$sel) ? 'selected' : '' }}>Security</option>
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
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes </label>
                            <textarea name="owner_flexibility_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property->owner_flexibility_notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION H — INVESTMENT / ROI  (if property is tenanted / pre-leased and being sold)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interior unit photos <span class="text-red-500">*</span></label>
                            <input type="text" name="interior_unit_photos" value="{{ old('interior_unit_photos', $property->interior_unit_photos ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
