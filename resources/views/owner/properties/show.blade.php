@extends('layouts.owner')
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
            <a href="{{ route('owner.properties.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors px-3 py-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
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
                    <a href="{{ $property->owner_edit_url }}"
                        class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all shadow whitespace-nowrap">
                        Re-edit &amp; Resubmit
                    </a>
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

    {{-- C. Dimensions --}}
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

    {{-- G. Financial --}}
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

    {{-- Photos --}}
    @if($property->photos->count())
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">Photographs</h3>
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

</div>
@endsection
