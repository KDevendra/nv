@props([
    'property' => null,
])

@php
    if (!$property) return;

    $fmtVal = function ($value) {
        if (is_null($value) || $value === '' || $value === []) {
            return '<span class="text-gray-400 font-normal">—</span>';
        }
        if (is_array($value)) {
            $pills = array_map(function ($item) {
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-zendo-navy border border-slate-200 mr-1.5 mb-1 shadow-2xs">' . e($item) . '</span>';
            }, array_filter($value));
            return !empty($pills) ? '<div class="flex flex-wrap mt-0.5">' . implode('', $pills) . '</div>' : '<span class="text-gray-400 font-normal">—</span>';
        }
        if ($value instanceof \DateTimeInterface) {
            return '<span class="font-semibold text-gray-900">' . e($value->format('d M Y')) . '</span>';
        }
        return '<span class="font-semibold text-gray-900">' . e($value) . '</span>';
    };

    $dl = function ($label, $value, $fullWidth = false) use ($fmtVal) {
        $colSpan = $fullWidth ? 'col-span-1 sm:col-span-2 md:col-span-3' : '';
        return '<div class="' . $colSpan . '"><dt class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-0.5">' . e($label) . '</dt><dd class="text-sm mt-0.5">' . $fmtVal($value) . '</dd></div>';
    };

    $card = 'bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4';
@endphp

<div class="space-y-6">

    {{-- SECTION A — SUBMITTER & OWNER DETAILS --}}
    @if($property->owner_contact_name || $property->owner_contact_phone || $property->owner_email || $property->submitter_name)
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                SECTION A — SUBMITTER &amp; OWNER DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Submitter Name', $property->submitter_name) !!}
                {!! $dl('Submitter Relationship', $property->submitter_relationship_to_owner) !!}
                {!! $dl('Owner Contact Name', $property->owner_contact_name) !!}
                {!! $dl('Owner Contact Phone', $property->owner_contact_phone) !!}
                {!! $dl('Owner Email', $property->owner_email) !!}
            </dl>
        </div>
    @endif

    {{-- SECTION B — LOCATION & IDENTIFICATION --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            SECTION B — LOCATION &amp; IDENTIFICATION
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Facility / Property Type', $property->facility_type ?? ucwords(str_replace('_', ' ', $property->property_type ?? ''))) !!}
            {!! $dl('Property Name', $property->property_name) !!}
            {!! $dl('Nearest City', $property->nearest_city) !!}
            {!! $dl('State', $property->state) !!}
            {!! $dl('Country', $property->country) !!}
            {!! $dl('PIN Code', $property->postal_address_pin) !!}
            {!! $dl('Village / Town / District', $property->village_town_district ?? $property->district) !!}
            {!! $dl('Tehsil', $property->tehsil) !!}
            {!! $dl('Nearest Highway', $property->nearest_highway) !!}
            {!! $dl('Nearest Railway Station', $property->nearest_railway_station) !!}
            {!! $dl('Nearest Airport', $property->nearest_airport) !!}
            {!! $dl('GPS Latitude', $property->gps_latitude) !!}
            {!! $dl('GPS Longitude', $property->gps_longitude) !!}
            {!! $dl('Facing / Orientation', $property->facing_orientation) !!}
            {!! $dl('Overlooking / View', $property->overlooking_view) !!}
            {!! $dl('Nearby Landmarks', $property->nearby_landmarks) !!}
            {!! $dl('Distance from Key Locations', $property->distance_from_key_locations) !!}
            {!! $dl('Full Address', $property->name_full_address, true) !!}
        </dl>
    </div>

    {{-- SECTION B2 — PROJECT / SOCIETY --}}
    @if($property->part_of_a_project_society === 'Yes' || $property->project_society_name || $property->project_name)
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                SECTION B2 — PROJECT / SOCIETY DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Part of Project / Society?', $property->part_of_a_project_society) !!}
                {!! $dl('Project / Society Name', $property->project_society_name ?? $property->project_name) !!}
                {!! $dl('Project RERA ID', $property->project_rera_id) !!}
                {!! $dl('Developer / Builder Name', $property->developer_builder_name ?? $property->builder_developer_name) !!}
                {!! $dl('Total Towers / Blocks', $property->total_towers_blocks) !!}
                {!! $dl('Total Units in Project', $property->total_units_in_project) !!}
                {!! $dl('Approved Loan Banks', $property->approved_loan_banks) !!}
                {!! $dl('Configurations Offered', $property->configurations_offered) !!}
                {!! $dl('Project Amenities', $property->project_amenities) !!}
            </dl>
        </div>
    @endif

    {{-- SECTION C — UNIT CONFIGURATION & SPECIFICATIONS --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            SECTION C — UNIT CONFIGURATION &amp; SPECIFICATIONS
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Unit / Property Type', $property->unit_property_type) !!}
            {!! $dl('Configuration (BHK)', $property->configuration ? $property->configuration . ' BHK' : null) !!}
            {!! $dl('Carpet Area', $property->carpet_area ? number_format($property->carpet_area, 2) . ' sq ft' : null) !!}
            {!! $dl('Built-up Area', $property->built_up_area ? number_format($property->built_up_area, 2) . ' sq ft' : null) !!}
            {!! $dl('Super Built-up Area', $property->super_built_up_area ? number_format($property->super_built_up_area, 2) . ' sq ft' : null) !!}
            {!! $dl('Plot Area', $property->plot_area ? number_format($property->plot_area, 2) . ' sq ft' : null) !!}
            {!! $dl('Floor Number', $property->floor_number) !!}
            {!! $dl('Total Floors in Building', $property->number_of_floors) !!}
            {!! $dl('Units on This Floor', $property->units_on_this_floor) !!}
            {!! $dl('No. of Bedrooms', $property->no_of_bedrooms) !!}
            {!! $dl('No. of Bathrooms', $property->no_of_bathrooms) !!}
            {!! $dl('No. of Balconies', $property->no_of_balconies) !!}
            {!! $dl('Additional Rooms', $property->additional_rooms) !!}
            {!! $dl('Furnishing Status', $property->furnishing_status) !!}
            {!! $dl('Furnishing Details', $property->furnishing_detail) !!}
        </dl>
    </div>

    {{-- SECTION C2 — TRANSACTION & POSSESSION STATUS --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            SECTION C2 — TRANSACTION &amp; POSSESSION STATUS
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Transaction Type', $property->transaction_type) !!}
            {!! $dl('Property / Construction Status', $property->construction_listing_status ?? $property->construction_status ?? $property->property_status) !!}
            {!! $dl('Possession By / Year', $property->possession_by ?? $property->possession_by_if_under_constr) !!}
            {!! $dl('Availability', $property->availability) !!}
            {!! $dl('Available From Date', $property->available_from) !!}
            {!! $dl('Age of Property', $property->age_of_property) !!}
            {!! $dl('Ownership Type', $property->ownership_type) !!}
            {!! $dl('RERA Registered?', $property->rera_registered) !!}
            {!! $dl('RERA Registration ID', $property->rera_registration_id) !!}
        </dl>
    </div>

    {{-- SECTION D & E — LEGAL & AMENITIES --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            SECTION D &amp; E — LEGAL, STATUTORY &amp; AMENITIES
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Tenure', $property->tenure) !!}
            {!! $dl('Approved Land Use', $property->approved_land_use) !!}
            {!! $dl('Fire NOC', $property->fire_noc) !!}
            {!! $dl('Occupancy Certificate', $property->occupancy_certificate) !!}
            {!! $dl('Title Deed / Khata / Mutated', $property->title_deed_khata_mutated) !!}
            {!! $dl('Clear Title', $property->clear_title) !!}
            {!! $dl('Loan / Encumbrance', $property->loan_encumbrance) !!}
            {!! $dl('Gated Society', $property->gated_society) !!}
            {!! $dl('Water Source', $property->water_source) !!}
            {!! $dl('Power Backup', $property->power_backup) !!}
            {!! $dl('Parking Type', $property->parking_type) !!}
            {!! $dl('Parking Slots', $property->parking_slots_count) !!}
            {!! $dl('Amenities Checklist', $property->amenities_checklist, true) !!}
        </dl>
    </div>

    {{-- SECTION H — FINANCIAL & COMMERCIAL TERMS --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            SECTION H — FINANCIAL &amp; COMMERCIAL TERMS
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Deal Type', $property->deal_type) !!}
            {!! $dl('Expected Rent', $property->expected_rent ? '₹' . number_format($property->expected_rent) . ' / mo' : null) !!}
            {!! $dl('Rent Range Band', $property->rent_range_band) !!}
            {!! $dl('Maintenance Charge', $property->maintenance_charge ? '₹' . number_format($property->maintenance_charge) . ' / mo' : null) !!}
            {!! $dl('Maintenance Borne By', $property->maintenance_borne_by) !!}
            {!! $dl('Expected Sale Price', $property->expected_sale_price ? '₹' . number_format($property->expected_sale_price) : null) !!}
            {!! $dl('Price Per Sqft', $property->price_per_sqft ? '₹' . number_format($property->price_per_sqft) . ' / sq ft' : null) !!}
            {!! $dl('Booking Amount', $property->booking_amount ? '₹' . number_format($property->booking_amount) : null) !!}
            {!! $dl('Sale Price Band', $property->sale_price_band) !!}
            {!! $dl('Negotiable / Floor Price', $property->negotiable_floor_price ? '₹' . number_format($property->negotiable_floor_price) : null) !!}
            {!! $dl('Security Deposit (months)', $property->security_deposit_months) !!}
            {!! $dl('Lock-in Period', $property->lock_in_years ? $property->lock_in_years . ' years' : null) !!}
            {!! $dl('Tax & Stamp Duty Extra?', $property->tax_stamp_duty_extra) !!}
            {!! $dl('Owner Flexibility Notes', $property->owner_flexibility_notes, true) !!}
        </dl>
    </div>

    {{-- SECTION I — PRE-LEASED / TENANTED DETAILS --}}
    @if($property->currently_rented_tenanted === 'Yes' || $property->currently_rented_tenanted === 'Partially' || $property->current_monthly_rent_received)
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                SECTION I — PRE-LEASED / TENANTED DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Currently Rented / Tenanted?', $property->currently_rented_tenanted) !!}
                {!! $dl('Current Monthly Rent Received', $property->current_monthly_rent_received ? '₹' . number_format($property->current_monthly_rent_received) : null) !!}
                {!! $dl('Tenant Name / Profile', $property->tenant_name_profile) !!}
                {!! $dl('Tenant Type', $property->tenant_type) !!}
                {!! $dl('Lease Start Date', $property->lease_start_date) !!}
                {!! $dl('Lease Tenure', $property->lease_tenure ? $property->lease_tenure . ' years' : null) !!}
                {!! $dl('Lock-in Remaining', $property->lock_in_remaining ? $property->lock_in_remaining . ' months' : null) !!}
                {!! $dl('Annual Escalation in Lease', $property->annual_escalation_in_lease ? $property->annual_escalation_in_lease . '%' : null) !!}
                {!! $dl('Security Deposit Held', $property->security_deposit_held) !!}
                {!! $dl('Deposit Adjustment on Sale', $property->deposit_adjustment_on_sale) !!}
                {!! $dl('CAM / Outgoings Borne By', $property->cam_outgoings_borne_by) !!}
                {!! $dl('Payback / Capital Value Note', $property->payback_capital_value_note, true) !!}
            </dl>
        </div>
    @endif

    {{-- SECTION J & K — MEDIA, LINKS & REMARKS --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            SECTION J &amp; K — MEDIA &amp; REMARKS
        </h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            {!! $dl('Video Walkthrough Link', $property->video_walkthrough_link) !!}
            {!! $dl('Virtual Tour / 360 Link', $property->virtual_tour_360_link) !!}
            {!! $dl('Inspection / Submission Date', $property->inspection_submission_date) !!}
            {!! $dl('Public Property Description', $property->property_description ?? $property->property_description_public, true) !!}
            {!! $dl('Submitter / Team Remarks', $property->remarks ?? $property->field_officer_submitter_remarks, true) !!}
        </dl>
    </div>

    {{-- Photographs --}}
    @if($property->photos && $property->photos->count() > 0)
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Photographs
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($property->photos as $photo)
                    @php
                        $photoUrl = $photo->url ?? (str_starts_with($photo->file_path ?? '', 'http') ? $photo->file_path : asset($photo->file_path ?? $photo->photo_path ?? ''));
                    @endphp
                    <div class="group relative bg-gray-50 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                        <a href="{{ $photoUrl }}" target="_blank" class="block aspect-square overflow-hidden">
                            <img src="{{ $photoUrl }}" alt="{{ $photo->slot_label ?? 'Photo' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </a>
                        <div class="p-2 text-center bg-white border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $photo->slot_label }}">{{ $photo->slot_label ?? 'Photo' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
