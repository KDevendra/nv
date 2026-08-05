@extends('layouts.field')
@section('title', 'Add Property')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Add Property</h2>
            <p class="text-gray-500 text-sm mt-1">Fill all sections — this entry goes straight to admin for approval</p>
        </div>
        <a href="{{ route('supplyhead.properties.index') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if($errors->any())
        @php
            // Group errors by section for step-by-step display
            $sectionFields = [
                'A. Location & Identification'   => ['facility_type','property_name','name_full_address','village','tehsil','district','state','country','postal_address_pin','nearest_city','nearest_highway','nearest_railway_station','nearest_airport','owner_contact_name','owner_contact_phone','owner_email'],
                'B. Legal & Statutory Compliance'=> ['tenure','approved_land_use','fire_noc','clu_conversion_status','pollution_noc','pollution_category','occupancy_certificate'],
                'C. Property Dimensions'         => ['area_unit','plot_area','built_up_area','carpet_area','available_area','clear_height_highest','clear_height_side','shed_width','shed_length','number_of_floors','fsi_far'],
                'D. Dock, Exit & Width Details'  => ['dock_door_count','dock_front','dock_left','dock_right','dock_back','has_dock_leveller','dock_leveller_front','dock_leveller_left','dock_leveller_right','dock_leveller_back','fire_exit_front','fire_exit_left','fire_exit_right','fire_exit_back','canopy_length_front','canopy_length_left','canopy_length_right','canopy_length_back','canopy_width_front','canopy_width_left','canopy_width_right','canopy_width_back','road_width_front','road_width_left','road_width_right','road_width_back'],
                'E. Facility Details'            => ['has_offices','no_of_offices','office_sizes','canteen','canteen_size','stp_plant','stp_capacity','washrooms','no_of_urinals','no_of_closets','female_washroom','driver_rest_room','mezzanine','mezzanine_size','structure_type','flooring_type','ventilation_lighting','insulation_roof','insulation_side','fire_sprinkler','scrap_yard','no_of_companies_same_premise','extension_possible'],
                'F. Loading & Docking'           => ['dock_type','dock_height','truck_movement','office_cabin_area'],
                'G. Utilities & Infrastructure'  => ['power_sanctioned_kva','discom_name','water_source','water_tank_capacity','fire_fighting_system','solar'],
                'H. Financial & Lease Terms'     => ['deal_type','expected_rent','expected_sale_price','security_deposit_months','lock_in_years','available_from'],
                'I. Surroundings & Environment'  => ['approach_road_width','top_neighbouring_companies','flood_risk'],
                'J. Health & Emergency Nearby'   => ['nearest_hospital_km','nearest_fire_station_km','nearest_police_station_km'],
                'K. Photographs'                 => ['photos'],
                'L. General Remarks'             => ['remarks'],
            ];
            $errorsBySec = [];
            foreach ($sectionFields as $secName => $fields) {
                foreach ($fields as $f) {
                    if ($errors->has($f) || $errors->has($f . '.*')) {
                        $errorsBySec[$secName][] = $errors->first($f) ?: $errors->first($f . '.*');
                    }
                }
            }
            $uncategorised = [];
            $allCatFields = array_merge(...array_values($sectionFields));
            foreach ($errors->keys() as $k) {
                $baseKey = explode('.', $k)[0];
                if (!in_array($k, $allCatFields) && !in_array($baseKey, $allCatFields)) {
                    $uncategorised[] = $errors->first($k);
                }
            }
        @endphp
        <div id="error-stepper" class="mb-6 bg-red-50 border border-red-200 rounded-xl overflow-hidden" x-data="errorStepper()" x-init="init()">
            <div class="px-4 py-3 bg-red-100 border-b border-red-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold text-red-800">
                        {{ $errors->count() }} {{ Str::plural('error', $errors->count()) }} in
                        {{ count($errorsBySec) + (count($uncategorised) ? 1 : 0) }} {{ Str::plural('section', count($errorsBySec)) }} — fix them one by one
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-red-600 font-medium" x-text="'Step ' + (currentStep + 1) + ' of ' + totalSteps"></span>
                    <div class="flex gap-1">
                        <button type="button" @click="prev()" :disabled="currentStep === 0"
                            class="px-2 py-1 rounded bg-white border border-red-300 text-red-600 text-xs font-semibold disabled:opacity-40 hover:bg-red-50">← Prev</button>
                        <button type="button" @click="next()" :disabled="currentStep === totalSteps - 1"
                            class="px-2 py-1 rounded bg-white border border-red-300 text-red-600 text-xs font-semibold disabled:opacity-40 hover:bg-red-50">Next →</button>
                    </div>
                </div>
            </div>
            @php $stepIdx = 0; @endphp
            @foreach($errorsBySec as $secName => $secErrors)
                <div class="step-panel px-4 py-3 border-b border-red-100 last:border-0" data-step="{{ $stepIdx }}" x-show="currentStep === {{ $stepIdx }}" x-cloak>
                    <p class="text-xs font-semibold text-red-700 mb-1.5">{{ $secName }}</p>
                    <ul class="space-y-0.5">
                        @foreach($secErrors as $err)
                            <li class="flex items-start gap-1.5 text-sm text-red-700">
                                <span class="mt-0.5 text-red-400 flex-shrink-0">•</span>{{ $err }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @php $stepIdx++; @endphp
            @endforeach
            @if(count($uncategorised))
                <div class="step-panel px-4 py-3" data-step="{{ $stepIdx }}" x-show="currentStep === {{ $stepIdx }}" x-cloak>
                    <p class="text-xs font-semibold text-red-700 mb-1.5">Other Errors</p>
                    <ul class="space-y-0.5">
                        @foreach($uncategorised as $err)
                            <li class="flex items-start gap-1.5 text-sm text-red-700"><span class="mt-0.5 text-red-400 flex-shrink-0">•</span>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <script>
        function errorStepper() {
            return {
                currentStep: 0,
                totalSteps: {{ count($errorsBySec) + (count($uncategorised) ? 1 : 0) }},
                sectionAnchors: @json(array_keys($errorsBySec)),
                init() {
                    this.currentStep = 0;
                    this.$nextTick(() => this.scrollToSection());
                },
                next() { if (this.currentStep < this.totalSteps - 1) { this.currentStep++; this.$nextTick(() => this.scrollToSection()); } },
                prev() { if (this.currentStep > 0) { this.currentStep--; this.$nextTick(() => this.scrollToSection()); } },
                scrollToSection() {
                    // Find the section heading that matches the current step
                    const title = this.sectionAnchors[this.currentStep];
                    if (!title) return;
                    const headings = document.querySelectorAll('[data-section-title]');
                    for (const h of headings) {
                        if (h.dataset.sectionTitle === title) {
                            // Expand it
                            const sectionEl = h.closest('[x-data]');
                            if (sectionEl && sectionEl._x_dataStack) {
                                sectionEl._x_dataStack[0].open = true;
                            }
                            setTimeout(() => h.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80);
                            break;
                        }
                    }
                }
            };
        }
        </script>
    @endif

    <form method="POST" action="{{ route('supplyhead.properties.store') }}" enctype="multipart/form-data" x-data="{ isDraft: false }">
        @csrf
        @php $entry = null; @endphp
        @include('field.properties._form')

        {{-- Buttons are in the wizard nav bar inside _form.blade.php --}}
    </form>

</div>
@endsection
