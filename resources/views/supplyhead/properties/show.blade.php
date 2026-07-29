@extends('layouts.field')
@section('title', 'Review — ' . $property->code)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">{{ $property->code }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->status_badge_class }}">{{ $property->status_label }}</span>
                @if($property->is_expired)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">Expired</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                Officer: <span class="font-medium">{{ $property->fieldOfficer?->name }}</span>
                &bull; {{ $property->facility_type ?? '—' }} &bull; {{ $property->nearest_city ?? '—' }}
            </p>
        </div>
        <a href="{{ route('supplyhead.properties.index') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

    {{-- Submitted Location — captured from the field officer's device at submit time --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-zendo-navy mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-zendo-navy">Field Officer Current Location</h3>
                    @if($property->form_submited_address)
                        <p class="text-sm text-gray-600 mt-0.5">{{ $property->form_submited_address }}</p>
                    @elseif($property->form_submited_location)
                        <p class="text-sm text-gray-600 font-mono mt-0.5">{{ $property->form_submited_location }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-0.5">Not captured — the field officer's device didn't provide a location for this entry.</p>
                    @endif
                </div>
            </div>
            @if($property->form_submited_maps_url)
                <a href="{{ $property->form_submited_maps_url }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-zendo-navy text-white text-xs font-semibold hover:opacity-90 transition-opacity self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View on Google Maps
                </a>
            @endif
        </div>
    </div>


    {{-- Field Validation — Collapsible Sections --}}
    @if(in_array($property->status, ['submitted', 'recheck']) && isset($fields))
        <div class="space-y-6" x-data="fieldReview()">

            {{-- Card Header with Progress --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-zendo-navy">Field Validation</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Review each field section-by-section and mark as correct or incorrect</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="undoAllCorrect" type="button"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-4-4 4-4m-4 4h11a4 4 0 010 8h-1"/>
                            </svg>
                            Undo All Correct
                        </button>
                        <button @click="markAllIncorrect" type="button"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all shadow">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Mark All Incorrect
                        </button>
                        <button @click="markAllCorrect" type="button"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all shadow">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Mark All Correct
                        </button>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="px-5 py-3 border-b border-gray-100" style="background-color: #f0f4f5;">
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-gray-700 font-medium">Review Progress</span>
                        <span class="text-gray-600 font-medium" x-text="$store.review.reviewed + ' / ' + $store.review.total + ' fields reviewed'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500"
                            :style="'width:' + $store.review.percentage + '%'"></div>
                    </div>
                    <div class="flex gap-4 mt-2 text-xs">
                        <span class="text-green-700"><span class="font-bold" x-text="$store.review.correct"></span> correct</span>
                        <span class="text-red-700"><span class="font-bold" x-text="$store.review.incorrect"></span> incorrect</span>
                        <span class="text-gray-500"><span class="font-bold" x-text="$store.review.pending"></span> pending</span>
                    </div>
                </div>
            </div>

            @php
                $fieldsByName = $fields->keyBy('name');
                // Complete list of all 98 data fields across all sections
                $sections = [
                    ['key' => 'A', 'title' => 'Location & Identification', 'fields' => [
                        'facility_type','property_name','name_full_address','village','tehsil','district','state','country',
                        'postal_address_pin','nearest_city','nearest_highway','nearest_railway_station',
                        'nearest_airport','owner_contact_name','owner_contact_phone','owner_email'
                    ]],
                    ['key' => 'B', 'title' => 'Legal & Statutory Compliance', 'fields' => [
                        'tenure','approved_land_use','fire_noc','clu_conversion_status','pollution_noc',
                        'pollution_category','occupancy_certificate'
                    ]],
                    ['key' => 'C', 'title' => 'Property Dimensions & Facilities', 'fields' => [
                        // Property Dimensions (10 fields)
                        'plot_area','built_up_area','carpet_area','available_area','clear_height_highest',
                        'clear_height_side','shed_width','shed_length','number_of_floors','fsi_far',
                        // Dock, Exit & Width Details (22 fields)
                        'dock_door_count','dock_front','dock_left','dock_right','dock_back',
                        'has_dock_leveller','dock_leveller_front','dock_leveller_left','dock_leveller_right','dock_leveller_back',
                        'fire_exit_front','fire_exit_left','fire_exit_right','fire_exit_back',
                        'canopy_width_front','canopy_width_left','canopy_width_right','canopy_width_back',
                        'road_width_front','road_width_left','road_width_right','road_width_back',
                        // Facility Details (22 fields)
                        'no_of_offices','office_sizes','canteen','canteen_size','stp_plant','stp_capacity',
                        'washrooms','no_of_urinals','no_of_closets','female_washroom','driver_rest_room',
                        'mezzanine','mezzanine_size','structure_type','flooring_type','ventilation_lighting',
                        'insulation_roof','insulation_side','fire_sprinkler','scrap_yard',
                        'no_of_companies_same_premise','extension_possible'
                    ]],
                    ['key' => 'D', 'title' => 'Loading & Docking', 'fields' => [
                        'dock_type','dock_height','truck_movement','office_cabin_area'
                    ]],
                    ['key' => 'F', 'title' => 'Utilities & Infrastructure', 'fields' => [
                        'power_sanctioned_kva','discom_name','water_source','water_tank_capacity',
                        'fire_fighting_system','solar'
                    ]],
                    ['key' => 'G', 'title' => 'Financial & Lease Terms', 'fields' => [
                        'deal_type','expected_rent','expected_sale_price','security_deposit_months',
                        'lock_in_years','available_from'
                    ]],
                    ['key' => 'H', 'title' => 'Surroundings & Environment', 'fields' => [
                        'approach_road_width','top_neighbouring_companies','flood_risk'
                    ]],
                    ['key' => 'I', 'title' => 'Health & Emergency Nearby', 'fields' => [
                        'nearest_hospital_km','nearest_fire_station_km','nearest_police_station_km'
                    ]],
                    ['key' => 'K', 'title' => 'General Remarks', 'fields' => [
                        'remarks'
                    ]],
                ];
                // Total: 15 + 7 + 53 + 4 + 6 + 6 + 3 + 3 + 1 = 98 data fields + 8 photo slots = 106 total
            @endphp

            {{-- Collapsible Sections --}}
            @foreach($sections as $section)
                @php
                    // Get section field names for Alpine tracking
                    $sectionFieldNames = collect($section['fields'])->map(fn($fn) => $fieldsByName->get($fn))->filter()->pluck('name')->toArray();
                @endphp
                <div x-data="sectionCounter(@js($sectionFieldNames))" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                    {{-- Section Toggle Header --}}
                    <button @click="open = !open" type="button"
                        class="w-full flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 sm:px-5 py-3 sm:py-4 cursor-pointer select-none border-b border-gray-200 hover:bg-opacity-90 transition-all gap-2 sm:gap-0"
                        :style="allCorrect ? 'background: linear-gradient(to right, #d1fae5, #a7f3d0)' : 'background: linear-gradient(to right, #e8eef0, #dfe7ea)'">
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <div class="w-8 h-8 text-white rounded-full flex items-center justify-center font-bold shadow-sm flex-shrink-0" :style="allCorrect ? 'background-color: #059669' : 'background-color: #0b2c3d'">{{ $section['key'] }}</div>
                            <h3 class="text-base sm:text-lg font-semibold" :class="allCorrect ? 'text-green-800' : 'text-gray-800'">{{ $section['title'] }}</h3>
                        </div>
                        <div class="flex items-center gap-2 ml-11 sm:ml-0">
                            <span x-show="correct > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="correct"></span>
                            </span>
                            <span x-show="incorrect > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span x-text="incorrect"></span>
                            </span>
                            <span class="text-xs text-gray-500 font-medium" x-text="reviewed + '/' + total"></span>
                            <svg class="w-5 h-5 text-gray-500 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    {{-- Section Table --}}
                    <div x-show="open" x-collapse>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="px-3 sm:px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/4">Field</th>
                                        <th class="px-3 sm:px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Value</th>
                                        <th class="px-3 sm:px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-40 sm:w-52">Review</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($section['fields'] as $fn)
                                        @php $field = $fieldsByName->get($fn); @endphp
                                        @if($field)
                                            <tr x-data="fieldRow(@js($field))"
                                                :class="status === false ? 'bg-red-50' : (status === true ? 'bg-green-50/30' : '')"
                                                class="transition-colors">
                                                {{-- Field --}}
                                                <td class="px-3 sm:px-5 py-3 text-xs sm:text-sm font-medium text-gray-700 align-top">{{ $field['label'] }}</td>

                                                {{-- Value + remark when incorrect --}}
                                                <td class="px-3 sm:px-5 py-3 text-xs sm:text-sm text-gray-900 align-top">
                                                    <span class="break-words">{{ $field['value'] ?: '—' }}</span>
                                                    <template x-if="status === false && remark">
                                                        <p class="mt-1 text-xs text-red-600 italic font-medium" x-text="'⚠ ' + remark"></p>
                                                    </template>
                                                </td>

                                                {{-- Review: checkboxes → remark input (inline) --}}
                                                <td class="px-3 sm:px-5 py-3 align-top">
                                                    {{-- Checkbox toggles --}}
                                                    <div x-show="!remarking" class="flex items-center gap-2 sm:gap-3">
                                                        <label class="inline-flex items-center gap-1 sm:gap-1.5 cursor-pointer select-none" @click.prevent="markCorrect">
                                                            <span :class="status === true ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300'"
                                                                class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                                <svg x-show="status === true" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            </span>
                                                            <span :class="status === true ? 'text-green-700 font-semibold' : 'text-gray-500'" class="text-xs hidden sm:inline">Correct</span>
                                                        </label>
                                                        <span class="text-gray-200 select-none hidden sm:inline">|</span>
                                                        <label class="inline-flex items-center gap-1 sm:gap-1.5 cursor-pointer select-none" @click.prevent="startRemark">
                                                            <span :class="status === false ? 'bg-red-600 border-red-600' : 'bg-white border-gray-300'"
                                                                class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                                <svg x-show="status === false" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </span>
                                                            <span :class="status === false ? 'text-red-700 font-semibold' : 'text-gray-500'" class="text-xs hidden sm:inline">Incorrect</span>
                                                        </label>
                                                    </div>

                                                    {{-- Remark input (replaces checkboxes when active) --}}
                                                    <div x-show="remarking" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-1 sm:gap-1.5">
                                                        <input type="text" x-model="pendingRemark"
                                                            placeholder="Remark (required)…"
                                                            @keydown.enter="saveIncorrect"
                                                            @keydown.escape="cancelRemark"
                                                            x-ref="remarkInput"
                                                            class="flex-1 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-400 focus:border-transparent">
                                                        <button @click="saveIncorrect" type="button"
                                                            class="px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 flex-shrink-0">Save</button>
                                                        <button @click="cancelRemark" type="button"
                                                            class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded hover:bg-gray-200 flex-shrink-0">Cancel</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Section J — Photographs (reviewable) --}}
            @php
                $photoFieldNames = $photoReviews->map(fn($p) => $p['name'])->toArray();
            @endphp
            <div x-data="sectionCounter(@js($photoFieldNames))" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                <button @click="open = !open" type="button"
                    class="w-full flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 sm:px-5 py-3 sm:py-4 cursor-pointer select-none border-b border-gray-200 hover:bg-opacity-90 transition-all gap-2 sm:gap-0"
                    :style="allCorrect ? 'background: linear-gradient(to right, #d1fae5, #a7f3d0)' : 'background: linear-gradient(to right, #e8eef0, #dfe7ea)'">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="w-8 h-8 text-white rounded-full flex items-center justify-center font-bold shadow-sm flex-shrink-0" :style="allCorrect ? 'background-color: #059669' : 'background-color: #0b2c3d'">J</div>
                        <h3 class="text-base sm:text-lg font-semibold" :class="allCorrect ? 'text-green-800' : 'text-gray-800'">
                            Photographs
                            <span class="text-sm font-normal ml-2" :class="allCorrect ? 'text-green-600' : 'text-gray-500'">({{ $property->photos->count() }}/{{ $photoReviews->count() }} uploaded)</span>
                        </h3>
                    </div>
                    <div class="flex items-center gap-2 ml-11 sm:ml-0">
                        <span x-show="correct > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="correct"></span>
                        </span>
                        <span x-show="incorrect > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span x-text="incorrect"></span>
                        </span>
                        <span class="text-xs text-gray-500 font-medium" x-text="reviewed + '/' + total"></span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>
                <div x-show="open" x-collapse>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/4">Slot</th>
                                <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Image</th>
                                <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-52">Review</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($photoReviews as $photoSlot)
                                <tr x-data="fieldRow(@js([
                                        'name'       => $photoSlot['name'],
                                        'label'      => $photoSlot['label'],
                                        'value'      => $photoSlot['url'],
                                        'is_correct' => $photoSlot['is_correct'],
                                        'remark'     => $photoSlot['remark'],
                                    ]))"
                                    :class="status === false ? 'bg-red-50' : (status === true ? 'bg-green-50/30' : '')"
                                    class="transition-colors">

                                    {{-- Slot label --}}
                                    <td class="px-5 py-3 text-sm font-medium text-gray-700 align-top">
                                        {{ $photoSlot['label'] }}
                                    </td>

                                    {{-- Image or placeholder + remark --}}
                                    <td class="px-5 py-3 align-top">
                                        @if($photoSlot['uploaded'])
                                            <a href="{{ $photoSlot['url'] }}" target="_blank" class="block w-24 h-24 flex-shrink-0 group">
                                                <img src="{{ $photoSlot['url'] }}" alt="{{ $photoSlot['label'] }}"
                                                    class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-zendo-gold transition-colors">
                                            </a>
                                        @else
                                            <div class="w-24 h-24 rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center gap-1 flex-shrink-0">
                                                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        {{-- Remark shown below image when incorrect --}}
                                        <template x-if="status === false && remark">
                                            <p class="mt-1.5 text-xs text-red-600 italic font-medium" x-text="'⚠ ' + remark"></p>
                                        </template>
                                    </td>

                                    {{-- Review checkboxes --}}
                                    <td class="px-5 py-3 align-top">
                                        <div x-show="!remarking" class="flex items-center gap-3">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer select-none" @click.prevent="markCorrect">
                                                <span :class="status === true ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300'"
                                                    class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                    <svg x-show="status === true" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                                <span :class="status === true ? 'text-green-700 font-semibold' : 'text-gray-500'" class="text-xs">Correct</span>
                                            </label>
                                            <span class="text-gray-200 select-none">|</span>
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer select-none" @click.prevent="startRemark">
                                                <span :class="status === false ? 'bg-red-600 border-red-600' : 'bg-white border-gray-300'"
                                                    class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                    <svg x-show="status === false" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </span>
                                                <span :class="status === false ? 'text-red-700 font-semibold' : 'text-gray-500'" class="text-xs">Incorrect</span>
                                            </label>
                                        </div>
                                        <div x-show="remarking" class="flex items-center gap-1.5">
                                            <input type="text" x-model="pendingRemark"
                                                placeholder="Remark (required)…"
                                                @keydown.enter="saveIncorrect"
                                                @keydown.escape="cancelRemark"
                                                x-ref="remarkInput"
                                                class="w-36 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-400 focus:border-transparent">
                                            <button @click="saveIncorrect" type="button"
                                                class="px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 flex-shrink-0">Save</button>
                                            <button @click="cancelRemark" type="button"
                                                class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded hover:bg-gray-200 flex-shrink-0">Cancel</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- Alpine.js scripts --}}
        <script>
        // ── Central reactive store: single source of truth for all field statuses ──
        document.addEventListener('alpine:init', () => {
            Alpine.store('review', {
                statuses: @js(
                    $fields->values()
                        ->concat($photoReviews->map(fn($p) => ['name' => $p['name'], 'label' => $p['label'], 'value' => $p['url'], 'is_correct' => $p['is_correct'], 'remark' => $p['remark']]))
                        ->mapWithKeys(fn($f) => [$f['name'] => ['is_correct' => $f['is_correct'], 'remark' => $f['remark'] ?? null]])
                ),
                set(name, isCorrect, remark) {
                    this.statuses[name] = { is_correct: isCorrect, remark };
                },
                countFor(names) {
                    let c = 0, i = 0;
                    names.forEach(n => {
                        const s = this.statuses[n];
                        if (s?.is_correct === true) c++;
                        else if (s?.is_correct === false) i++;
                    });
                    return { correct: c, incorrect: i, reviewed: c + i };
                },
                get total()    { return Object.keys(this.statuses).length; },
                get correct()  { return Object.values(this.statuses).filter(s => s.is_correct === true).length; },
                get incorrect(){ return Object.values(this.statuses).filter(s => s.is_correct === false).length; },
                get reviewed() { return this.correct + this.incorrect; },
                get pending()  { return this.total - this.reviewed; },
                get percentage(){ return this.total ? Math.round(this.reviewed / this.total * 100) : 0; },
            });
        });

        function fieldReview() {
            return {
                async markAllCorrect() {
                    if (!confirm('Mark all fields and photos as correct?')) return;
                    const r = await fetch('{{ route('supplyhead.properties.mark-all-correct', $property) }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (r.ok) location.reload();
                },
                async markAllIncorrect() {
                    if (!confirm('Mark all fields and photos as incorrect? The field officer will need to review and resubmit every field.')) return;
                    const r = await fetch('{{ route('supplyhead.properties.mark-all-incorrect', $property) }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (r.ok) location.reload();
                },
                async undoAllCorrect() {
                    if (!confirm('Unset all fields currently marked correct? Fields marked incorrect (with a remark) will be left as-is.')) return;
                    const r = await fetch('{{ route('supplyhead.properties.undo-all-correct', $property) }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (r.ok) location.reload();
                }
            };
        }

        function sectionCounter(fieldNames) {
            return {
                fieldNames,
                open: true,
                get correct()  { return this.$store.review.countFor(this.fieldNames).correct; },
                get incorrect(){ return this.$store.review.countFor(this.fieldNames).incorrect; },
                get reviewed() { return this.$store.review.countFor(this.fieldNames).reviewed; },
                get total()    { return this.fieldNames.length; },
                get allCorrect() { return this.total > 0 && this.correct === this.total; },
            };
        }

        function fieldRow(field) {
            return {
                field,
                get status() { return this.$store.review.statuses[this.field.name]?.is_correct ?? null; },
                get remark() { return this.$store.review.statuses[this.field.name]?.remark ?? ''; },
                remarking: false,
                pendingRemark: '',
                startRemark() {
                    this.pendingRemark = this.remark || '';
                    this.remarking = true;
                    this.$nextTick(() => this.$refs.remarkInput?.focus());
                },
                cancelRemark() {
                    this.remarking = false;
                    this.pendingRemark = '';
                },
                async markCorrect() { await this.saveReview(true, null); },
                async saveIncorrect() {
                    if (!this.pendingRemark.trim()) { alert('Remark is required for incorrect fields'); return; }
                    await this.saveReview(false, this.pendingRemark);
                },
                async saveReview(isCorrect, remark) {
                    const r = await fetch('{{ route('supplyhead.properties.review-field', $property) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ field_name: this.field.name, field_label: this.field.label, field_value: this.field.value, is_correct: isCorrect, remark })
                    });
                    if (r.ok) {
                        // Update the central store — all counters update automatically
                        this.$store.review.set(this.field.name, isCorrect, remark);
                        this.remarking = false;
                        this.pendingRemark = '';
                    }
                }
            };
        }
        </script>

    @endif

    
    {{-- Action Form --}}
    @if(in_array($property->status, ['submitted', 'recheck', 'verified', 'rejected']))
    @php
        $allFieldsCorrect = isset($fields) && isset($photoReviews)
            && $fields->count() > 0
            && $fields->every(fn($f) => $f['is_correct'] === true)
            && $photoReviews->every(fn($p) => $p['is_correct'] === true);
        $reviewedCount = (isset($fields) ? $fields->filter(fn($f) => $f['is_correct'] !== null)->count() : 0)
                       + (isset($photoReviews) ? $photoReviews->filter(fn($p) => $p['is_correct'] !== null)->count() : 0);
        $totalCount    = (isset($fields) ? $fields->count() : 0)
                       + (isset($photoReviews) ? $photoReviews->count() : 0);
    @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ showForm: {{ in_array($property->status, ['submitted','recheck']) ? 'true' : 'false' }} }">
            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-zendo-navy">Take Action</h3>
                @if(in_array($property->status, ['verified','rejected']))
                    <button @click="showForm = !showForm" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        <span x-text="showForm ? 'Hide' : 'Change Status'"></span>
                    </button>
                @endif
            </div>
            <div x-show="showForm" class="p-5" x-data="{ selectedAction: '{{ $property->status }}' }">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
                @if($errors->has('action'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <strong>Error:</strong> {{ $errors->first('action') }}
                    </div>
                @endif

                @if(!$allFieldsCorrect && in_array($property->status, ['submitted', 'recheck']))
                    <div class="mb-4 border border-amber-200 bg-amber-50 rounded-lg p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-amber-800">Field Review Incomplete</h4>
                            <p class="text-sm text-amber-700 mt-0.5">
                                Review all fields below before verifying. Progress: <strong>{{ $reviewedCount }} / {{ $totalCount }}</strong> fields reviewed.
                            </p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('supplyhead.properties.action', $property) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Decision <span class="text-red-500">*</span></label>
                        <select name="action" required x-model="selectedAction"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            <option value="">— Select Action —</option>
                            <option value="verified" {{ !$allFieldsCorrect ? 'disabled' : '' }}>
                                &#10003; Verified — Approve this entry{{ !$allFieldsCorrect ? ' (complete field review first)' : '' }}
                            </option>
                            <option value="rejected">&#10007; Rejected — Permanently reject</option>
                            <option value="recheck">&#9888; Recheck — Send back to officer</option>
                        </select>
                        @error('action')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Note to Field Officer <span class="text-gray-400 text-xs">(required for Recheck / Reject)</span></label>
                        <textarea name="note" rows="3" placeholder="Explain what needs to be corrected or why it is rejected..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('note', $property->supply_head_note) }}</textarea>
                        @error('note')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div x-show="selectedAction === 'rejected'" x-transition class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="allow_resubmit" value="1"
                                {{ old('allow_resubmit', $property->allow_resubmit) ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-zendo-navy border-gray-300 rounded focus:ring-zendo-gold">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Allow field officer to re-edit and resubmit</span>
                                <p class="text-xs text-gray-600 mt-1">If unchecked, the entry will be permanently rejected with no option to re-edit.</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow hover:shadow-md">
                            Submit Decision
                        </button>
                    </div>
                </form>
            </div>
            @if(!in_array($property->status, ['submitted','recheck']))
                <div x-show="!showForm" class="px-5 py-4 text-sm text-gray-500">
                    Current status: <span class="font-semibold {{ $property->status_badge_class }} px-2 py-0.5 rounded-full text-xs">{{ $property->status_label }}</span>
                    @if($property->status === 'rejected')
                        @if($property->allow_resubmit)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 ml-2">Re-edit Allowed</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-2">Re-edit Not Allowed</span>
                        @endif
                    @endif
                    @if($property->supply_head_note)
                        <br><span class="italic mt-1 block">Note: {{ $property->supply_head_note }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- ── Property Detail Cards ─────────────────────────────────────────── --}}
    @php
        use App\Models\PropertyFieldConfig;
        $isVerified = $property->status === 'verified';
        $card       = 'bg-white rounded-xl border border-gray-100 shadow-sm p-5';

        // fd($key, $label, $rawValue) — renders one <div> respecting keep/show_after_verification/show_on_website
        $fd = function(string $key, string $label, $rawValue) use ($isVerified): string {
            $cfg = PropertyFieldConfig::forField($key);
            if (!$cfg->keep_field) return '';

            $webBadge = $cfg->show_on_website
                ? '<span class="inline-flex items-center ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700">WEB</span>'
                : '';

            if ($cfg->show_after_verification && !$isVerified) {
                $display = '<span class="text-xs italic text-gray-400">Visible after verification</span>';
            } else {
                $display = '<span class="text-sm font-medium text-gray-900">' . (e($rawValue) ?: '—') . '</span>';
            }

            return '<div><dt class="text-xs text-gray-400 uppercase tracking-wide font-medium flex items-center gap-0.5">'
                . e($label) . $webBadge
                . '</dt><dd class="mt-0.5">' . $display . '</dd></div>';
        };
    @endphp

   

</div>
@endsection
