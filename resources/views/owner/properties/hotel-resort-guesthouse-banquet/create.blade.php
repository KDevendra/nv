@extends('layouts.owner')
@php if (!isset($property)) { $property = null; } @endphp
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Hospitality — Hotel / Resort / Guest House - Owner Portal')

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
                {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Hospitality — Hotel / Resort / Guest House' }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Complete section-by-section submission form for Hospitality — Hotel / Resort / Guest House</p>
        </div>
        <a href="{{ route('owner.properties.select-type') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Choose Other Type
        </a>
    </div>

    

    <form method="POST" action="{{ isset($property) ? route('owner.properties.hotel-resort-guesthouse-banquet.update', $property) : route('owner.properties.hotel-resort-guesthouse-banquet.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property))
            @method('PUT')
        @endif

        <x-property-wizard-shell :steps="$steps" :title="'Hospitality — Hotel / Resort / Guest House Submission Form'" :propertyType="'hotel_resort_guesthouse_banquet'">
            
            <div class="wizard-step-content space-y-6" style="display:block">
                
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hotel/Resorts · Guest House/Banquet Halls · 99acres-aligned </label>
                            <input type="text" name="hotel_resorts_guest_house_banquet_halls_99acres_aligned" value="{{ old('hotel_resorts_guest_house_banquet_halls_99acres_aligned', $property?->fieldValue('hotel_resorts_guest_house_banquet_halls_99acres_aligned') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                        <h4 class="text-base font-bold text-zendo-navy">SECTION C — ASSET CONFIGURATION</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span class="text-red-500">*</span></label>
                            <select required  name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Hotel" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="Resort" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Resort' ? 'selected' : '' }}>Resort</option>
                                <option value="Guest House" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Guest House' ? 'selected' : '' }}>Guest House</option>
                                <option value="Banquet Hall" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Banquet Hall' ? 'selected' : '' }}>Banquet Hall</option>
                                <option value="Service Apartment block" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Service Apartment block' ? 'selected' : '' }}>Service Apartment block</option>
                                <option value="Restaurant space" {{ old('property_type', $property?->fieldValue('property_type') ?? '') === 'Restaurant space' ? 'selected' : '' }}>Restaurant space</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Star / Positioning <span class="text-red-500">*</span></label>
                            <select required  name="star_positioning" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Budget" {{ old('star_positioning', $property?->fieldValue('star_positioning') ?? '') === 'Budget' ? 'selected' : '' }}>Budget</option>
                                <option value="3-Star" {{ old('star_positioning', $property?->fieldValue('star_positioning') ?? '') === '3-Star' ? 'selected' : '' }}>3-Star</option>
                                <option value="4-Star" {{ old('star_positioning', $property?->fieldValue('star_positioning') ?? '') === '4-Star' ? 'selected' : '' }}>4-Star</option>
                                <option value="5-Star" {{ old('star_positioning', $property?->fieldValue('star_positioning') ?? '') === '5-Star' ? 'selected' : '' }}>5-Star</option>
                                <option value="Unbranded" {{ old('star_positioning', $property?->fieldValue('star_positioning') ?? '') === 'Unbranded' ? 'selected' : '' }}>Unbranded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Land / Plot Area (sq ft) </label>
                            <input type="number" step="any" name="total_land_plot_area_sq_ft" value="{{ old('total_land_plot_area_sq_ft', $property?->fieldValue('total_land_plot_area_sq_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Built-up Area (sq ft) <span class="text-red-500">*</span></label>
                            <input required  type="number" step="any" name="total_built_up_area_sq_ft" value="{{ old('total_built_up_area_sq_ft', $property?->fieldValue('total_built_up_area_sq_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Keys / Rooms <span class="text-red-500">*</span></label>
                            <input required  type="number" step="any" name="number_of_keys_rooms" value="{{ old('number_of_keys_rooms', $property?->fieldValue('number_of_keys_rooms') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of Floors </label>
                            <input type="number" step="any" name="number_of_floors" value="{{ old('number_of_floors', $property?->fieldValue('number_of_floors') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banquet / Event Space (sq ft) </label>
                            <input type="number" step="any" name="banquet_event_space_sq_ft" value="{{ old('banquet_event_space_sq_ft', $property?->fieldValue('banquet_event_space_sq_ft') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banquet Guest Capacity (pax) </label>
                            <input type="number" step="any" name="banquet_guest_capacity_pax" value="{{ old('banquet_guest_capacity_pax', $property?->fieldValue('banquet_guest_capacity_pax') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant / F&B Outlets </label>
                            <input type="number" step="any" name="restaurant_f_b_outlets" value="{{ old('restaurant_f_b_outlets', $property?->fieldValue('restaurant_f_b_outlets') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Parking Capacity </label>
                            <input type="number" step="any" name="parking_capacity" value="{{ old('parking_capacity', $property?->fieldValue('parking_capacity') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Operational Status <span class="text-red-500">*</span></label>
                            <select required  name="operational_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Operational" {{ old('operational_status', $property?->fieldValue('operational_status') ?? '') === 'Operational' ? 'selected' : '' }}>Operational</option>
                                <option value="Under-construction" {{ old('operational_status', $property?->fieldValue('operational_status') ?? '') === 'Under-construction' ? 'selected' : '' }}>Under-construction</option>
                                <option value="Closed" {{ old('operational_status', $property?->fieldValue('operational_status') ?? '') === 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Bare structure" {{ old('operational_status', $property?->fieldValue('operational_status') ?? '') === 'Bare structure' ? 'selected' : '' }}>Bare structure</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Existing Brand / Operator </label>
                            <input type="text" name="existing_brand_operator" value="{{ old('existing_brand_operator', $property?->fieldValue('existing_brand_operator') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing / FF&E Status </label>
                            <select name="furnishing_ff_e_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Fully fitted" {{ old('furnishing_ff_e_status', $property?->fieldValue('furnishing_ff_e_status') ?? '') === 'Fully fitted' ? 'selected' : '' }}>Fully fitted</option>
                                <option value="Partial" {{ old('furnishing_ff_e_status', $property?->fieldValue('furnishing_ff_e_status') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="Bare" {{ old('furnishing_ff_e_status', $property?->fieldValue('furnishing_ff_e_status') ?? '') === 'Bare' ? 'selected' : '' }}>Bare</option>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership / Title Type <span class="text-red-500">*</span></label>
                            <select required  name="ownership_title_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Freehold" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Freehold' ? 'selected' : '' }}>Freehold</option>
                                <option value="Leasehold-Govt" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Leasehold-Govt' ? 'selected' : '' }}>Leasehold-Govt</option>
                                <option value="Leasehold-Private" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Leasehold-Private' ? 'selected' : '' }}>Leasehold-Private</option>
                                <option value="Society" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Society' ? 'selected' : '' }}>Society</option>
                                <option value="Other" {{ old('ownership_title_type', $property?->fieldValue('ownership_title_type') ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span class="text-red-500">*</span></label>
                            <select required  name="title_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Clear" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                <option value="Under Dispute" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Under Dispute' ? 'selected' : '' }}>Under Dispute</option>
                                <option value="Encumbrance Being Resolved" {{ old('title_status', $property?->fieldValue('title_status') ?? '') === 'Encumbrance Being Resolved' ? 'selected' : '' }}>Encumbrance Being Resolved</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registered <span class="text-red-500">*</span></label>
                            <select required  name="rera_registered" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Not Applicable" {{ old('rera_registered', $property?->fieldValue('rera_registered') ?? '') === 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registration ID <span class="text-red-500">*</span></label>
                            <input required  type="text" name="rera_registration_id" value="{{ old('rera_registration_id', $property?->fieldValue('rera_registration_id') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Occupancy Certificate (OC) <span class="text-red-500">*</span></label>
                            <select required  name="occupancy_certificate_oc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Received" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Received' ? 'selected' : '' }}>Received</option>
                                <option value="Applied" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not Received" {{ old('occupancy_certificate_oc', $property?->fieldValue('occupancy_certificate_oc') ?? '') === 'Not Received' ? 'selected' : '' }}>Not Received</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CLU / Land Use Approval <span class="text-red-500">*</span></label>
                            <select required  name="clu_land_use_approval" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Commercial Approved" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Commercial Approved' ? 'selected' : '' }}>Commercial Approved</option>
                                <option value="Mixed-Use" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Mixed-Use' ? 'selected' : '' }}>Mixed-Use</option>
                                <option value="Conversion Applied" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Conversion Applied' ? 'selected' : '' }}>Conversion Applied</option>
                                <option value="Not Converted" {{ old('clu_land_use_approval', $property?->fieldValue('clu_land_use_approval') ?? '') === 'Not Converted' ? 'selected' : '' }}>Not Converted</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fire NOC Status <span class="text-red-500">*</span></label>
                            <select required  name="fire_noc_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied — In Process" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Applied — In Process' ? 'selected' : '' }}>Applied — In Process</option>
                                <option value="Not Yet Applied" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Not Yet Applied' ? 'selected' : '' }}>Not Yet Applied</option>
                                <option value="Not Required" {{ old('fire_noc_status', $property?->fieldValue('fire_noc_status') ?? '') === 'Not Required' ? 'selected' : '' }}>Not Required</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pollution / Environment NOC </label>
                            <select name="pollution_environment_noc" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Obtained" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Obtained' ? 'selected' : '' }}>Obtained</option>
                                <option value="Applied" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="Not Applicable" {{ old('pollution_environment_noc', $property?->fieldValue('pollution_environment_noc') ?? '') === 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Loan on Property <span class="text-red-500">*</span></label>
                            <select required  name="encumbrance_loan_on_property" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="None" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                                <option value="Loan" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Loan' ? 'selected' : '' }}>Loan</option>
                                <option value="Mortgage" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Mortgage' ? 'selected' : '' }}>Mortgage</option>
                                <option value="Other" {{ old('encumbrance_loan_on_property', $property?->fieldValue('encumbrance_loan_on_property') ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION E — LICENCES & INFRASTRUCTURE</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hotel / Hospitality Licence </label>
                            <select name="hotel_hospitality_licence" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Held" {{ old('hotel_hospitality_licence', $property?->fieldValue('hotel_hospitality_licence') ?? '') === 'Held' ? 'selected' : '' }}>Held</option>
                                <option value="Applied" {{ old('hotel_hospitality_licence', $property?->fieldValue('hotel_hospitality_licence') ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                <option value="None" {{ old('hotel_hospitality_licence', $property?->fieldValue('hotel_hospitality_licence') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Liquor Licence </label>
                            <select name="liquor_licence" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('liquor_licence', $property?->fieldValue('liquor_licence') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('liquor_licence', $property?->fieldValue('liquor_licence') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Transferable" {{ old('liquor_licence', $property?->fieldValue('liquor_licence') ?? '') === 'Transferable' ? 'selected' : '' }}>Transferable</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup </label>
                            <select name="power_backup" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="100%" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === '100%' ? 'selected' : '' }}>100%</option>
                                <option value="Partial" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="None" {{ old('power_backup', $property?->fieldValue('power_backup') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sanctioned Power Load (KVA) </label>
                            <input type="text" name="sanctioned_power_load_kva" value="{{ old('sanctioned_power_load_kva', $property?->fieldValue('sanctioned_power_load_kva') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Water Source / STP </label>
                            <input type="text" name="water_source_stp" value="{{ old('water_source_stp', $property?->fieldValue('water_source_stp') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lifts </label>
                            <input type="number" step="any" name="lifts" value="{{ old('lifts', $property?->fieldValue('lifts') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kitchen Infrastructure </label>
                            <select name="kitchen_infrastructure" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Full commercial" {{ old('kitchen_infrastructure', $property?->fieldValue('kitchen_infrastructure') ?? '') === 'Full commercial' ? 'selected' : '' }}>Full commercial</option>
                                <option value="Basic" {{ old('kitchen_infrastructure', $property?->fieldValue('kitchen_infrastructure') ?? '') === 'Basic' ? 'selected' : '' }}>Basic</option>
                                <option value="None" {{ old('kitchen_infrastructure', $property?->fieldValue('kitchen_infrastructure') ?? '') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amenities </label>
                            <select name="amenities[]" multiple class="select2-multiple w-full">
                                @php $sel = old('amenities', $property?->fieldValue('amenities') ?? []); @endphp
                                    <option value="Pool" {{ in_array('Pool', (array)$sel) ? 'selected' : '' }}>Pool</option>
                                    <option value="Spa" {{ in_array('Spa', (array)$sel) ? 'selected' : '' }}>Spa</option>
                                    <option value="Gym" {{ in_array('Gym', (array)$sel) ? 'selected' : '' }}>Gym</option>
                                    <option value="Conference" {{ in_array('Conference', (array)$sel) ? 'selected' : '' }}>Conference</option>
                                    <option value="Bar" {{ in_array('Bar', (array)$sel) ? 'selected' : '' }}>Bar</option>
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
                                <option value="Management Contract" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Management Contract' ? 'selected' : '' }}>Management Contract</option>
                                <option value="Revenue Share" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Revenue Share' ? 'selected' : '' }}>Revenue Share</option>
                                <option value="Slump Sale" {{ old('listing_purpose_transaction_type', $property?->fieldValue('listing_purpose_transaction_type') ?? '') === 'Slump Sale' ? 'selected' : '' }}>Slump Sale</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹) </label>
                            <input type="number" step="any" name="expected_sale_price" value="{{ old('expected_sale_price', $property?->fieldValue('expected_sale_price') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price Band (shown live) </label>
                            <select name="sale_price_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('sale_price_band_shown_live', $property?->fieldValue('sale_price_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Lease Rent (₹/month) </label>
                            <input type="number" step="any" name="expected_lease_rent_month" value="{{ old('expected_lease_rent_month', $property?->fieldValue('expected_lease_rent_month') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Revenue / ARR / Occupancy % </label>
                            <input type="text" name="current_revenue_arr_occupancy" value="{{ old('current_revenue_arr_occupancy', $property?->fieldValue('current_revenue_arr_occupancy') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                <div class="border-t pt-4 first:border-t-0 first:pt-0">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION H — INVESTMENT / ROI  (if property is tenanted / pre-leased and being sold)</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currently Rented / Tenanted <span class="text-red-500">*</span></label>
                            <select required  name="currently_rented_tenanted" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                <option value="Partially" {{ old('currently_rented_tenanted', $property?->fieldValue('currently_rented_tenanted') ?? '') === 'Partially' ? 'selected' : '' }}>Partially</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Monthly Rent Received (₹) </label>
                            <input type="number" step="any" name="current_monthly_rent_received" value="{{ old('current_monthly_rent_received', $property?->fieldValue('current_monthly_rent_received') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rental Income Band (shown live) </label>
                            <select name="rental_income_band_shown_live" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Yes" {{ old('rental_income_band_shown_live', $property?->fieldValue('rental_income_band_shown_live') ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('rental_income_band_shown_live', $property?->fieldValue('rental_income_band_shown_live') ?? '') === 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rental Yield / ROI (% p.a.) </label>
                            <input type="text" name="rental_yield_roi_p_a" value="{{ old('rental_yield_roi_p_a', $property?->fieldValue('rental_yield_roi_p_a') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Type (public) </label>
                            <select name="tenant_type_public" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
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
                            <input type="number" step="any" name="lease_tenure_years" value="{{ old('lease_tenure_years', $property?->fieldValue('lease_tenure_years') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Remaining (months) </label>
                            <input type="number" step="any" name="lock_in_remaining_months" value="{{ old('lock_in_remaining_months', $property?->fieldValue('lock_in_remaining_months') ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CAM / Outgoings Borne By </label>
                            <select name="cam_outgoings_borne_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select —</option>
                                <option value="Tenant" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Tenant' ? 'selected' : '' }}>Tenant</option>
                                <option value="Owner" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Owner' ? 'selected' : '' }}>Owner</option>
                                <option value="Shared" {{ old('cam_outgoings_borne_by', $property?->fieldValue('cam_outgoings_borne_by') ?? '') === 'Shared' ? 'selected' : '' }}>Shared</option>
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
