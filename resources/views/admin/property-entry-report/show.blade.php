@extends('layouts.admin')
@section('title', 'Entry — ' . $entry->code . ' - ZendoIndia Admin')
@section('page-title', 'Property Entry Detail')
@section('page-description', 'Read-only view of field officer property entry ' . $entry->code)

@section('content')
@php
    $dl   = fn($label, $value) => '<div><dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">' . e($label) . '</dt><dd class="text-sm font-medium text-gray-900 mt-0.5">' . (e($value) ?: '—') . '</dd></div>';
    $card = 'bg-white rounded-xl border border-gray-100 shadow-sm p-5';
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">{{ $entry->code }}</h2>
                @php
                    $badge = match($entry->status) {
                        'draft'     => 'bg-gray-100 text-gray-700',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'verified'  => 'bg-green-100 text-green-800',
                        'recheck'   => 'bg-orange-100 text-orange-700',
                        'rejected'  => 'bg-red-100 text-red-800',
                        default     => 'bg-gray-100 text-gray-600',
                    };
                    $label = match($entry->status) {
                        'draft'     => 'Draft',
                        'submitted' => 'Under Review',
                        'verified'  => 'Verified',
                        'recheck'   => 'Needs Recheck',
                        'rejected'  => 'Rejected',
                        default     => ucfirst($entry->status),
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>
            </div>
            <p class="text-sm text-gray-500">
                Supply Head: <span class="font-medium">{{ $entry->supplyHead?->name ?? '—' }}</span>
                &bull; Officer: <span class="font-medium">{{ $entry->fieldOfficer?->name ?? '—' }}</span>
                &bull; {{ $entry->facility_type ?? '—' }} &bull; {{ $entry->nearest_city ?? '—' }}
            </p>
        </div>
        <a href="{{ route('admin.property-entry-report.index', request()->query()) }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Report
        </a>
    </div>

    {{-- Recheck / Rejection note --}}
    @if($entry->status === 'recheck' && $entry->supply_head_note)
        <div class="bg-orange-50 border border-orange-300 rounded-xl p-4">
            <p class="text-sm font-semibold text-orange-800 mb-1">&#9888; Recheck Note from Supply Head:</p>
            <p class="text-sm text-orange-700">{{ $entry->supply_head_note }}</p>
        </div>
    @endif
    @if($entry->status === 'rejected' && $entry->supply_head_note)
        <div class="bg-red-50 border border-red-300 rounded-xl p-4">
            <p class="text-sm font-semibold text-red-800 mb-1">&#10007; Rejected — Reason:</p>
            <p class="text-sm text-red-700">{{ $entry->supply_head_note }}</p>
        </div>
    @endif

    {{-- A. Location --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">A. Location &amp; Identification</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Facility Type', $entry->facility_type) !!}
            {!! $dl('Nearest City', $entry->nearest_city) !!}
            {!! $dl('Village / Town / District', $entry->village_town_district) !!}
            {!! $dl('PIN Code', $entry->postal_address_pin) !!}
            {!! $dl('Nearest Highway', $entry->nearest_highway) !!}
            {!! $dl('Nearest Railway Station', $entry->nearest_railway_station) !!}
            {!! $dl('Nearest Airport', $entry->nearest_airport) !!}
            @if($entry->name_full_address)
                <div class="col-span-2 md:col-span-3">
                    <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Full Address</dt>
                    <dd class="text-sm font-medium text-gray-900 mt-0.5">{{ $entry->name_full_address }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- B. Legal --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">B. Legal &amp; Statutory Compliance</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Tenure', $entry->tenure) !!}
            {!! $dl('Approved Land Use', $entry->approved_land_use) !!}
            {!! $dl('Fire NOC', $entry->fire_noc) !!}
            {!! $dl('CLU Conversion Status', $entry->clu_conversion_status) !!}
            {!! $dl('Occupancy Certificate', $entry->occupancy_certificate) !!}
        </dl>
    </div>

    {{-- C. Dimensions --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">C. Property Dimensions</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Plot Area (sq ft)', $entry->plot_area) !!}
            {!! $dl('Built-up Area (sq ft)', $entry->built_up_area) !!}
            {!! $dl('Clear Height — Highest (ft)', $entry->clear_height_highest) !!}
            {!! $dl('Clear Height — Side (ft)', $entry->clear_height_side) !!}
            {!! $dl('Number of Floors', $entry->number_of_floors) !!}
            {!! $dl('FSI / FAR', $entry->fsi_far) !!}
        </dl>
    </div>

    {{-- D. Docking --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">D. Loading &amp; Docking</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Dock Door Count', $entry->dock_door_count) !!}
            {!! $dl('Dock Type', $entry->dock_type) !!}
            {!! $dl('Dock Height (ft)', $entry->dock_height) !!}
            {!! $dl('Truck Movement', $entry->truck_movement) !!}
        </dl>
    </div>

    {{-- E-F. Environment & Utilities --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">E–F. Environment &amp; Utilities</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Flooring Type', $entry->flooring_type) !!}
            {!! $dl('Office / Cabin Area (sq ft)', $entry->office_cabin_area) !!}
            {!! $dl('Washrooms', $entry->washrooms) !!}
            {!! $dl('Ventilation & Lighting', $entry->ventilation_lighting) !!}
            {!! $dl('Power Sanctioned (KVA)', $entry->power_sanctioned_kva) !!}
            {!! $dl('DISCOM Name', $entry->discom_name) !!}
            {!! $dl('Water Source', $entry->water_source) !!}
            {!! $dl('Fire Fighting System', $entry->fire_fighting_system) !!}
        </dl>
    </div>

    {{-- G. Financial --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">G. Financial &amp; Lease Terms</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Deal Type', $entry->deal_type) !!}
            {!! $dl('Expected Rent (₹/sq ft/mo)', $entry->expected_rent) !!}
            {!! $dl('Expected Sale Price (₹)', $entry->expected_sale_price) !!}
            {!! $dl('Security Deposit (months)', $entry->security_deposit_months) !!}
            {!! $dl('Lock-in Period (years)', $entry->lock_in_years) !!}
            {!! $dl('Available From', $entry->available_from?->format('d M Y')) !!}
        </dl>
    </div>

    {{-- H-I. Surroundings --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">H–I. Surroundings &amp; Emergency</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Approach Road Width (ft)', $entry->approach_road_width) !!}
            {!! $dl('Flood Risk', $entry->flood_risk) !!}
            {!! $dl('Nearest Hospital (km)', $entry->nearest_hospital_km) !!}
            {!! $dl('Nearest Fire Station (km)', $entry->nearest_fire_station_km) !!}
            {!! $dl('Nearest Police Station (km)', $entry->nearest_police_station_km) !!}
            @if($entry->top_neighbouring_companies)
                <div class="col-span-2 md:col-span-3">
                    <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Top Neighbouring Companies</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $entry->top_neighbouring_companies }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- J. Photos --}}
    @if($entry->photos->count())
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">J. Photographs</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($entry->photos as $photo)
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

    {{-- K. Remarks & Meta --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">K. Remarks &amp; Submission Info</h3>
        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Owner Contact Name', $entry->owner_contact_name) !!}
            {!! $dl('Owner Contact Phone', $entry->owner_contact_phone) !!}
            {!! $dl('Supply Head', $entry->supplyHead?->name) !!}
            {!! $dl('Field Officer', $entry->fieldOfficer?->name) !!}
            {!! $dl('Submitted At', $entry->submitted_at?->format('d M Y, g:i A')) !!}
            {!! $dl('Reviewed By', $entry->reviewer?->name) !!}
            {!! $dl('Reviewed At', $entry->reviewed_at?->format('d M Y, g:i A')) !!}
            @if($entry->remarks)
                <div class="col-span-2 md:col-span-3">
                    <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">Remarks</dt>
                    <dd class="text-sm text-gray-900 mt-0.5">{{ $entry->remarks }}</dd>
                </div>
            @endif
        </dl>
    </div>

    {{-- Activity Log --}}
    @if($entry->logs->count())
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">Activity Log</h3>
            <ol class="relative border-l border-gray-200 ml-3 space-y-4">
                @foreach($entry->logs as $log)
                    <li class="ml-4">
                        <div class="absolute w-2.5 h-2.5 bg-zendo-gold rounded-full mt-1.5 -left-1.5 border border-white"></div>
                        <p class="text-xs text-gray-500">{{ $log->created_at->format('d M Y, g:i A') }}</p>
                        <p class="text-sm font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $log->action)) }}</p>
                        @if($log->user)
                            <p class="text-xs text-gray-500">by {{ $log->user->name }}</p>
                        @endif
                        @if($log->note)
                            <p class="text-xs text-gray-600 italic mt-0.5">{{ $log->note }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    @endif

</div>
@endsection
