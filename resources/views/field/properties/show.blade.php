@extends('layouts.field')
@section('title', 'Property — ' . $property->code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">{{ $property->code }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->status_badge_class }}">
                    {{ $property->status_label }}
                </span>
            </div>
            <p class="text-gray-500 text-sm">{{ $property->facility_type ?? '—' }} &bull; {{ $property->nearest_city ?? '—' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('field.properties.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors px-3 py-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </div>

    {{-- Recheck note --}}
    @if($property->status === 'recheck' && $property->supply_head_note)
        <div class="bg-orange-50 border border-orange-300 rounded-xl p-4">
            <p class="text-sm font-semibold text-orange-800 mb-1">&#9888; Action Required — Note from Supply Head:</p>
            <p class="text-sm text-orange-700">{{ $property->supply_head_note }}</p>
        </div>
    @endif

    {{-- Rejection note --}}
    @if($property->status === 'rejected' && $property->supply_head_note)
        <div class="bg-red-50 border border-red-300 rounded-xl p-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-800 mb-1">&#10007; Rejected — Reason:</p>
                    <p class="text-sm text-red-700">{{ $property->supply_head_note }}</p>
                </div>
                @if($property->allow_resubmit)
                    <a href="{{ route('field.properties.edit', $property) }}"
                        class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all shadow whitespace-nowrap">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Re-edit &amp; Resubmit
                    </a>
                @else
                    <div class="flex-shrink-0 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg border border-gray-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Re-edit Not Allowed
                    </div>
                @endif
            </div>
        </div>
    @elseif($property->status === 'rejected')
        <div class="bg-red-50 border border-red-300 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm font-semibold text-red-800">&#10007; This entry was rejected.</p>
                @if($property->allow_resubmit)
                    <a href="{{ route('field.properties.edit', $property) }}"
                        class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all shadow">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Re-edit &amp; Resubmit
                    </a>
                @else
                    <div class="flex-shrink-0 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg border border-gray-200">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Re-edit Not Allowed
                    </div>
                @endif
            </div>
        </div>
    @endif

    @php
        $dl = fn($label, $value) => '<div><dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">' . e($label) . '</dt><dd class="text-sm font-medium text-gray-900 mt-0.5">' . (e($value) ?: '—') . '</dd></div>';
        $card = 'bg-white rounded-xl border border-gray-100 shadow-sm p-5';
    @endphp

    {{-- A. Location --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">A. Location &amp; Identification</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Facility Type', $property->facility_type) !!}
            {!! $dl('Property Name', $property->property_name) !!}
            {!! $dl('Nearest City', $property->nearest_city) !!}
            {!! $dl('Village / Town / District', $property->village_town_district) !!}
            {!! $dl('PIN Code', $property->postal_address_pin) !!}
            {!! $dl('Nearest Highway', $property->nearest_highway) !!}
            {!! $dl('Nearest Railway Station', $property->nearest_railway_station) !!}
            {!! $dl('Nearest Airport', $property->nearest_airport) !!}
            <div class="col-span-2 md:col-span-3">
                <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Full Address</dt>
                <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $property->name_full_address ?: '—' }}</dd>
            </div>
        </dl>
    </div>

    {{-- B. Legal --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">B. Legal &amp; Statutory Compliance</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Tenure', $property->tenure) !!}
            {!! $dl('Approved Land Use', $property->approved_land_use) !!}
            {!! $dl('Fire NOC', $property->fire_noc) !!}
            {!! $dl('CLU Conversion Status', $property->clu_conversion_status) !!}
            {!! $dl('Occupancy Certificate', $property->occupancy_certificate) !!}
        </dl>
    </div>

    {{-- C–K sections —abbreviated for readability, same pattern --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">C. Property Dimensions</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Plot Area (sq ft)', $property->plot_area) !!}
            {!! $dl('Built-up Area (sq ft)', $property->built_up_area) !!}
            {!! $dl('Clear Height — Highest (ft)', $property->clear_height_highest) !!}
            {!! $dl('Clear Height — Side (ft)', $property->clear_height_side) !!}
            {!! $dl('Number of Floors', $property->number_of_floors) !!}
            {!! $dl('FSI / FAR', $property->fsi_far) !!}
        </dl>
    </div>

    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">D. Loading &amp; Docking</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Dock Door Count', $property->dock_door_count) !!}
            {!! $dl('Dock Type', $property->dock_type) !!}
            {!! $dl('Dock Height (ft)', $property->dock_height) !!}
            {!! $dl('Truck Movement', $property->truck_movement) !!}
        </dl>
    </div>

    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">E–F. Environment &amp; Utilities</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Flooring Type', $property->flooring_type) !!}
            {!! $dl('Office / Cabin Area (sq ft)', $property->office_cabin_area) !!}
            {!! $dl('Washrooms', $property->washrooms) !!}
            {!! $dl('Ventilation & Lighting', $property->ventilation_lighting) !!}
            {!! $dl('Power Sanctioned (KVA)', $property->power_sanctioned_kva) !!}
            {!! $dl('DISCOM Name', $property->discom_name) !!}
            {!! $dl('Water Source', $property->water_source) !!}
            {!! $dl('Fire Fighting System', $property->fire_fighting_system) !!}
        </dl>
    </div>

    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">G. Financial &amp; Lease Terms</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Deal Type', $property->deal_type) !!}
            {!! $dl('Expected Rent (₹/sq ft/mo)', $property->expected_rent) !!}
            {!! $dl('Expected Sale Price (₹)', $property->expected_sale_price) !!}
            {!! $dl('Security Deposit (months)', $property->security_deposit_months) !!}
            {!! $dl('Lock-in Period (years)', $property->lock_in_years) !!}
            {!! $dl('Available From', $property->available_from?->format('d M Y')) !!}
        </dl>
    </div>

    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">H–I. Surroundings &amp; Emergency</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Approach Road Width (ft)', $property->approach_road_width) !!}
            {!! $dl('Flood Risk', $property->flood_risk) !!}
            {!! $dl('Nearest Hospital (km)', $property->nearest_hospital_km) !!}
            {!! $dl('Nearest Fire Station (km)', $property->nearest_fire_station_km) !!}
            {!! $dl('Nearest Police Station (km)', $property->nearest_police_station_km) !!}
            @if($property->top_neighbouring_companies)
                <div class="col-span-2 md:col-span-3">
                    <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Top Neighbouring Companies</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $property->top_neighbouring_companies }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- Photos --}}
    @if($property->photos->count())
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">J. Photographs</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($property->photos as $photo)
                    <div>
                        <a href="{{ $photo->url }}" target="_blank">
                            <img src="{{ $photo->url }}" alt="{{ $photo->slot_label }}"
                                class="w-full aspect-square object-cover rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                        </a>
                        <p class="text-xs text-gray-500 mt-1 text-center leading-tight">{{ $photo->slot_label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- K. Remarks --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">K. General Remarks</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Owner Contact Name', $property->owner_contact_name) !!}
            {!! $dl('Owner Contact Phone', $property->owner_contact_phone) !!}
            @if($property->remarks)
                <div class="col-span-2 md:col-span-3">
                    <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Remarks</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $property->remarks }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- Meta --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">Submission Info</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Entry Code', $property->code) !!}
            {!! $dl('Submitted At', $property->submitted_at?->format('d M Y, g:i A')) !!}
        </dl>
    </div>

</div>
@endsection
