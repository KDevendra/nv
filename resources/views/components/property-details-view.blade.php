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
    @php
        // submitter_full_name/submitter_role are the real column names every
        // one of the 13 forms actually submits under (confirmed against
        // $fillable and each view's markup) — "submitter_name" and
        // "submitter_relationship_to_owner" were never real fields on any
        // form, so they always rendered "—" regardless of what was entered.
        $submitterName = $property->fieldValue('submitter_full_name');
        $submitterRole = $property->fieldValue('submitter_role');
    @endphp
    @if($property->fieldValue('owner_contact_name') || $property->fieldValue('owner_contact_phone') || $property->fieldValue('owner_email') || $submitterName)
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                SECTION A — SUBMITTER &amp; OWNER DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Submitter Name', $submitterName) !!}
                {!! $dl('Submitter Role', $submitterRole) !!}
                {!! $dl('Owner Contact Name', $property->fieldValue('owner_contact_name')) !!}
                {!! $dl('Owner Contact Phone', $property->fieldValue('owner_contact_phone')) !!}
                {!! $dl('Owner Email', $property->fieldValue('owner_email')) !!}
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
            {!! $dl('Facility / Property Type', $property->fieldValue('facility_type') ?? ucwords(str_replace('_', ' ', $property->fieldValue('property_type') ?? ''))) !!}
            {!! $dl('Property Name', $property->fieldValue('property_name')) !!}
            {!! $dl('Nearest City', $property->fieldValue('nearest_city')) !!}
            {!! $dl('State', $property->fieldValue('state')) !!}
            {!! $dl('Country', $property->fieldValue('country')) !!}
            {!! $dl('PIN Code', $property->fieldValue('postal_address_pin')) !!}
            {!! $dl('Village / Town / District', $property->fieldValue('village_town_district') ?? $property->fieldValue('district')) !!}
            {!! $dl('Tehsil', $property->fieldValue('tehsil')) !!}
            {!! $dl('Nearest Highway', $property->fieldValue('nearest_highway')) !!}
            {!! $dl('Nearest Railway Station', $property->fieldValue('nearest_railway_station')) !!}
            {!! $dl('Nearest Airport', $property->fieldValue('nearest_airport')) !!}
            {!! $dl('GPS Latitude', $property->fieldValue('gps_latitude')) !!}
            {!! $dl('GPS Longitude', $property->fieldValue('gps_longitude')) !!}
            {!! $dl('Facing / Orientation', $property->fieldValue('facing_orientation')) !!}
            {!! $dl('Overlooking / View', $property->fieldValue('overlooking_view')) !!}
            {!! $dl('Nearby Landmarks', $property->fieldValue('nearby_landmarks')) !!}
            {!! $dl('Distance from Key Locations', $property->fieldValue('distance_from_key_locations')) !!}
            {!! $dl('Full Address', $property->fieldValue('name_full_address'), true) !!}
        </dl>
    </div>

    {{-- SECTION B2 — PROJECT / SOCIETY --}}
    @if($property->fieldValue('part_of_a_project_society') === 'Yes' || $property->fieldValue('project_society_name') || $property->fieldValue('project_name'))
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                SECTION B2 — PROJECT / SOCIETY DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Part of Project / Society?', $property->fieldValue('part_of_a_project_society')) !!}
                {!! $dl('Project / Society Name', $property->fieldValue('project_society_name') ?? $property->fieldValue('project_name')) !!}
                {!! $dl('Project RERA ID', $property->fieldValue('project_rera_id')) !!}
                {!! $dl('Developer / Builder Name', $property->fieldValue('developer_builder_name') ?? $property->fieldValue('builder_developer_name')) !!}
                {!! $dl('Total Towers / Blocks', $property->fieldValue('total_towers_blocks')) !!}
                {!! $dl('Total Units in Project', $property->fieldValue('total_units_in_project')) !!}
                {!! $dl('Approved Loan Banks', $property->fieldValue('approved_loan_banks')) !!}
                {!! $dl('Configurations Offered', $property->fieldValue('configurations_offered')) !!}
                {!! $dl('Project Amenities', $property->fieldValue('project_amenities')) !!}
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
            {!! $dl('Unit / Property Type', $property->fieldValue('unit_property_type')) !!}
            {!! $dl('Configuration (BHK)', $property->fieldValue('configuration') ? $property->fieldValue('configuration') . ' BHK' : null) !!}
            {!! $dl('Carpet Area', $property->fieldValue('carpet_area') ? number_format($property->fieldValue('carpet_area'), 2) . ' sq ft' : null) !!}
            {!! $dl('Built-up Area', $property->fieldValue('built_up_area') ? number_format($property->fieldValue('built_up_area'), 2) . ' sq ft' : null) !!}
            {!! $dl('Super Built-up Area', $property->fieldValue('super_built_up_area') ? number_format($property->fieldValue('super_built_up_area'), 2) . ' sq ft' : null) !!}
            {!! $dl('Plot Area', $property->fieldValue('plot_area') ? number_format($property->fieldValue('plot_area'), 2) . ' sq ft' : null) !!}
            {!! $dl('Floor Number', $property->fieldValue('floor_number')) !!}
            {!! $dl('Total Floors in Building', $property->fieldValue('number_of_floors')) !!}
            {!! $dl('Units on This Floor', $property->fieldValue('units_on_this_floor')) !!}
            {!! $dl('No. of Bedrooms', $property->fieldValue('no_of_bedrooms')) !!}
            {!! $dl('No. of Bathrooms', $property->fieldValue('no_of_bathrooms')) !!}
            {!! $dl('No. of Balconies', $property->fieldValue('no_of_balconies')) !!}
            {!! $dl('Additional Rooms', $property->fieldValue('additional_rooms')) !!}
            {!! $dl('Furnishing Status', $property->fieldValue('furnishing_status')) !!}
            {!! $dl('Furnishing Details', $property->fieldValue('furnishing_detail')) !!}
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
            {{-- "transaction_type" was never a real field on any of the 13 forms;
                 deal_type (apartment/warehouse) and listing_purpose_transaction_type
                 (the other 12) are the two real names this concept is actually
                 submitted under. --}}
            {!! $dl('Transaction Type', $property->fieldValue('deal_type') ?? $property->fieldValue('listing_purpose_transaction_type')) !!}
            {!! $dl('Property / Construction Status', $property->fieldValue('construction_listing_status') ?? $property->fieldValue('construction_status') ?? $property->fieldValue('property_status')) !!}
            {!! $dl('Possession By / Year', $property->fieldValue('possession_by') ?? $property->fieldValue('possession_by_if_under_constr')) !!}
            {!! $dl('Availability', $property->fieldValue('availability')) !!}
            {!! $dl('Available From Date', $property->fieldValue('available_from')) !!}
            {!! $dl('Age of Property', $property->fieldValue('age_of_property')) !!}
            {!! $dl('Ownership Type', $property->fieldValue('ownership_type')) !!}
            {!! $dl('RERA Registered?', $property->fieldValue('rera_registered')) !!}
            {!! $dl('RERA Registration ID', $property->fieldValue('rera_registration_id')) !!}
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
            {!! $dl('Tenure', $property->fieldValue('tenure')) !!}
            {!! $dl('Approved Land Use', $property->fieldValue('approved_land_use')) !!}
            {!! $dl('Fire NOC', $property->fieldValue('fire_noc')) !!}
            {!! $dl('Occupancy Certificate', $property->fieldValue('occupancy_certificate')) !!}
            {{-- title_deed_khata_mutated / clear_title / loan_encumbrance were
                 never real field names on any of the 13 forms — title_status
                 and encumbrance_loan_on_property are the real columns the
                 same concepts are actually submitted under (khata specifically
                 is only asked on a couple of types, hence its own line). --}}
            {!! $dl('Title Status', $property->fieldValue('title_status')) !!}
            {!! $dl('Khata / Property Tax Status', $property->fieldValue('khata_property_tax_status')) !!}
            {!! $dl('Loan / Encumbrance', $property->fieldValue('encumbrance_loan_on_property')) !!}
            {!! $dl('Gated Society', $property->fieldValue('gated_society')) !!}
            {!! $dl('Water Source', $property->fieldValue('water_source')) !!}
            {!! $dl('Power Backup', $property->fieldValue('power_backup')) !!}
            {{-- parking_type / parking_slots_count matched no form's real
                 field name — parking is captured very differently per type
                 (covered/open counts on some, a single free-text description
                 on others), so both real shapes are shown. --}}
            {!! $dl('Parking Slots', $property->fieldValue('parking_slots')) !!}
            @php
                $covered = $property->fieldValue('covered_parking_slots');
                $open = $property->fieldValue('open_parking_slots');
            @endphp
            @if($covered !== null || $open !== null)
                {!! $dl('Covered / Open Parking', trim(($covered !== null ? $covered . ' covered' : '') . ($covered !== null && $open !== null ? ', ' : '') . ($open !== null ? $open . ' open' : '')) ?: null) !!}
            @endif
            {!! $dl('Amenities Checklist', $property->fieldValue('amenities_checklist'), true) !!}
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
            {!! $dl('Deal Type', $property->fieldValue('deal_type')) !!}
            {!! $dl('Expected Rent', $property->fieldValue('expected_rent') ? '₹' . number_format($property->fieldValue('expected_rent')) . ' / mo' : null) !!}
            {!! $dl('Rent Range Band', $property->fieldValue('rent_range_band')) !!}
            {!! $dl('Maintenance Charge', $property->fieldValue('maintenance_charge') ? '₹' . number_format($property->fieldValue('maintenance_charge')) . ' / mo' : null) !!}
            {{-- maintenance_borne_by matched no real field; utilities_who_bears
                 and cam_outgoings_borne_by are the two real columns for this
                 concept depending on residential vs commercial type. --}}
            {!! $dl('Maintenance / Utilities Borne By', $property->fieldValue('utilities_who_bears') ?? $property->fieldValue('cam_outgoings_borne_by')) !!}
            {!! $dl('Expected Sale Price', $property->fieldValue('expected_sale_price') ? '₹' . number_format($property->fieldValue('expected_sale_price')) : null) !!}
            {!! $dl('Price Per Sqft', $property->fieldValue('price_per_sqft') ? '₹' . number_format($property->fieldValue('price_per_sqft')) . ' / sq ft' : null) !!}
            {!! $dl('Booking Amount', $property->fieldValue('booking_amount') ? '₹' . number_format($property->fieldValue('booking_amount')) : null) !!}
            {!! $dl('Sale Price Band', $property->fieldValue('sale_price_band')) !!}
            {!! $dl('Negotiable / Floor Price', $property->fieldValue('negotiable_floor_price') ? '₹' . number_format($property->fieldValue('negotiable_floor_price')) : null) !!}
            {!! $dl('Security Deposit (months)', $property->fieldValue('security_deposit_months')) !!}
            {!! $dl('Lock-in Period', $property->fieldValue('lock_in_years') ? $property->fieldValue('lock_in_years') . ' years' : null) !!}
            {!! $dl('Tax & Stamp Duty Extra?', $property->fieldValue('tax_stamp_duty_extra')) !!}
            {!! $dl('Owner Flexibility Notes', $property->fieldValue('owner_flexibility_notes'), true) !!}
        </dl>
    </div>

    {{-- SECTION I — PRE-LEASED / TENANTED DETAILS --}}
    @if($property->fieldValue('currently_rented_tenanted') === 'Yes' || $property->fieldValue('currently_rented_tenanted') === 'Partially' || $property->fieldValue('current_monthly_rent_received'))
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                SECTION I — PRE-LEASED / TENANTED DETAILS
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {!! $dl('Currently Rented / Tenanted?', $property->fieldValue('currently_rented_tenanted')) !!}
                {!! $dl('Current Monthly Rent Received', $property->fieldValue('current_monthly_rent_received') ? '₹' . number_format($property->fieldValue('current_monthly_rent_received')) : null) !!}
                {!! $dl('Tenant Name / Profile', $property->fieldValue('tenant_name_profile')) !!}
                {!! $dl('Tenant Type', $property->fieldValue('tenant_type')) !!}
                {!! $dl('Lease Start Date', $property->fieldValue('lease_start_date')) !!}
                {!! $dl('Lease Tenure', $property->fieldValue('lease_tenure') ? $property->fieldValue('lease_tenure') . ' years' : null) !!}
                {!! $dl('Lock-in Remaining', $property->fieldValue('lock_in_remaining') ? $property->fieldValue('lock_in_remaining') . ' months' : null) !!}
                {!! $dl('Annual Escalation in Lease', $property->fieldValue('annual_escalation_in_lease') ? $property->fieldValue('annual_escalation_in_lease') . '%' : null) !!}
                {!! $dl('Security Deposit Held', $property->fieldValue('security_deposit_held')) !!}
                {!! $dl('Deposit Adjustment on Sale', $property->fieldValue('deposit_adjustment_on_sale')) !!}
                {!! $dl('CAM / Outgoings Borne By', $property->fieldValue('cam_outgoings_borne_by')) !!}
                {!! $dl('Payback / Capital Value Note', $property->fieldValue('payback_capital_value_note'), true) !!}
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
            {!! $dl('Video Walkthrough Link', $property->fieldValue('video_walkthrough_link')) !!}
            {!! $dl('Virtual Tour / 360 Link', $property->fieldValue('virtual_tour_360_link')) !!}
            {!! $dl('Inspection / Submission Date', $property->fieldValue('inspection_submission_date')) !!}
            {!! $dl('Public Property Description', $property->fieldValue('property_description') ?? $property->fieldValue('property_description_public'), true) !!}
            {!! $dl('Submitter / Team Remarks', $property->fieldValue('remarks') ?? $property->fieldValue('field_officer_submitter_remarks'), true) !!}
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
