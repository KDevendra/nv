@extends('layouts.owner')
@php if (!isset($property)) { $property = null; } @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Service Apartment / Co-living / PG - Owner Portal')

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
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Service Apartment / Co-living / PG' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Service Apartment / Co-living / PG</p>
        </div>
        <a href="{{ route('owner.properties.select-type') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Choose Other Type
        </a>
    </div>

    

    <form method="POST" action="{{ isset($property) ? route('owner.properties.service-apartment-pg.update', $property) : route('owner.properties.service-apartment-pg.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'Service Apartment / Co-living / PG Submission Form'" :propertyType="'service_apartment_pg'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
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
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — UNIT & OCCUPANCY</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select required  name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Service Apartment" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Service Apartment' ? 'selected' : '' }}>Service Apartment</option>
                                <option value="Co-living" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Co-living' ? 'selected' : '' }}>Co-living</option>
                                <option value="PG" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'PG' ? 'selected' : '' }}>PG</option>
                                <option value="Hostel" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Hostel' ? 'selected' : '' }}>Hostel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room / Occupancy Type <span class="text-red-500">*</span></label>
                            <select required  name="room_occupancy_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Private" {{ old('room_occupancy_type', $property?->fieldValue('room_occupancy_type') ?? '') === 'Private' ? 'selected' : '' }}>Private</option>
                                <option value="Twin" {{ old('room_occupancy_type', $property?->fieldValue('room_occupancy_type') ?? '') === 'Twin' ? 'selected' : '' }}>Twin</option>
                                <option value="Triple" {{ old('room_occupancy_type', $property?->fieldValue('room_occupancy_type') ?? '') === 'Triple' ? 'selected' : '' }}>Triple</option>
                                <option value="Dorm" {{ old('room_occupancy_type', $property?->fieldValue('room_occupancy_type') ?? '') === 'Dorm' ? 'selected' : '' }}>Dorm</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Rooms / Beds Available <span class="text-red-500">*</span></label>
                            <input required  type="number" step="any" name="total_rooms_beds_available" value="{{ old('total_rooms_beds_available', $property?->fieldValue('total_rooms_beds_available') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carpet Area per Unit (sq ft) </label>
                            <input type="number" step="any" name="carpet_area_per_unit_sq_ft" value="{{ old('carpet_area_per_unit_sq_ft', $property?->fieldValue('carpet_area_per_unit_sq_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing Status <span class="text-red-500">*</span></label>
                            <select required  name="furnishing_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Fully furnished" {{ old('furnishing_status', $property?->fieldValue('furnishing_status') ?? '') === 'Fully furnished' ? 'selected' : '' }}>Fully furnished</option>
                                <option value="Semi" {{ old('furnishing_status', $property?->fieldValue('furnishing_status') ?? '') === 'Semi' ? 'selected' : '' }}>Semi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Attached Bathroom </label>
                            <select name="attached_bathroom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('attached_bathroom', $property?->fieldValue('attached_bathroom') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="Shared" {{ old('attached_bathroom', $property?->fieldValue('attached_bathroom') ?? '') === 'Shared' ? 'selected' : '' }}>Shared</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Floor / Total Floors </label>
                            <input type="text" name="floor_total_floors" value="{{ old('floor_total_floors', $property?->fieldValue('floor_total_floors') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender Policy </label>
                            <select name="gender_policy" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Male" {{ old('gender_policy', $property?->fieldValue('gender_policy') ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender_policy', $property?->fieldValue('gender_policy') ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Co-ed" {{ old('gender_policy', $property?->fieldValue('gender_policy') ?? '') === 'Co-ed' ? 'selected' : '' }}>Co-ed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Occupant </label>
                            <select name="target_occupant" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Students" {{ old('target_occupant', $property?->fieldValue('target_occupant') ?? '') === 'Students' ? 'selected' : '' }}>Students</option>
                                <option value="Working Professionals" {{ old('target_occupant', $property?->fieldValue('target_occupant') ?? '') === 'Working Professionals' ? 'selected' : '' }}>Working Professionals</option>
                                <option value="Any" {{ old('target_occupant', $property?->fieldValue('target_occupant') ?? '') === 'Any' ? 'selected' : '' }}>Any</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wizard-step-content space-y-6" style="display:none">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION E — SERVICES & AMENITIES</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meals Included </label>
                            <select name="meals_included" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('meals_included', $property?->fieldValue('meals_included') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Breakfast" {{ old('meals_included', $property?->fieldValue('meals_included') ?? '') === 'Breakfast' ? 'selected' : '' }}>Breakfast</option>
                                <option value="All meals" {{ old('meals_included', $property?->fieldValue('meals_included') ?? '') === 'All meals' ? 'selected' : '' }}>All meals</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Housekeeping </label>
                            <select name="housekeeping" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Daily" {{ old('housekeeping', $property?->fieldValue('housekeeping') ?? '') === 'Daily' ? 'selected' : '' }}>Daily</option>
                                <option value="Weekly" {{ old('housekeeping', $property?->fieldValue('housekeeping') ?? '') === 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="None" {{ old('housekeeping', $property?->fieldValue('housekeeping') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Laundry </label>
                            <select name="laundry" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Included" {{ old('laundry', $property?->fieldValue('laundry') ?? '') === 'Included' ? 'selected' : '' }}>Included</option>
                                <option value="Paid" {{ old('laundry', $property?->fieldValue('laundry') ?? '') === 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="None" {{ old('laundry', $property?->fieldValue('laundry') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WiFi </label>
                            <select name="wifi" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('wifi', $property?->fieldValue('wifi') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('wifi', $property?->fieldValue('wifi') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup </label>
                            <select name="power_backup" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Full" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Full' ? 'selected' : '' }}>Full</option>
                                <option value="Partial" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="None" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">AC Rooms </label>
                            <select name="ac_rooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('ac_rooms', $property?->fieldValue('ac_rooms') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('ac_rooms', $property?->fieldValue('ac_rooms') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Some" {{ old('ac_rooms', $property?->fieldValue('ac_rooms') ?? '') === 'Some' ? 'selected' : '' }}>Some</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Common Amenities </label>
                            <select name="common_amenities[]" multiple class="select2-multiple w-full">
                                @php $sel = old('common_amenities', $property?->fieldValue('common_amenities') ?? []); @endphp
                                    <option value="Kitchen" {{ in_array('Kitchen', (array)$sel) ? 'selected' : '' }}>Kitchen</option>
                                    <option value="Lounge" {{ in_array('Lounge', (array)$sel) ? 'selected' : '' }}>Lounge</option>
                                    <option value="Gym" {{ in_array('Gym', (array)$sel) ? 'selected' : '' }}>Gym</option>
                                    <option value="Study" {{ in_array('Study', (array)$sel) ? 'selected' : '' }}>Study</option>
                                    <option value="TV" {{ in_array('TV', (array)$sel) ? 'selected' : '' }}>TV</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Security / CCTV </label>
                            <select name="security_cctv" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="24x7" {{ old('security_cctv', $property?->fieldValue('security_cctv') ?? '') === '24x7' ? 'selected' : '' }}>24x7</option>
                                <option value="Partial" {{ old('security_cctv', $property?->fieldValue('security_cctv') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="None" {{ old('security_cctv', $property?->fieldValue('security_cctv') ?? '') === 'None' ? 'selected' : '' }}>None</option>
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
                                <option value="Rent per bed" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Rent per bed' ? 'selected' : '' }}>Rent per bed</option>
                                <option value="Whole property lease" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Whole property lease' ? 'selected' : '' }}>Whole property lease</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rent per Bed / Room (₹/month) <span class="text-red-500">*</span></label>
                            <input required  type="number" step="any" name="rent_per_bed_room_month" value="{{ old('rent_per_bed_room_month', $property?->fieldValue('rent_per_bed_room_month') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rent Band (shown live) <span class="text-red-500">*</span></label>
                            <select required  name="rent_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('rent_band_shown_live', $property?->fieldValue('rent_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('rent_band_shown_live', $property?->fieldValue('rent_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit <span class="text-red-500">*</span></label>
                            <select required  name="security_deposit_months" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="1" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '1' ? 'selected' : '' }}>1</option>
                                <option value="2 months" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === '2 months' ? 'selected' : '' }}>2 months</option>
                                <option value="Fixed" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="Negotiable" {{ old('security_deposit_months', $property?->fieldValue('security_deposit_months') ?? '') === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in / Min Stay (months) </label>
                            <input type="number" step="any" name="lock_in_min_stay_months" value="{{ old('lock_in_min_stay_months', $property?->fieldValue('lock_in_min_stay_months') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance / Service Charge </label>
                            <input type="number" step="any" name="maintenance_service_charge" value="{{ old('maintenance_service_charge', $property?->fieldValue('maintenance_service_charge') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price </label>
                            <input type="number" step="any" name="negotiable_floor_price" value="{{ old('negotiable_floor_price', $property?->fieldValue('negotiable_floor_price') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
