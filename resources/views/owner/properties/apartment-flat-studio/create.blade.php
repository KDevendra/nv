@extends('layouts.owner')
@section('title', (isset($property) ? 'Edit' : 'List New') . ' Apartment / Flat / Studio - Owner Portal')

@section('content')
    @php
        $apartmentSteps = [
            ['key' => 'A', 'title' => 'Submitter & Owner'],
            ['key' => 'B', 'title' => 'Location & Project'],
            ['key' => 'C', 'title' => 'Unit & Possession'],
            ['key' => 'D', 'title' => 'Legal & Amenities'],
            ['key' => 'E', 'title' => 'Commercials & ROI'],
            ['key' => 'F', 'title' => 'Photos & Remarks'],
        ];
    @endphp

    <div class="max-w-5xl mx-auto space-y-6" x-data="apartmentWizard()">

        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">
                    {{ isset($property) ? 'Edit Listing: ' . $property->code : 'List Apartment / Flat / Studio' }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Complete section-by-section specification form for Residential
                    Apartment / Flat / Studio</p>
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
            action="{{ isset($property) ? route('owner.properties.apartment-flat-studio.update', $property) : route('owner.properties.apartment-flat-studio.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif

            <x-property-wizard-shell :steps="$apartmentSteps" :title="'Apartment / Flat / Studio Submission Form'"
                :propertyType="'apartment_flat_studio'">

                {{-- Step 0 — SECTION A: SUBMITTER & OWNER DETAILS --}}
                <div class="wizard-step-content space-y-4" style="display:block">
                    <div class="border-b pb-2 mb-4">
                        <h4 class="text-base font-bold text-zendo-navy">SECTION A — SUBMITTER & OWNER DETAILS</h4>
                        <p class="text-xs text-gray-500">Stored internally · Never published</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Full Name <span
                                    class="text-red-500">*</span></label>
                            <input required type="text" name="submitter_full_name"
                                value="{{ old('submitter_full_name', $property->submitter_full_name ?? auth()->user()->name) }}"
                                required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Phone <span
                                    class="text-red-500">*</span></label>
                            <input required type="text" name="submitter_phone"
                                value="{{ old('submitter_phone', $property->submitter_phone ?? auth()->user()->phone) }}"
                                required placeholder="10-digit mobile"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Email <span
                                    class="text-red-500">*</span></label>
                            <input required type="email" name="submitter_email"
                                value="{{ old('submitter_email', $property->submitter_email ?? auth()->user()->email) }}"
                                required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Role <span
                                    class="text-red-500">*</span></label>
                            <select name="submitter_role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <option value="">— Select Role —</option>
                                @foreach(['Owner', 'Builder', 'Authorised Agent', 'Broker', 'GPA holder'] as $role)
                                    <option value="{{ $role }}" {{ old('submitter_role', $property->submitter_role ?? 'Owner') === $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company / Entity Name</label>
                            <input type="text" name="company_entity_name"
                                value="{{ old('company_entity_name', $property->company_entity_name ?? '') }}"
                                placeholder="Company or entity name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Contact Name <span
                                    class="text-red-500">*</span></label>
                            <input required type="text" name="owner_contact_name"
                                value="{{ old('owner_contact_name', $property->owner_contact_name ?? auth()->user()->name) }}"
                                required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Contact Number <span
                                    class="text-red-500">*</span></label>
                            <input required type="text" name="owner_contact_phone"
                                value="{{ old('owner_contact_phone', $property->owner_contact_phone ?? auth()->user()->phone) }}"
                                required placeholder="10-digit mobile"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Email</label>
                            <input type="email" name="owner_email"
                                value="{{ old('owner_email', $property->owner_email ?? '') }}"
                                placeholder="Owner email address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                </div>

                {{-- Step 1 — SECTION B & SECTION B2: LOCATION, IDENTIFICATION & PROJECT/SOCIETY --}}
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div>
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION B — LOCATION & IDENTIFICATION</h4>
                            <p class="text-xs text-gray-500">Property location, coordinates & orientation</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PIN Code <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="postal_address_pin"
                                    value="{{ old('postal_address_pin', $property->postal_address_pin ?? '') }}" required
                                    maxlength="6" placeholder="6-digit PIN"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="city" value="{{ old('city', $property->city ?? '') }}"
                                    required placeholder="e.g. Mumbai, Gurgaon, Delhi"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Locality / Broad Area <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="locality_broad_area"
                                    value="{{ old('locality_broad_area', $property->locality_broad_area ?? '') }}" required
                                    placeholder="e.g. Bandra West, DLF Phase 5"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sub-locality / Society
                                    Name</label>
                                <input type="text" name="sub_locality_society_name"
                                    value="{{ old('sub_locality_society_name', $property->sub_locality_society_name ?? '') }}"
                                    placeholder="Sub-locality or society"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                                <input type="text" name="project_name"
                                    value="{{ old('project_name', $property->project_name ?? '') }}"
                                    placeholder="Project name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Builder / Developer Name</label>
                                <input type="text" name="builder_developer_name"
                                    value="{{ old('builder_developer_name', $property->builder_developer_name ?? '') }}"
                                    placeholder="Developer name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="state" value="{{ old('state', $property->state ?? '') }}"
                                    required placeholder="State"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GPS Latitude <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="0.000001" name="gps_latitude"
                                    value="{{ old('gps_latitude', $property->gps_latitude ?? '') }}" required
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
                                    value="{{ old('gps_longitude', $property->gps_longitude ?? '') }}" required
                                    placeholder="e.g. 77.026634"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Address (House/Plot No.,
                                    Street) <span class="text-red-500">*</span></label>
                                <textarea name="name_full_address" required rows="2"
                                    placeholder="Full postal street address"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('name_full_address', $property->name_full_address ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nearby Landmarks</label>
                                <input type="text" name="nearby_landmarks"
                                    value="{{ old('nearby_landmarks', $property->nearby_landmarks ?? '') }}"
                                    placeholder="Key landmarks nearby"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Distance from Key
                                    Locations</label>
                                <input type="text" name="distance_from_key_locations"
                                    value="{{ old('distance_from_key_locations', $property->distance_from_key_locations ?? '') }}"
                                    placeholder="Distance from Metro/School/Hospital"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Facing / Orientation</label>
                                <select name="facing_orientation"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['N' => 'North', 'E' => 'East', 'W' => 'West', 'S' => 'South', 'NE' => 'North-East', 'NW' => 'North-West', 'SE' => 'South-East', 'SW' => 'South-West'] as $dir => $label)
                                        <option value="{{ $dir }}" {{ old('facing_orientation', $property->facing_orientation ?? '') === $dir ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Overlooking / View</label>
                                <select name="overlooking_view[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedOverlooking = old('overlooking_view', $property->overlooking_view ?? []);
                                    @endphp
                                    @foreach(['Main Road', 'Park-Garden', 'Pool', 'Club', 'Others'] as $vw)
                                        <option value="{{ $vw }}" {{ in_array($vw, $selectedOverlooking) ? 'selected' : '' }}>
                                            {{ $vw }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION B2 --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION B2 — PROJECT / SOCIETY</h4>
                            <p class="text-xs text-gray-500">If unit is part of a builder project</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Part of a Project / Society?
                                    <span class="text-red-500">*</span></label>
                                <select name="part_of_a_project_society" required x-model="isPartOfProject"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Yes" {{ old('part_of_a_project_society', $property->part_of_a_project_society ?? 'Yes') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ old('part_of_a_project_society', $property->part_of_a_project_society ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project / Society Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="project_society_name"
                                    value="{{ old('project_society_name', $property->project_society_name ?? '') }}"
                                    placeholder="Society name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project RERA ID <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="project_rera_id"
                                    value="{{ old('project_rera_id', $property->project_rera_id ?? '') }}"
                                    placeholder="RERA ID"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Developer / Builder Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="developer_builder_name"
                                    value="{{ old('developer_builder_name', $property->developer_builder_name ?? '') }}"
                                    placeholder="Developer name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Towers / Blocks</label>
                                <input type="number" name="total_towers_blocks"
                                    value="{{ old('total_towers_blocks', $property->total_towers_blocks ?? '') }}" min="0"
                                    placeholder="Total towers"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Units in Project</label>
                                <input type="number" name="total_units_in_project"
                                    value="{{ old('total_units_in_project', $property->total_units_in_project ?? '') }}"
                                    min="0" placeholder="Total units"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved Loan Banks</label>
                                <input type="text" name="approved_loan_banks"
                                    value="{{ old('approved_loan_banks', $property->approved_loan_banks ?? '') }}"
                                    placeholder="SBI, HDFC, ICICI..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Configurations Offered</label>
                                <select name="configurations_offered[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedConfigs = old('configurations_offered', $property->configurations_offered ?? []);
                                    @endphp
                                    @foreach(['1', '2', '3', '4', '4+ BHK', 'Studio', 'Shop', 'Office'] as $cfgOpt)
                                        <option value="{{ $cfgOpt }}" {{ in_array($cfgOpt, $selectedConfigs) ? 'selected' : '' }}>{{ $cfgOpt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isPartOfProject === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project Amenities</label>
                                <select name="project_amenities[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedProjAmen = old('project_amenities', $property->project_amenities ?? []);
                                    @endphp
                                    @foreach(['Clubhouse', 'Pool', 'Gym', 'Park', 'Sports', 'Power backup'] as $pam)
                                        <option value="{{ $pam }}" {{ in_array($pam, $selectedProjAmen) ? 'selected' : '' }}>
                                            {{ $pam }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2 — SECTION C & SECTION C2: UNIT CONFIGURATION, TRANSACTION & POSSESSION --}}
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div>
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C — UNIT CONFIGURATION</h4>
                            <p class="text-xs text-gray-500">Unit type, area specs, floor & furnishing details</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type <span
                                        class="text-red-500">*</span></label>
                                <select name="unit_property_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Apartment', 'Flat', 'Studio Apartment', 'Penthouse', 'Duplex'] as $t)
                                        <option value="{{ $t }}" {{ old('unit_property_type', $property->unit_property_type ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Configuration (BHK) <span
                                        class="text-red-500">*</span></label>
                                <select name="configuration" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['1RK', '1', '2', '3', '4', '4+ BHK'] as $cfg)
                                        <option value="{{ $cfg }}" {{ old('configuration', $property->configuration ?? '') === $cfg ? 'selected' : '' }}>{{ $cfg }} BHK</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Carpet Area (sq ft) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" step="0.01" name="carpet_area"
                                    value="{{ old('carpet_area', $property->carpet_area ?? '') }}" required
                                    placeholder="e.g. 850"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Built-up Area (sq ft)</label>
                                <input type="number" step="0.01" name="built_up_area"
                                    value="{{ old('built_up_area', $property->built_up_area ?? '') }}"
                                    placeholder="e.g. 1100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Super Built-up Area (sq
                                    ft)</label>
                                <input type="number" step="0.01" name="super_built_up_area"
                                    value="{{ old('super_built_up_area', $property->super_built_up_area ?? '') }}"
                                    placeholder="e.g. 1350"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Floor Number <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="floor_number"
                                    value="{{ old('floor_number', $property->floor_number ?? '') }}" required min="0"
                                    max="999" placeholder="0 = Ground"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Floors in Building <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="number_of_floors"
                                    value="{{ old('number_of_floors', $property->number_of_floors ?? '') }}" required
                                    min="0" max="999" placeholder="Total floors"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Units on This Floor</label>
                                <input type="number" name="units_on_this_floor"
                                    value="{{ old('units_on_this_floor', $property->units_on_this_floor ?? '') }}" min="0"
                                    max="999" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Bedrooms <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="no_of_bedrooms"
                                    value="{{ old('no_of_bedrooms', $property->no_of_bedrooms ?? '') }}" required min="0"
                                    max="999" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Bathrooms <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="no_of_bathrooms"
                                    value="{{ old('no_of_bathrooms', $property->no_of_bathrooms ?? '') }}" required min="0"
                                    max="999" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. of Balconies</label>
                                <input type="number" name="no_of_balconies"
                                    value="{{ old('no_of_balconies', $property->no_of_balconies ?? '') }}" min="0" max="999"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Rooms</label>
                                <select name="additional_rooms[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedAddRooms = old('additional_rooms', $property->additional_rooms ?? []);
                                    @endphp
                                    @foreach(['Pooja', 'Study', 'Servant', 'Store'] as $ar)
                                        <option value="{{ $ar }}" {{ in_array($ar, $selectedAddRooms) ? 'selected' : '' }}>
                                            {{ $ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing Status <span
                                        class="text-red-500">*</span></label>
                                <select name="furnishing_status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Unfurnished', 'Semi', 'Fully furnished'] as $f)
                                        <option value="{{ $f }}" {{ old('furnishing_status', $property->furnishing_status ?? '') === $f ? 'selected' : '' }}>{{ $f }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Furnishing Details</label>
                                <select name="furnishing_detail[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedFurnDetail = old('furnishing_detail', $property->furnishing_detail ?? []);
                                    @endphp
                                    @foreach(['wardrobes', 'modular kitchen', 'ACs', 'appliances'] as $fd)
                                        <option value="{{ $fd }}" {{ in_array($fd, $selectedFurnDetail) ? 'selected' : '' }}>
                                            {{ $fd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Covered Parking Slots</label>
                                <input type="number" name="covered_parking_slots"
                                    value="{{ old('covered_parking_slots', $property->covered_parking_slots ?? '') }}"
                                    min="0" max="999" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Open Parking Slots</label>
                                <input type="number" name="open_parking_slots"
                                    value="{{ old('open_parking_slots', $property->open_parking_slots ?? '') }}" min="0"
                                    max="999" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION C2 --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION C2 — TRANSACTION & POSSESSION</h4>
                            <p class="text-xs text-gray-500">Property status, age, construction & availability timeline</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Property Status <span
                                        class="text-red-500">*</span></label>
                                <select name="property_status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="New" {{ old('property_status', $property->property_status ?? 'New') === 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Resale" {{ old('property_status', $property->property_status ?? '') === 'Resale' ? 'selected' : '' }}>Resale</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Construction / Listing Status
                                    <span class="text-red-500">*</span></label>
                                <select name="construction_listing_status" required x-model="constrStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Ready to Move" {{ old('construction_listing_status', $property->construction_listing_status ?? 'Ready to Move') === 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                                    <option value="Under Construction" {{ old('construction_listing_status', $property->construction_listing_status ?? '') === 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                                    <option value="New Launch" {{ old('construction_listing_status', $property->construction_listing_status ?? '') === 'New Launch' ? 'selected' : '' }}>
                                        New Launch</option>
                                    <option value="Pre-launch" {{ old('construction_listing_status', $property->construction_listing_status ?? '') === 'Pre-launch' ? 'selected' : '' }}>
                                        Pre-launch</option>
                                </select>
                            </div>
                            <div x-show="constrStatus === 'Under Construction'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Possession By Date <span
                                        class="text-red-500">*</span></label>
                                <input required type="date" name="possession_by"
                                    value="{{ old('possession_by', isset($property->possession_by) ? $property->possession_by->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age of Property</label>
                                <select name="age_of_property"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['<1', '1-5', '5-10', '10+ yrs'] as $age)
                                        <option value="{{ $age }}" {{ old('age_of_property', $property->age_of_property ?? '') === $age ? 'selected' : '' }}>{{ $age }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Availability <span
                                        class="text-red-500">*</span></label>
                                <select name="availability" required x-model="availability"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Immediate" {{ old('availability', $property->availability ?? 'Immediate') === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                                    <option value="From date" {{ old('availability', $property->availability ?? '') === 'From date' ? 'selected' : '' }}>From date</option>
                                </select>
                            </div>
                            <div x-show="availability === 'From date'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Available From Date <span
                                        class="text-red-500">*</span></label>
                                <input required type="date" name="available_from"
                                    value="{{ old('available_from', isset($property->available_from) ? $property->available_from->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Loan / EMI
                                    Available</label>
                                <select name="bank_loan_emi_available"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes — approved banks" {{ old('bank_loan_emi_available', $property->bank_loan_emi_available ?? '') === 'Yes — approved banks' ? 'selected' : '' }}>Yes — approved banks</option>
                                    <option value="No" {{ old('bank_loan_emi_available', $property->bank_loan_emi_available ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3 — SECTION D & SECTION E: LEGAL, COMPLIANCE, SOCIETY & BUILDING AMENITIES --}}
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div>
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION D — LEGAL & COMPLIANCE</h4>
                            <p class="text-xs text-gray-500">Ownership, RERA, certificates & encumbrance status</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ownership Type <span
                                        class="text-red-500">*</span></label>
                                <select name="ownership_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Freehold', 'Leasehold', 'GPA', 'Co-op Society'] as $o)
                                        <option value="{{ $o }}" {{ old('ownership_type', $property->ownership_type ?? '') === $o ? 'selected' : '' }}>{{ $o }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title Status <span
                                        class="text-red-500">*</span></label>
                                <select name="title_status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Clear" {{ old('title_status', $property->title_status ?? 'Clear') === 'Clear' ? 'selected' : '' }}>Clear</option>
                                    <option value="Under Dispute" {{ old('title_status', $property->title_status ?? '') === 'Under Dispute' ? 'selected' : '' }}>Under Dispute</option>
                                    <option value="Encumbrance Being Resolved" {{ old('title_status', $property->title_status ?? '') === 'Encumbrance Being Resolved' ? 'selected' : '' }}>
                                        Encumbrance Being Resolved</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registered <span
                                        class="text-red-500">*</span></label>
                                <select name="rera_registered" required x-model="reraReg"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Yes" {{ old('rera_registered', $property->rera_registered ?? 'Yes') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('rera_registered', $property->rera_registered ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Not Applicable" {{ old('rera_registered', $property->rera_registered ?? '') === 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                            </div>
                            <div x-show="reraReg === 'Yes'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">RERA Registration ID <span
                                        class="text-red-500">*</span></label>
                                <input required type="text" name="rera_registration_id"
                                    value="{{ old('rera_registration_id', $property->rera_registration_id ?? '') }}"
                                    placeholder="RERA reg number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Occupancy Certificate (OC) <span
                                        class="text-red-500">*</span></label>
                                <select name="occupancy_certificate" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Received" {{ old('occupancy_certificate', $property->occupancy_certificate ?? 'Received') === 'Received' ? 'selected' : '' }}>
                                        Received</option>
                                    <option value="Applied" {{ old('occupancy_certificate', $property->occupancy_certificate ?? '') === 'Applied' ? 'selected' : '' }}>Applied</option>
                                    <option value="Not Received" {{ old('occupancy_certificate', $property->occupancy_certificate ?? '') === 'Not Received' ? 'selected' : '' }}>Not
                                        Received</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Completion Certificate
                                    (CC)</label>
                                <select name="completion_certificate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Received" {{ old('completion_certificate', $property->completion_certificate ?? '') === 'Received' ? 'selected' : '' }}>Received
                                    </option>
                                    <option value="Applied" {{ old('completion_certificate', $property->completion_certificate ?? '') === 'Applied' ? 'selected' : '' }}>Applied
                                    </option>
                                    <option value="Not Received" {{ old('completion_certificate', $property->completion_certificate ?? '') === 'Not Received' ? 'selected' : '' }}>Not
                                        Received</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Encumbrance / Loan on Property
                                    <span class="text-red-500">*</span></label>
                                <select name="encumbrance_loan_on_property" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="None" {{ old('encumbrance_loan_on_property', $property->encumbrance_loan_on_property ?? 'None') === 'None' ? 'selected' : '' }}>
                                        None</option>
                                    <option value="Home Loan" {{ old('encumbrance_loan_on_property', $property->encumbrance_loan_on_property ?? '') === 'Home Loan' ? 'selected' : '' }}>
                                        Home Loan</option>
                                    <option value="Mortgage" {{ old('encumbrance_loan_on_property', $property->encumbrance_loan_on_property ?? '') === 'Mortgage' ? 'selected' : '' }}>
                                        Mortgage</option>
                                    <option value="Other" {{ old('encumbrance_loan_on_property', $property->encumbrance_loan_on_property ?? '') === 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Khata / Property Tax
                                    Status</label>
                                <select name="khata_property_tax_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Up to date" {{ old('khata_property_tax_status', $property->khata_property_tax_status ?? '') === 'Up to date' ? 'selected' : '' }}>Up
                                        to date</option>
                                    <option value="Pending" {{ old('khata_property_tax_status', $property->khata_property_tax_status ?? '') === 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION E --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION E — SOCIETY & BUILDING AMENITIES</h4>
                            <p class="text-xs text-gray-500">Power backup, water supply, security & amenities checklist</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lift / Elevator <span
                                        class="text-red-500">*</span></label>
                                <select name="lift_elevator" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Yes" {{ old('lift_elevator', $property->lift_elevator ?? 'Yes') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('lift_elevator', $property->lift_elevator ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Power Backup</label>
                                <select name="power_backup"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Full', 'Partial', 'None'] as $pb)
                                        <option value="{{ $pb }}" {{ old('power_backup', $property->power_backup ?? '') === $pb ? 'selected' : '' }}>{{ $pb }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Source</label>
                                <select name="water_source"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Municipal', 'Borewell', 'Both', '24x7'] as $ws)
                                        <option value="{{ $ws }}" {{ old('water_source', $property->water_source ?? '') === $ws ? 'selected' : '' }}>{{ $ws }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Availability</label>
                                <select name="water_availability"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['24x7', 'Fixed hours', 'Tanker dependent'] as $wa)
                                        <option value="{{ $wa }}" {{ old('water_availability', $property->water_availability ?? '') === $wa ? 'selected' : '' }}>{{ $wa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Electricity Status</label>
                                <select name="electricity_status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['No cuts', 'Rare cuts', 'Frequent cuts'] as $es)
                                        <option value="{{ $es }}" {{ old('electricity_status', $property->electricity_status ?? '') === $es ? 'selected' : '' }}>{{ $es }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gated Society</label>
                                <select name="gated_society"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('gated_society', $property->gated_society ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('gated_society', $property->gated_society ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security / CCTV</label>
                                <select name="security_cctv"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['24x7', 'Partial', 'None'] as $sec)
                                        <option value="{{ $sec }}" {{ old('security_cctv', $property->security_cctv ?? '') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pet Friendly</label>
                                <select name="pet_friendly"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('pet_friendly', $property->pet_friendly ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('pet_friendly', $property->pet_friendly ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amenities Checklist</label>
                                <select name="amenities_checklist[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedAmenities = old('amenities_checklist', $property->amenities_checklist ?? []);
                                    @endphp
                                    @foreach(['Gym', 'Pool', 'Clubhouse', 'Park', 'Play area', 'Lift', 'Garden'] as $am)
                                        <option value="{{ $am }}" {{ in_array($am, $selectedAmenities) ? 'selected' : '' }}>
                                            {{ $am }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 4 — SECTION G, SECTION G2 & SECTION H: COMMERCIALS, TENANT TERMS & INVESTMENT / ROI --}}
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div>
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION G — COMMERCIAL TERMS</h4>
                            <p class="text-xs text-gray-500">Transaction type, rent/sale pricing & negotiable terms</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Listing Purpose / Deal Type
                                    <span class="text-red-500">*</span></label>
                                <select name="deal_type" required x-model="dealType"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="Rent" {{ old('deal_type', $property->deal_type ?? 'Rent') === 'Rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="Sale" {{ old('deal_type', $property->deal_type ?? '') === 'Sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="Both" {{ old('deal_type', $property->deal_type ?? '') === 'Both' ? 'selected' : '' }}>Both (Rent or Sale)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price on Request</label>
                                <select name="price_on_request"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('price_on_request', $property->price_on_request ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('price_on_request', $property->price_on_request ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div x-show="dealType === 'Rent' || dealType === 'Both'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Rent (₹/month) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="expected_rent"
                                    value="{{ old('expected_rent', $property->expected_rent ?? '') }}"
                                    placeholder="Monthly rent"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="dealType === 'Rent' || dealType === 'Both'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit Months <span
                                        class="text-red-500">*</span></label>
                                <select required name="security_deposit_months"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['1', '2', '3', '6 months', 'Negotiable'] as $dep)
                                        <option value="{{ $dep }}" {{ old('security_deposit_months', $property->security_deposit_months ?? '') === $dep ? 'selected' : '' }}>{{ $dep }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="dealType === 'Sale' || dealType === 'Both'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Sale Price (₹) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="expected_sale_price"
                                    value="{{ old('expected_sale_price', $property->expected_sale_price ?? '') }}"
                                    placeholder="Total sale price"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Charge
                                    (₹/month)</label>
                                <input type="number" name="maintenance_charge"
                                    value="{{ old('maintenance_charge', $property->maintenance_charge ?? '') }}"
                                    placeholder="Monthly maintenance"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Booking Amount (₹)</label>
                                <input type="number" name="booking_amount"
                                    value="{{ old('booking_amount', $property->booking_amount ?? '') }}"
                                    placeholder="Booking amount"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Negotiable Floor Price
                                    (₹)</label>
                                <input type="number" name="negotiable_floor_price"
                                    value="{{ old('negotiable_floor_price', $property->negotiable_floor_price ?? '') }}"
                                    placeholder="Minimum negotiable price"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Owner Flexibility Notes</label>
                                <textarea name="owner_flexibility_notes" rows="2"
                                    placeholder="Owner flexibility, negotiable terms..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('owner_flexibility_notes', $property->owner_flexibility_notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION G2 --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION G2 — TENANT PREFERENCES & RENTAL TERMS
                            </h4>
                            <p class="text-xs text-gray-500">Tenant filters, lease duration & utility charge
                                responsibilities</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Tenant</label>
                                <select name="preferred_tenant[]" multiple class="select2-multiple w-full">
                                    @php
                                        $selectedTenants = old('preferred_tenant', $property->preferred_tenant ?? []);
                                    @endphp
                                    @foreach(['Family', 'Bachelor Male', 'Bachelor Female', 'Company', 'Any'] as $pt)
                                        <option value="{{ $pt }}" {{ in_array($pt, $selectedTenants) ? 'selected' : '' }}>
                                            {{ $pt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Non-Veg Allowed</label>
                                <select name="non_veg_allowed"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('non_veg_allowed', $property->non_veg_allowed ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('non_veg_allowed', $property->non_veg_allowed ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pets Allowed</label>
                                <select name="pets_allowed"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Yes" {{ old('pets_allowed', $property->pets_allowed ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('pets_allowed', $property->pets_allowed ?? '') === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notice Period (months)</label>
                                <input type="number" name="notice_period"
                                    value="{{ old('notice_period', $property->notice_period ?? '') }}" min="0"
                                    placeholder="Notice period"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Lease Term
                                    (months)</label>
                                <input type="number" name="minimum_lease_agreement_term"
                                    value="{{ old('minimum_lease_agreement_term', $property->minimum_lease_agreement_term ?? '') }}"
                                    min="0" placeholder="Minimum agreement term"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Electricity Charges</label>
                                <select name="electricity_charges"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Included" {{ old('electricity_charges', $property->electricity_charges ?? '') === 'Included' ? 'selected' : '' }}>Included</option>
                                    <option value="Extra — as per meter" {{ old('electricity_charges', $property->electricity_charges ?? '') === 'Extra — as per meter' ? 'selected' : '' }}>
                                        Extra — as per meter</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Water Charges</label>
                                <select name="water_charges"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Included" {{ old('water_charges', $property->water_charges ?? '') === 'Included' ? 'selected' : '' }}>Included</option>
                                    <option value="Extra" {{ old('water_charges', $property->water_charges ?? '') === 'Extra' ? 'selected' : '' }}>Extra</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Inclusion</label>
                                <select name="maintenance_inclusion"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    <option value="Included in rent" {{ old('maintenance_inclusion', $property->maintenance_inclusion ?? '') === 'Included in rent' ? 'selected' : '' }}>
                                        Included in rent</option>
                                    <option value="Extra fixed" {{ old('maintenance_inclusion', $property->maintenance_inclusion ?? '') === 'Extra fixed' ? 'selected' : '' }}>Extra
                                        fixed</option>
                                    <option value="Extra per sq ft" {{ old('maintenance_inclusion', $property->maintenance_inclusion ?? '') === 'Extra per sq ft' ? 'selected' : '' }}>
                                        Extra per sq ft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION H --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION H — INVESTMENT / ROI</h4>
                            <p class="text-xs text-gray-500">For pre-leased or currently tenanted properties being sold</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Currently Rented / Tenanted?
                                    <span class="text-red-500">*</span></label>
                                <select name="currently_rented_tenanted" required x-model="isTenanted"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="No" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? 'No') === 'No' ? 'selected' : '' }}>No
                                    </option>
                                    <option value="Yes" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? '') === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="Partially" {{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? '') === 'Partially' ? 'selected' : '' }}>
                                        Partially</option>
                                </select>
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Monthly Rent Received
                                    (₹) <span class="text-red-500">*</span></label>
                                <input required type="number" name="current_monthly_rent_received"
                                    value="{{ old('current_monthly_rent_received', $property->current_monthly_rent_received ?? '') }}"
                                    placeholder="Monthly rent"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Name / Profile</label>
                                <input type="text" name="tenant_name_profile"
                                    value="{{ old('tenant_name_profile', $property->tenant_name_profile ?? '') }}"
                                    placeholder="Tenant name / profile"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tenant Type</label>
                                <select name="tenant_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['MNC', 'Corporate', 'Bank', 'Brand Retail', 'Individual'] as $tt)
                                        <option value="{{ $tt }}" {{ old('tenant_type', $property->tenant_type ?? '') === $tt ? 'selected' : '' }}>{{ $tt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lease Start Date <span
                                        class="text-red-500">*</span></label>
                                <input required type="date" name="lease_start_date"
                                    value="{{ old('lease_start_date', isset($property->lease_start_date) ? $property->lease_start_date->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lease Tenure (years) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="lease_tenure"
                                    value="{{ old('lease_tenure', $property->lease_tenure ?? '') }}" placeholder="e.g. 3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lock-in Remaining (months) <span
                                        class="text-red-500">*</span></label>
                                <input required type="number" name="lock_in_remaining"
                                    value="{{ old('lock_in_remaining', $property->lock_in_remaining ?? '') }}"
                                    placeholder="e.g. 12"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Annual Escalation in Lease
                                    (%)</label>
                                <input type="number" step="0.01" name="annual_escalation_in_lease"
                                    value="{{ old('annual_escalation_in_lease', $property->annual_escalation_in_lease ?? '') }}"
                                    placeholder="e.g. 5.0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Security Deposit Held
                                    (₹)</label>
                                <input type="text" name="security_deposit_held"
                                    value="{{ old('security_deposit_held', $property->security_deposit_held ?? '') }}"
                                    placeholder="Deposit amount"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deposit Adjustment on
                                    Sale</label>
                                <select name="deposit_adjustment_on_sale"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Transferred to buyer', 'Refunded', 'Negotiable'] as $depAdj)
                                        <option value="{{ $depAdj }}" {{ old('deposit_adjustment_on_sale', $property->deposit_adjustment_on_sale ?? '') === $depAdj ? 'selected' : '' }}>
                                            {{ $depAdj }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">CAM / Outgoings Borne By</label>
                                <select name="cam_outgoings_borne_by"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                    <option value="">— Select —</option>
                                    @foreach(['Tenant', 'Owner', 'Shared'] as $cam)
                                        <option value="{{ $cam }}" {{ old('cam_outgoings_borne_by', $property->cam_outgoings_borne_by ?? '') === $cam ? 'selected' : '' }}>{{ $cam }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="isTenanted === 'Yes' || isTenanted === 'Partially'" class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Payback / Capital Value
                                    Note</label>
                                <textarea name="payback_capital_value_note" rows="2" placeholder="Payback notes..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('payback_capital_value_note', $property->payback_capital_value_note ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 5 — SECTION J & SECTION K: PHOTOS, MEDIA & TEAM REMARKS --}}
                <div class="wizard-step-content space-y-6" style="display:none">
                    <div>
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION J — PHOTOS & MEDIA</h4>
                            <p class="text-xs text-gray-500">Interior rooms, exterior photos, floor plans & 360 video
                                walkthrough links</p>
                        </div>
                        <div class="space-y-4">
                            <p class="text-xs text-gray-500">Upload property photos (JPEG, PNG, WebP up to 5MB each)</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($slots as $idx => $label)
                                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 text-center">
                                        <span class="text-xs font-semibold text-gray-700 block mb-2">{{ $label }}</span>
                                        <input type="file" name="photo_{{ $idx }}" accept="image/*,.pdf"
                                            class="text-xs w-full text-gray-500">
                                    </div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Video Walkthrough
                                        Link</label>
                                    <input type="url" name="video_walkthrough_link"
                                        value="{{ old('video_walkthrough_link', $property->video_walkthrough_link ?? '') }}"
                                        placeholder="https://youtube.com/..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Virtual Tour / 360
                                        Link</label>
                                    <input type="url" name="virtual_tour_360_link"
                                        value="{{ old('virtual_tour_360_link', $property->virtual_tour_360_link ?? '') }}"
                                        placeholder="https://my.matterport.com/..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION K --}}
                    <div class="border-t pt-4">
                        <div class="border-b pb-2 mb-4">
                            <h4 class="text-base font-bold text-zendo-navy">SECTION K — TEAM REMARKS</h4>
                            <p class="text-xs text-gray-500">Field officer remarks, public description & inspection date</p>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Inspection / Submission Date
                                    <span class="text-red-500">*</span></label>
                                <input required type="date" name="inspection_submission_date"
                                    value="{{ old('inspection_submission_date', isset($property->inspection_submission_date) ? $property->inspection_submission_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Public Property
                                    Description</label>
                                <textarea name="property_description" rows="3"
                                    placeholder="Public facing description of the property..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('property_description', $property->property_description ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submitter Remarks <span
                                        class="text-red-500">*</span></label>
                                <textarea name="remarks" required rows="3"
                                    placeholder="General remarks, special highlights or notes..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('remarks', $property->remarks ?? '') }}</textarea>
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
        function apartmentWizard() {
            return {
                isPartOfProject: '{{ old('part_of_a_project_society', $property->part_of_a_project_society ?? 'Yes') }}',
                constrStatus: '{{ old('construction_listing_status', $property->construction_listing_status ?? 'Ready to Move') }}',
                availability: '{{ old('availability', $property->availability ?? 'Immediate') }}',
                reraReg: '{{ old('rera_registered', $property->rera_registered ?? 'Yes') }}',
                dealType: '{{ old('deal_type', $property->deal_type ?? 'Rent') }}',
                isTenanted: '{{ old('currently_rented_tenanted', $property->currently_rented_tenanted ?? 'No') }}',
            };
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

            // Toggle step contents
            document.querySelectorAll('.wizard-step-content').forEach((el, idx) => {
                el.style.display = (idx === s) ? 'block' : 'none';
            });

            // Toggle dots & lines
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

            // Re-initialize select2 if visible
            setTimeout(initSelect2, 50);

            // Update Prev button disabled
            const prevBtn = document.getElementById('wiz-prev-btn');
            if (prevBtn) {
                prevBtn.disabled = (s === 0);
                prevBtn.classList.toggle('opacity-40', s === 0);
                prevBtn.classList.toggle('cursor-not-allowed', s === 0);
            }

            // Toggle Next vs Submit button
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

        window.wizardNext = function () {
            if (typeof window.wizardValidateStep === 'function' && !window.wizardValidateStep(window.wizCurrent)) return;
            window.wizardGoTo(window.wizCurrent + 1);
        };

        window.wizardPrev = function () {
            window.wizardGoTo(window.wizCurrent - 1);
        };

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