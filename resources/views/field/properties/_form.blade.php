{{-- Shared form partial: create & edit. Expects: $entry (null on create), $slots, $fieldConfigs --}}
@php
    if (!isset($fieldConfigs)) {
        $fieldConfigs = \App\Models\PropertyFieldConfig::allKeyed();
    }
    if (!isset($fieldRemarks)) {
        $fieldRemarks = [];
    }
    $fc  = fn(string $k) => \App\Models\PropertyFieldConfig::forField($k);
    $v   = fn($f) => old($f, $entry?->$f ?? '');
    $sel = fn($f, $o) => old($f, $entry?->$f ?? '') == $o ? 'selected' : '';
    // For boolean fields stored as 1/0, cast to "1"/"0" string for select comparison
    $bv  = fn($f) => old($f, $entry?->$f ?? '') !== '' ? (int) old($f, $entry?->$f ?? '') : '';
    $req = fn(string $k) => $fc($k)->mandatory_field ? 'required' : '';
    $ast = fn(string $k) => $fc($k)->mandatory_field ? '<span class="text-red-500 ml-0.5">*</span>' : '';
    
    // Helper to display field remark (supply head's comment)
    $rmk = fn(string $k) => isset($fieldRemarks[$k]) && $fieldRemarks[$k]
        ? '<div class="mt-1"><p class="text-[10px] font-semibold text-red-700 mb-0.5"></p><p class="text-xs text-red-800">⚠ ' . e($fieldRemarks[$k]) . '</p></div>'
        : '';

    // Pre-compute per-section server error counts for the red badge
    $__sfm = [
        'A. Location & Identification'   => ['facility_type','name_full_address','village','tehsil','district','state','country','postal_address_pin','nearest_city','nearest_highway','nearest_railway_station','nearest_airport','owner_contact_name','owner_contact_phone','owner_email'],
        'B. Legal & Statutory Compliance'=> ['tenure','approved_land_use','fire_noc','clu_conversion_status','pollution_noc','pollution_category','occupancy_certificate'],
        'C. Property Dimensions'         => ['plot_area','built_up_area','carpet_area','available_area','clear_height_highest','clear_height_side','shed_width','shed_length','number_of_floors','fsi_far'],
        'C. Dock, Exit & Width Details'  => ['dock_door_count','dock_front','dock_left','dock_right','dock_back','dock_leveller_front','dock_leveller_left','dock_leveller_right','dock_leveller_back','fire_exit_front','fire_exit_left','fire_exit_right','fire_exit_back','canopy_width_front','canopy_width_left','canopy_width_right','canopy_width_back','road_width_front','road_width_left','road_width_right','road_width_back'],
        'C. Facility Details'            => ['no_of_offices','office_sizes','canteen','canteen_size','stp_plant','stp_capacity','washrooms','no_of_urinals','no_of_closets','female_washroom','driver_rest_room','mezzanine','mezzanine_size','structure_type','flooring_type','ventilation_lighting','insulation_roof','insulation_side','fire_sprinkler','scrap_yard','no_of_companies_same_premise','extension_possible'],
        'D. Loading & Docking'           => ['dock_type','dock_height','truck_movement','office_cabin_area'],
        'F. Utilities & Infrastructure'  => ['power_sanctioned_kva','discom_name','water_source','water_tank_capacity','fire_fighting_system','solar'],
        'G. Financial & Lease Terms'     => ['deal_type','expected_rent','expected_sale_price','security_deposit_months','lock_in_years','available_from'],
        'H. Surroundings & Environment'  => ['approach_road_width','top_neighbouring_companies','flood_risk'],
        'I. Health & Emergency Nearby'   => ['nearest_hospital_km','nearest_fire_station_km','nearest_police_station_km'],
        'K. General Remarks'             => ['remarks'],
    ];
    $__eb = isset($errors) ? $errors->getBag('default') : null;
    $sec_errs = fn(string $t) => $__eb
        ? collect($__sfm[$t] ?? [])->filter(fn($f) => $__eb->has($f))->count()
        : 0;
    // $sd(open, sectionTitle) — builds x-data string with baked-in server error count
    $sd = fn(bool $o, string $t) => 'sectionCounter(' . ($o ? 'true' : 'false') . ',' . $sec_errs($t) . ')';

    $ic  = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm';
    $sc  = 'w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm bg-white';
    $lc  = 'block text-sm font-medium text-gray-700 mb-1';
    $sec = 'bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4';
    $sh  = 'flex items-center justify-between px-5 py-4 cursor-pointer select-none bg-gray-50 border-b border-gray-100';
    $sb  = 'px-5 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4';
    $chevron = '<svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open?\'rotate-180\':\'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
    // Section header right side: error badge + counter pill + chevron
    $counter = '<div class="flex items-center gap-2 flex-shrink-0">'
        // Red error badge — shown when section has server-side errors
        . '<span x-show="errorCount > 0" class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">'
        . '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>'
        . '<span x-text="errorCount + \' error\' + (errorCount > 1 ? \'s\' : \'\')"></span>'
        . '</span>'
        // Filled/total pill
        . '<span x-show="total > 0" class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full transition-colors"'
        . ' :class="errorCount > 0 ? \'bg-red-50 text-red-500\' : (filled === total ? \'bg-green-100 text-green-700\' : (filled > 0 ? \'bg-amber-100 text-amber-700\' : \'bg-gray-100 text-gray-500\'))">'
        . '<span x-text="filled"></span><span class="opacity-50 font-normal">/</span><span x-text="total"></span>'
        . '</span>'
        . '<svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open?\'rotate-180\':\'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>'
        . '</div>';
@endphp

{{-- ══ A. Location & Identification ══════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(true, 'A. Location & Identification') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="A. Location &amp; Identification">A. Location &amp; Identification</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">

        @if($fc('facility_type')->keep_field)
        <div>
            <label class="{{ $lc }}">Facility Type {!! $ast('facility_type') !!}</label>
            <select name="facility_type" {{ $req('facility_type') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Warehouse','Industrial Shed','Cold Storage','Open Land','Commercial Space','Factory'] as $o)
                    <option value="{{ $o }}" {{ $sel('facility_type',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('facility_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            {!! $rmk('facility_type') !!}
        </div>
        @endif

        @if($fc('name_full_address')->keep_field)
        <div class="sm:col-span-2 lg:col-span-3">
            <label class="{{ $lc }}">Full Address / Name of Property {!! $ast('name_full_address') !!}</label>
            <textarea name="name_full_address" rows="2" {{ $req('name_full_address') }} class="{{ $ic }}">{{ $v('name_full_address') }}</textarea>
            @error('name_full_address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('name_full_address') !!}
 </div>
        @endif

        @if($fc('village')->keep_field)
        <div>
            <label class="{{ $lc }}">Village {!! $ast('village') !!}</label>
            <input type="text" name="village" value="{{ $v('village') }}" {{ $req('village') }} class="{{ $ic }}">
            @error('village')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            {!! $rmk('village') !!}
        </div>
        @endif

        @if($fc('tehsil')->keep_field)
        <div>
            <label class="{{ $lc }}">Tehsil {!! $ast('tehsil') !!}</label>
            <input type="text" name="tehsil" value="{{ $v('tehsil') }}" {{ $req('tehsil') }} class="{{ $ic }}">
            @error('tehsil')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('tehsil') !!}
 </div>
        @endif

        @if($fc('district')->keep_field)
        <div>
            <label class="{{ $lc }}">District {!! $ast('district') !!}</label>
            <input type="text" name="district" value="{{ $v('district') }}" {{ $req('district') }} class="{{ $ic }}">
            @error('district')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('district') !!}
 </div>
        @endif

        @if($fc('state')->keep_field)
        <div>
            <label class="{{ $lc }}">State {!! $ast('state') !!}</label>
            <input type="text" name="state" value="{{ $v('state') }}" {{ $req('state') }} class="{{ $ic }}">
            @error('state')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('state') !!}
 </div>
        @endif

        @if($fc('country')->keep_field)
        <div>
            <label class="{{ $lc }}">Country {!! $ast('country') !!}</label>
            <input type="text" name="country" value="{{ $v('country') ?: 'India' }}" {{ $req('country') }} class="{{ $ic }}">
            @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('country') !!}
 </div>
        @endif

        @if($fc('postal_address_pin')->keep_field)
        <div>
            <label class="{{ $lc }}">PIN Code {!! $ast('postal_address_pin') !!}</label>
            <input type="text" name="postal_address_pin" value="{{ $v('postal_address_pin') }}" {{ $req('postal_address_pin') }} class="{{ $ic }}">
            @error('postal_address_pin')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('postal_address_pin') !!}
 </div>
        @endif

        @if($fc('nearest_city')->keep_field)
        <div>
            <label class="{{ $lc }}">Nearest City {!! $ast('nearest_city') !!}</label>
            <input type="text" name="nearest_city" value="{{ $v('nearest_city') }}" {{ $req('nearest_city') }} class="{{ $ic }}">
            @error('nearest_city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_city') !!}
 </div>
        @endif

        @if($fc('nearest_highway')->keep_field)
        <div>
            <label class="{{ $lc }}">Road Connectivity / Nearest Highway {!! $ast('nearest_highway') !!}</label>
            <input type="text" name="nearest_highway" value="{{ $v('nearest_highway') }}" {{ $req('nearest_highway') }} class="{{ $ic }}">
            @error('nearest_highway')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_highway') !!}
 </div>
        @endif

        @if($fc('nearest_railway_station')->keep_field)
        <div>
            <label class="{{ $lc }}">Nearest Railway Station {!! $ast('nearest_railway_station') !!}</label>
            <input type="text" name="nearest_railway_station" value="{{ $v('nearest_railway_station') }}" {{ $req('nearest_railway_station') }} class="{{ $ic }}">
            @error('nearest_railway_station')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_railway_station') !!}
 </div>
        @endif

        @if($fc('nearest_airport')->keep_field)
        <div>
            <label class="{{ $lc }}">Nearest Airport {!! $ast('nearest_airport') !!}</label>
            <input type="text" name="nearest_airport" value="{{ $v('nearest_airport') }}" {{ $req('nearest_airport') }} class="{{ $ic }}">
            @error('nearest_airport')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_airport') !!}
 </div>
        @endif

        @if($fc('owner_contact_name')->keep_field)
        <div>
            <label class="{{ $lc }}">Owner Name {!! $ast('owner_contact_name') !!}</label>
            <input type="text" name="owner_contact_name" value="{{ $v('owner_contact_name') }}" {{ $req('owner_contact_name') }} class="{{ $ic }}">
            @error('owner_contact_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('owner_contact_name') !!}
 </div>
        @endif

        @if($fc('owner_contact_phone')->keep_field)
        <div>
            <label class="{{ $lc }}">Owner Contact Number {!! $ast('owner_contact_phone') !!}</label>
            <input type="text" name="owner_contact_phone" value="{{ $v('owner_contact_phone') }}" {{ $req('owner_contact_phone') }} class="{{ $ic }}">
            @error('owner_contact_phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('owner_contact_phone') !!}
 </div>
        @endif

        @if($fc('owner_email')->keep_field)
        <div>
            <label class="{{ $lc }}">Owner E-mail {!! $ast('owner_email') !!}</label>
            <input type="email" name="owner_email" value="{{ $v('owner_email') }}" {{ $req('owner_email') }} class="{{ $ic }}">
            @error('owner_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('owner_email') !!}
 </div>
        @endif

    </div>
</div>

{{-- ══ B. Legal & Statutory Compliance ══════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'B. Legal & Statutory Compliance') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="B. Legal &amp; Statutory Compliance">B. Legal &amp; Statutory Compliance</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">

        @if($fc('tenure')->keep_field)
        <div>
            <label class="{{ $lc }}">Tenure {!! $ast('tenure') !!}</label>
            <select name="tenure" {{ $req('tenure') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Freehold','Leasehold','Other'] as $o)
                    <option value="{{ $o }}" {{ $sel('tenure',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('tenure')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('tenure') !!}
 </div>
        @endif

        @if($fc('approved_land_use')->keep_field)
        <div>
            <label class="{{ $lc }}">Approved Land Use {!! $ast('approved_land_use') !!}</label>
            <select name="approved_land_use" {{ $req('approved_land_use') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Industrial','Commercial','Warehousing','Agricultural','Mixed','Not sure'] as $o)
                    <option value="{{ $o }}" {{ $sel('approved_land_use',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('approved_land_use')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('approved_land_use') !!}
 </div>
        @endif

        @if($fc('fire_noc')->keep_field)
        <div>
            <label class="{{ $lc }}">Fire NOC Availability {!! $ast('fire_noc') !!}</label>
            <select name="fire_noc" {{ $req('fire_noc') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','Applied'] as $o)
                    <option value="{{ $o }}" {{ $sel('fire_noc',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('fire_noc')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('fire_noc') !!}
 </div>
        @endif

        @if($fc('clu_conversion_status')->keep_field)
        <div>
            <label class="{{ $lc }}">CLU / Conversion Status {!! $ast('clu_conversion_status') !!}</label>
            <input type="text" name="clu_conversion_status" value="{{ $v('clu_conversion_status') }}" {{ $req('clu_conversion_status') }} class="{{ $ic }}">
            @error('clu_conversion_status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('clu_conversion_status') !!}
 </div>
        @endif

        @if($fc('pollution_noc')->keep_field)
        <div>
            <label class="{{ $lc }}">Pollution NOC {!! $ast('pollution_noc') !!}</label>
            <select name="pollution_noc" {{ $req('pollution_noc') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','Applied'] as $o)
                    <option value="{{ $o }}" {{ $sel('pollution_noc',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('pollution_noc')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('pollution_noc') !!}
 </div>
        @endif

        @if($fc('pollution_category')->keep_field)
        <div>
            <label class="{{ $lc }}">Pollution Category {!! $ast('pollution_category') !!}</label>
            <input type="text" name="pollution_category" value="{{ $v('pollution_category') }}" {{ $req('pollution_category') }}  class="{{ $ic }}">
            @error('pollution_category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('pollution_category') !!}
 </div>
        @endif

        @if($fc('occupancy_certificate')->keep_field)
        <div>
            <label class="{{ $lc }}">Occupancy Certificate {!! $ast('occupancy_certificate') !!}</label>
            <select name="occupancy_certificate" {{ $req('occupancy_certificate') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','NA'] as $o)
                    <option value="{{ $o }}" {{ $sel('occupancy_certificate',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('occupancy_certificate')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('occupancy_certificate') !!}
 </div>
        @endif

    </div>
</div>

{{-- ══ C. Property Dimensions ════════════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'C. Property Dimensions') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="C. Property Dimensions">C. Property Dimensions</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">

        @if($fc('plot_area')->keep_field)
        <div><label class="{{ $lc }}">Plot Area — as per CLU (sq ft) {!! $ast('plot_area') !!}</label>
            <input type="number" step="0.01" min="0" name="plot_area" value="{{ $v('plot_area') }}" {{ $req('plot_area') }} class="{{ $ic }}">
            @error('plot_area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('plot_area') !!}
 </div>
        @endif

        @if($fc('built_up_area')->keep_field)
        <div><label class="{{ $lc }}">Built-up Area (sq ft) {!! $ast('built_up_area') !!}</label>
            <input type="number" step="0.01" min="0" name="built_up_area" value="{{ $v('built_up_area') }}" {{ $req('built_up_area') }} class="{{ $ic }}">
            @error('built_up_area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('built_up_area') !!}
 </div>
        @endif

        @if($fc('carpet_area')->keep_field)
        <div><label class="{{ $lc }}">Carpet Area (sq ft) {!! $ast('carpet_area') !!}</label>
            <input type="number" step="0.01" min="0" name="carpet_area" value="{{ $v('carpet_area') }}" {{ $req('carpet_area') }} class="{{ $ic }}">
            @error('carpet_area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('carpet_area') !!}
 </div>
        @endif

        @if($fc('available_area')->keep_field)
        <div><label class="{{ $lc }}">Available Area (sq ft) {!! $ast('available_area') !!}</label>
            <input type="number" step="0.01" min="0" name="available_area" value="{{ $v('available_area') }}" {{ $req('available_area') }} class="{{ $ic }}">
            @error('available_area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('available_area') !!}
 </div>
        @endif

        @if($fc('clear_height_highest')->keep_field)
        <div><label class="{{ $lc }}">Clear Height — Highest (ft) {!! $ast('clear_height_highest') !!}</label>
            <input type="number" step="0.01" min="0" name="clear_height_highest" value="{{ $v('clear_height_highest') }}" {{ $req('clear_height_highest') }} class="{{ $ic }}">
            @error('clear_height_highest')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('clear_height_highest') !!}
 </div>
        @endif

        @if($fc('clear_height_side')->keep_field)
        <div><label class="{{ $lc }}">Clear Height — Side Wall (ft) {!! $ast('clear_height_side') !!}</label>
            <input type="number" step="0.01" min="0" name="clear_height_side" value="{{ $v('clear_height_side') }}" {{ $req('clear_height_side') }} class="{{ $ic }}">
            @error('clear_height_side')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('clear_height_side') !!}
 </div>
        @endif

        @if($fc('shed_width')->keep_field)
        <div><label class="{{ $lc }}">Shed Width (ft) {!! $ast('shed_width') !!}</label>
            <input type="number" step="0.01" min="0" name="shed_width" value="{{ $v('shed_width') }}" {{ $req('shed_width') }} class="{{ $ic }}">
            @error('shed_width')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('shed_width') !!}
 </div>
        @endif

        @if($fc('shed_length')->keep_field)
        <div><label class="{{ $lc }}">Shed Length (ft) {!! $ast('shed_length') !!}</label>
            <input type="number" step="0.01" min="0" name="shed_length" value="{{ $v('shed_length') }}" {{ $req('shed_length') }} class="{{ $ic }}">
            @error('shed_length')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('shed_length') !!}
 </div>
        @endif

        @if($fc('number_of_floors')->keep_field)
        <div><label class="{{ $lc }}">Number of Floors {!! $ast('number_of_floors') !!}</label>
            <input type="number" min="0" name="number_of_floors" value="{{ $v('number_of_floors') }}" {{ $req('number_of_floors') }} class="{{ $ic }}">
            @error('number_of_floors')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('number_of_floors') !!}
 </div>
        @endif

        @if($fc('fsi_far')->keep_field)
        <div><label class="{{ $lc }}">FSI / FAR {!! $ast('fsi_far') !!}</label>
            <input type="text" name="fsi_far" value="{{ $v('fsi_far') }}" {{ $req('fsi_far') }} class="{{ $ic }}">
            @error('fsi_far')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('fsi_far') !!}
 </div>
        @endif

    </div>
</div>

{{-- ══ C-sub. Docks, Levellers, Fire Exits, Canopy & Road Widths ════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'C. Dock, Exit & Width Details') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="C. Dock, Exit &amp; Width Details">C. Dock, Exit &amp; Width Details</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="px-5 py-5 space-y-6">

        {{-- Dock counts --}}
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Total Dock Doors</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['dock_door_count'=>'Total','dock_front'=>'Front','dock_left'=>'Left','dock_right'=>'Right','dock_back'=>'Back'] as $fk=>$lbl)
                @if($fc($fk)->keep_field)
                <div><label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                    <input type="number" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }} class="{{ $ic }}">
                    @error($fk)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Dock levellers --}}
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Dock Levellers</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['dock_leveller_front'=>'Front','dock_leveller_left'=>'Left','dock_leveller_right'=>'Right','dock_leveller_back'=>'Back'] as $fk=>$lbl)
                @if($fc($fk)->keep_field)
                <div><label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                    <input type="number" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }} class="{{ $ic }}">
                    @error($fk)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Fire exit doors --}}
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Fire Exit Doors</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['fire_exit_front'=>'Front','fire_exit_left'=>'Left','fire_exit_right'=>'Right','fire_exit_back'=>'Back'] as $fk=>$lbl)
                @if($fc($fk)->keep_field)
                <div><label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                    <input type="number" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }} class="{{ $ic }}">
                    @error($fk)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Canopy widths --}}
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Canopy Width (ft)</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['canopy_width_front'=>'Front','canopy_width_left'=>'Left','canopy_width_right'=>'Right','canopy_width_back'=>'Back'] as $fk=>$lbl)
                @if($fc($fk)->keep_field)
                <div><label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                    <input type="number" step="0.01" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }} class="{{ $ic }}">
                    @error($fk)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endif
                @endforeach
            </div>
        </div>

        {{-- Road widths --}}
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Road Width (ft)</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['road_width_front'=>'Front','road_width_left'=>'Left','road_width_right'=>'Right','road_width_back'=>'Back'] as $fk=>$lbl)
                @if($fc($fk)->keep_field)
                <div><label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                    <input type="number" step="0.01" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }} class="{{ $ic }}">
                    @error($fk)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endif
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ══ C-sub. Facilities (offices, canteen, washrooms, STP, etc.) ══════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'C. Facility Details') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="C. Facility Details">C. Facility Details</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">

        @if($fc('no_of_offices')->keep_field)
        <div><label class="{{ $lc }}">No. of Offices {!! $ast('no_of_offices') !!}</label>
            <input type="number" min="0" name="no_of_offices" value="{{ $v('no_of_offices') }}" {{ $req('no_of_offices') }} class="{{ $ic }}">
            @error('no_of_offices')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('no_of_offices') !!}
 </div>
        @endif

        @if($fc('office_sizes')->keep_field)
        <div><label class="{{ $lc }}">Office Sizes {!! $ast('office_sizes') !!}</label>
            <input type="text" name="office_sizes" value="{{ $v('office_sizes') }}" {{ $req('office_sizes') }} class="{{ $ic }}">
            @error('office_sizes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('office_sizes') !!}
 </div>
        @endif

        @if($fc('canteen')->keep_field)
        <div><label class="{{ $lc }}">Canteen {!! $ast('canteen') !!}</label>
            <select name="canteen" {{ $req('canteen') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('canteen') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('canteen') === 0 && $bv('canteen') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('canteen')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('canteen') !!}
 </div>
        @endif

        @if($fc('canteen_size')->keep_field)
        <div><label class="{{ $lc }}">Canteen Size {!! $ast('canteen_size') !!}</label>
            <input type="text" name="canteen_size" value="{{ $v('canteen_size') }}" {{ $req('canteen_size') }} class="{{ $ic }}">
            @error('canteen_size')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('canteen_size') !!}
 </div>
        @endif

        @if($fc('stp_plant')->keep_field)
        <div><label class="{{ $lc }}">STP Plant {!! $ast('stp_plant') !!}</label>
            <select name="stp_plant" {{ $req('stp_plant') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('stp_plant') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('stp_plant') === 0 && $bv('stp_plant') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('stp_plant')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('stp_plant') !!}
 </div>
        @endif

        @if($fc('stp_capacity')->keep_field)
        <div><label class="{{ $lc }}">STP Capacity {!! $ast('stp_capacity') !!}</label>
            <input type="text" name="stp_capacity" value="{{ $v('stp_capacity') }}" {{ $req('stp_capacity') }} class="{{ $ic }}">
            @error('stp_capacity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('stp_capacity') !!}
 </div>
        @endif

        @if($fc('washrooms')->keep_field)
        <div><label class="{{ $lc }}">No. of Washrooms {!! $ast('washrooms') !!}</label>
            <input type="number" min="0" name="washrooms" value="{{ $v('washrooms') }}" {{ $req('washrooms') }} class="{{ $ic }}">
            @error('washrooms')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('washrooms') !!}
 </div>
        @endif

        @if($fc('no_of_urinals')->keep_field)
        <div><label class="{{ $lc }}">No. of Urinals {!! $ast('no_of_urinals') !!}</label>
            <input type="number" min="0" name="no_of_urinals" value="{{ $v('no_of_urinals') }}" {{ $req('no_of_urinals') }} class="{{ $ic }}">
            @error('no_of_urinals')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('no_of_urinals') !!}
 </div>
        @endif

        @if($fc('no_of_closets')->keep_field)
        <div><label class="{{ $lc }}">No. of Closets {!! $ast('no_of_closets') !!}</label>
            <input type="number" min="0" name="no_of_closets" value="{{ $v('no_of_closets') }}" {{ $req('no_of_closets') }} class="{{ $ic }}">
            @error('no_of_closets')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('no_of_closets') !!}
 </div>
        @endif

        @if($fc('female_washroom')->keep_field)
        <div><label class="{{ $lc }}">Female Washroom {!! $ast('female_washroom') !!}</label>
            <select name="female_washroom" {{ $req('female_washroom') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('female_washroom') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('female_washroom') === 0 && $bv('female_washroom') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('female_washroom')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('female_washroom') !!}
 </div>
        @endif

        @if($fc('driver_rest_room')->keep_field)
        <div><label class="{{ $lc }}">Driver Rest Room {!! $ast('driver_rest_room') !!}</label>
            <select name="driver_rest_room" {{ $req('driver_rest_room') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('driver_rest_room') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('driver_rest_room') === 0 && $bv('driver_rest_room') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('driver_rest_room')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('driver_rest_room') !!}
 </div>
        @endif

        @if($fc('mezzanine')->keep_field)
        <div><label class="{{ $lc }}">Mezzanine {!! $ast('mezzanine') !!}</label>
            <select name="mezzanine" {{ $req('mezzanine') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('mezzanine') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('mezzanine') === 0 && $bv('mezzanine') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('mezzanine')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('mezzanine') !!}
 </div>
        @endif

        @if($fc('mezzanine_size')->keep_field)
        <div><label class="{{ $lc }}">Mezzanine Size {!! $ast('mezzanine_size') !!}</label>
            <input type="text" name="mezzanine_size" value="{{ $v('mezzanine_size') }}" {{ $req('mezzanine_size') }} class="{{ $ic }}">
            @error('mezzanine_size')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('mezzanine_size') !!}
 </div>
        @endif

        @if($fc('structure_type')->keep_field)
        <div><label class="{{ $lc }}">Structure Type {!! $ast('structure_type') !!}</label>
            <input type="text" name="structure_type" value="{{ $v('structure_type') }}" {{ $req('structure_type') }}  class="{{ $ic }}">
            @error('structure_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('structure_type') !!}
 </div>
        @endif

        @if($fc('flooring_type')->keep_field)
        <div><label class="{{ $lc }}">Flooring Type {!! $ast('flooring_type') !!}</label>
            <select name="flooring_type" {{ $req('flooring_type') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['FM2','VDF','Trimix','Concrete','Kota / Tile','Kachha'] as $o)
                    <option value="{{ $o }}" {{ $sel('flooring_type',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('flooring_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('flooring_type') !!}
 </div>
        @endif

        @if($fc('ventilation_lighting')->keep_field)
        <div><label class="{{ $lc }}">Ventilation &amp; Lighting {!! $ast('ventilation_lighting') !!}</label>
            <select name="ventilation_lighting" {{ $req('ventilation_lighting') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Good','Average','Poor'] as $o)
                    <option value="{{ $o }}" {{ $sel('ventilation_lighting',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('ventilation_lighting')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('ventilation_lighting') !!}
 </div>
        @endif

        @if($fc('insulation_roof')->keep_field)
        <div><label class="{{ $lc }}">Roof Insulation {!! $ast('insulation_roof') !!}</label>
            <input type="text" name="insulation_roof" value="{{ $v('insulation_roof') }}" {{ $req('insulation_roof') }} class="{{ $ic }}">
            @error('insulation_roof')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('insulation_roof') !!}
 </div>
        @endif

        @if($fc('insulation_side')->keep_field)
        <div><label class="{{ $lc }}">Side Insulation {!! $ast('insulation_side') !!}</label>
            <input type="text" name="insulation_side" value="{{ $v('insulation_side') }}" {{ $req('insulation_side') }} class="{{ $ic }}">
            @error('insulation_side')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('insulation_side') !!}
 </div>
        @endif

        @if($fc('fire_sprinkler')->keep_field)
        <div><label class="{{ $lc }}">Fire Sprinkler {!! $ast('fire_sprinkler') !!}</label>
            <select name="fire_sprinkler" {{ $req('fire_sprinkler') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Yes','No','Partial'] as $o)
                    <option value="{{ $o }}" {{ $sel('fire_sprinkler',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('fire_sprinkler')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('fire_sprinkler') !!}
 </div>
        @endif

        @if($fc('scrap_yard')->keep_field)
        <div><label class="{{ $lc }}">Scrap Yard {!! $ast('scrap_yard') !!}</label>
            <select name="scrap_yard" {{ $req('scrap_yard') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('scrap_yard') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('scrap_yard') === 0 && $bv('scrap_yard') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('scrap_yard')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('scrap_yard') !!}
 </div>
        @endif

        @if($fc('no_of_companies_same_premise')->keep_field)
        <div><label class="{{ $lc }}">No. of Companies in Same Premise {!! $ast('no_of_companies_same_premise') !!}</label>
            <input type="number" min="0" name="no_of_companies_same_premise" value="{{ $v('no_of_companies_same_premise') }}" {{ $req('no_of_companies_same_premise') }} class="{{ $ic }}">
            @error('no_of_companies_same_premise')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('no_of_companies_same_premise') !!}
 </div>
        @endif

        @if($fc('extension_possible')->keep_field)
        <div><label class="{{ $lc }}">Extension Possible? {!! $ast('extension_possible') !!}</label>
            <select name="extension_possible" {{ $req('extension_possible') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('extension_possible') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('extension_possible') === 0 && $bv('extension_possible') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('extension_possible')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('extension_possible') !!}
 </div>
        @endif

    </div>
</div>

{{-- ══ D. Loading & Docking ══════════════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'D. Loading & Docking') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="D. Loading &amp; Docking">D. Loading &amp; Docking Facilities</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('dock_type')->keep_field)
        <div><label class="{{ $lc }}">Dock Type {!! $ast('dock_type') !!}</label>
            <select name="dock_type" {{ $req('dock_type') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Ground level','Dock high','Both','None'] as $o)
                    <option value="{{ $o }}" {{ $sel('dock_type',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('dock_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('dock_type') !!}
 </div>
        @endif
        @if($fc('dock_height')->keep_field)
        <div><label class="{{ $lc }}">Dock Height (ft) {!! $ast('dock_height') !!}</label>
            <input type="number" step="0.01" min="0" name="dock_height" value="{{ $v('dock_height') }}" {{ $req('dock_height') }} class="{{ $ic }}">
            @error('dock_height')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('dock_height') !!}
 </div>
        @endif
        @if($fc('truck_movement')->keep_field)
        <div><label class="{{ $lc }}">Truck Movement {!! $ast('truck_movement') !!}</label>
            <select name="truck_movement" {{ $req('truck_movement') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['40 ft container','32 ft truck','Tempo only','Restricted'] as $o)
                    <option value="{{ $o }}" {{ $sel('truck_movement',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('truck_movement')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('truck_movement') !!}
 </div>
        @endif
        @if($fc('office_cabin_area')->keep_field)
        <div><label class="{{ $lc }}">Office / Cabin Area (sq ft) {!! $ast('office_cabin_area') !!}</label>
            <input type="number" step="0.01" min="0" name="office_cabin_area" value="{{ $v('office_cabin_area') }}" {{ $req('office_cabin_area') }} class="{{ $ic }}">
            @error('office_cabin_area')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('office_cabin_area') !!}
 </div>
        @endif
    </div>
</div>

{{-- ══ F. Utilities & Infrastructure ═══════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'F. Utilities & Infrastructure') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="F. Utilities &amp; Infrastructure">F. Utilities &amp; Infrastructure</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('power_sanctioned_kva')->keep_field)
        <div><label class="{{ $lc }}">Power Sanctioned (KVA) {!! $ast('power_sanctioned_kva') !!}</label>
            <input type="number" step="0.01" min="0" name="power_sanctioned_kva" value="{{ $v('power_sanctioned_kva') }}" {{ $req('power_sanctioned_kva') }} class="{{ $ic }}">
            @error('power_sanctioned_kva')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('power_sanctioned_kva') !!}
 </div>
        @endif
        @if($fc('discom_name')->keep_field)
        <div><label class="{{ $lc }}">DISCOM Name {!! $ast('discom_name') !!}</label>
            <input type="text" name="discom_name" value="{{ $v('discom_name') }}" {{ $req('discom_name') }} class="{{ $ic }}">
            @error('discom_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('discom_name') !!}
 </div>
        @endif
        @if($fc('water_source')->keep_field)
        <div><label class="{{ $lc }}">Water Source {!! $ast('water_source') !!}</label>
            <select name="water_source" {{ $req('water_source') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Borewell','Municipal','Tanker','None'] as $o)
                    <option value="{{ $o }}" {{ $sel('water_source',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('water_source')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('water_source') !!}
 </div>
        @endif
        @if($fc('water_tank_capacity')->keep_field)
        <div><label class="{{ $lc }}">Water Tank Capacity {!! $ast('water_tank_capacity') !!}</label>
            <input type="text" name="water_tank_capacity" value="{{ $v('water_tank_capacity') }}" {{ $req('water_tank_capacity') }}  class="{{ $ic }}">
            @error('water_tank_capacity')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('water_tank_capacity') !!}
 </div>
        @endif
        @if($fc('fire_fighting_system')->keep_field)
        <div><label class="{{ $lc }}">Fire Fighting System {!! $ast('fire_fighting_system') !!}</label>
            <select name="fire_fighting_system" {{ $req('fire_fighting_system') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Full sprinkler','Hydrant only','Extinguishers','None'] as $o)
                    <option value="{{ $o }}" {{ $sel('fire_fighting_system',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('fire_fighting_system')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('fire_fighting_system') !!}
 </div>
        @endif
        @if($fc('solar')->keep_field)
        <div><label class="{{ $lc }}">Solar {!! $ast('solar') !!}</label>
            <select name="solar" {{ $req('solar') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                <option value="1" {{ $bv('solar') === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $bv('solar') === 0 && $bv('solar') !== '' ? 'selected' : '' }}>No</option>
            </select>
            @error('solar')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('solar') !!}
 </div>
        @endif
    </div>
</div>

{{-- ══ G. Financial & Lease Terms ════════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'G. Financial & Lease Terms') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="G. Financial &amp; Lease Terms">G. Financial &amp; Lease Terms</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('deal_type')->keep_field)
        <div><label class="{{ $lc }}">Lease / Sale Status {!! $ast('deal_type') !!}</label>
            <select name="deal_type" {{ $req('deal_type') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['Lease','Sale','Both'] as $o)
                    <option value="{{ $o }}" {{ $sel('deal_type',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('deal_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('deal_type') !!}
 </div>
        @endif
        @if($fc('expected_rent')->keep_field)
        <div><label class="{{ $lc }}">Expected Rent (₹/sq ft/month) {!! $ast('expected_rent') !!}</label>
            <input type="number" step="0.01" min="0" name="expected_rent" value="{{ $v('expected_rent') }}" {{ $req('expected_rent') }} class="{{ $ic }}">
            @error('expected_rent')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('expected_rent') !!}
 </div>
        @endif
        @if($fc('expected_sale_price')->keep_field)
        <div><label class="{{ $lc }}">Expected Sale Price (₹) {!! $ast('expected_sale_price') !!}</label>
            <input type="number" step="0.01" min="0" name="expected_sale_price" value="{{ $v('expected_sale_price') }}" {{ $req('expected_sale_price') }} class="{{ $ic }}">
            @error('expected_sale_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('expected_sale_price') !!}
 </div>
        @endif
        @if($fc('security_deposit_months')->keep_field)
        <div><label class="{{ $lc }}">Security Deposit (months) {!! $ast('security_deposit_months') !!}</label>
            <input type="number" step="0.1" min="0" max="60" name="security_deposit_months" value="{{ $v('security_deposit_months') }}" {{ $req('security_deposit_months') }} class="{{ $ic }}">
            @error('security_deposit_months')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('security_deposit_months') !!}
 </div>
        @endif
        @if($fc('lock_in_years')->keep_field)
        <div><label class="{{ $lc }}">Lock-in Period (years) {!! $ast('lock_in_years') !!}</label>
            <input type="number" step="0.1" min="0" max="99" name="lock_in_years" value="{{ $v('lock_in_years') }}" {{ $req('lock_in_years') }} class="{{ $ic }}">
            @error('lock_in_years')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('lock_in_years') !!}
 </div>
        @endif
        @if($fc('available_from')->keep_field)
        <div><label class="{{ $lc }}">Available From Date {!! $ast('available_from') !!}</label>
            <input type="date" name="available_from" {{ $req('available_from') }}
                value="{{ old('available_from', ($entry && $entry->available_from) ? $entry->available_from->format('Y-m-d') : '') }}"
                class="{{ $ic }}">
            @error('available_from')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('available_from') !!}
 </div>
        @endif
    </div>
</div>

{{-- ══ H. Surroundings & Environment ════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'H. Surroundings & Environment') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="H. Surroundings &amp; Environment">H. Surroundings &amp; Environment</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('approach_road_width')->keep_field)
        <div><label class="{{ $lc }}">Approach Road Width (ft) {!! $ast('approach_road_width') !!}</label>
            <input type="number" step="0.01" min="0" name="approach_road_width" value="{{ $v('approach_road_width') }}" {{ $req('approach_road_width') }} class="{{ $ic }}">
            @error('approach_road_width')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('approach_road_width') !!}
 </div>
        @endif
        @if($fc('top_neighbouring_companies')->keep_field)
        <div class="sm:col-span-2"><label class="{{ $lc }}">Top Neighbouring Companies {!! $ast('top_neighbouring_companies') !!}</label>
            <textarea name="top_neighbouring_companies" rows="2" {{ $req('top_neighbouring_companies') }} class="{{ $ic }}">{{ $v('top_neighbouring_companies') }}</textarea>
            @error('top_neighbouring_companies')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('top_neighbouring_companies') !!}
 </div>
        @endif
        @if($fc('flood_risk')->keep_field)
        <div><label class="{{ $lc }}">Flood / Water-Logging Risk {!! $ast('flood_risk') !!}</label>
            <select name="flood_risk" {{ $req('flood_risk') }} class="{{ $sc }}">
                <option value="">— Select —</option>
                @foreach(['None','Low','Moderate','High'] as $o)
                    <option value="{{ $o }}" {{ $sel('flood_risk',$o) }}>{{ $o }}</option>
                @endforeach
            </select>
            @error('flood_risk')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('flood_risk') !!}
 </div>
        @endif
    </div>
</div>

{{-- ══ I. Health & Emergency Nearby ══════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'I. Health & Emergency Nearby') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="I. Health &amp; Emergency Nearby">I. Health &amp; Emergency Facilities Nearby</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('nearest_hospital_km')->keep_field)
        <div><label class="{{ $lc }}">Nearest Hospital (km) {!! $ast('nearest_hospital_km') !!}</label>
            <input type="number" step="0.01" min="0" name="nearest_hospital_km" value="{{ $v('nearest_hospital_km') }}" {{ $req('nearest_hospital_km') }} class="{{ $ic }}">
            @error('nearest_hospital_km')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_hospital_km') !!}
 </div>
        @endif
        @if($fc('nearest_fire_station_km')->keep_field)
        <div><label class="{{ $lc }}">Nearest Fire Station (km) {!! $ast('nearest_fire_station_km') !!}</label>
            <input type="number" step="0.01" min="0" name="nearest_fire_station_km" value="{{ $v('nearest_fire_station_km') }}" {{ $req('nearest_fire_station_km') }} class="{{ $ic }}">
            @error('nearest_fire_station_km')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_fire_station_km') !!}
 </div>
        @endif
        @if($fc('nearest_police_station_km')->keep_field)
        <div><label class="{{ $lc }}">Nearest Police Station (km) {!! $ast('nearest_police_station_km') !!}</label>
            <input type="number" step="0.01" min="0" name="nearest_police_station_km" value="{{ $v('nearest_police_station_km') }}" {{ $req('nearest_police_station_km') }} class="{{ $ic }}">
            @error('nearest_police_station_km')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('nearest_police_station_km') !!}
 </div>
        @endif
    </div>
</div>

{{-- ══ J. Photographs ════════════════════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(true, 'J. Photographs') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy">J. Photographs</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="px-5 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach($slots as $index => $slotLabel)
            @php $existing = $entry?->photos?->firstWhere('slot_label', $slotLabel); @endphp
            <div class="flex flex-col items-center">
                <div class="w-full aspect-square mb-2 rounded-lg overflow-hidden border border-gray-200 bg-gray-50" id="preview-{{ $index }}">
                    @if($existing)
                        <img src="{{ asset('images/property_photos/'.basename($existing->file_path)) }}" alt="{{ $slotLabel }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center" id="placeholder-{{ $index }}">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <label class="text-xs text-gray-600 text-center font-medium mb-1 leading-tight"><b>{{ $slotLabel }}</b></label>
                <input type="file" name="photos[{{ $index }}]" id="photo-{{ $index }}"
                    capture="camera" accept="image/*"
                    onchange="previewPhoto(this, {{ $index }})"
                    class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-zendo-gold file:text-white hover:file:bg-opacity-90 cursor-pointer">
                @if(isset($fieldRemarks['photo_' . $index]) && $fieldRemarks['photo_' . $index])
                    <div class="mt-2 px-2 py-1.5 bg-red-50 border border-red-200 rounded text-center">
                        <p class="text-[10px] font-semibold text-red-700 mb-0.5">⚠ Remark:</p>
                        <p class="text-xs text-red-800">{{ $fieldRemarks['photo_' . $index] }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- ══ K. General Remarks ════════════════════════════════════════════════════ --}}
<div class="{{ $sec }}" x-data="{{ $sd(false, 'K. General Remarks') }}">
    <div class="{{ $sh }}" @click="open=!open">
        <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="K. General Remarks">K. General Remarks &amp; Field Observations</h3>
        {!! $counter !!}
    </div>
    <div x-show="open" class="{{ $sb }}">
        @if($fc('remarks')->keep_field)
        <div class="sm:col-span-2 lg:col-span-3">
            <label class="{{ $lc }}">Remarks / Observations {!! $ast('remarks') !!}</label>
            <textarea name="remarks" rows="3" {{ $req('remarks') }} class="{{ $ic }}">{{ $v('remarks') }}</textarea>
            @error('remarks')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
 {!! $rmk('remarks') !!}
 </div>
        @endif
    </div>
</div>

<script>
function sectionCounter(defaultOpen, serverErrorCount = 0) {
    return {
        open: defaultOpen,
        filled: 0,
        total: 0,
        errorCount: serverErrorCount,
        init() {
            this.$nextTick(() => this._count());
            // Re-count on any input/change inside this section
            this.$el.addEventListener('input',  () => this._count());
            this.$el.addEventListener('change', () => this._count());
        },
        _count() {
            // Photo section opts out entirely
            if (this.$el.hasAttribute('data-no-counter')) {
                this.total = 0;
                return;
            }
            const controls = this.$el.querySelectorAll(
                'input:not([type="file"]):not([type="hidden"]), select, textarea'
            );
            let filled = 0, total = 0;
            controls.forEach(el => {
                total++;
                const val = el.value ? el.value.trim() : '';
                // Count as filled if:
                // - Select with any selected value (including "0" for No)
                // - Input/textarea with non-empty value (including "0")
                // Exception: empty string or placeholder "— Select —" counts as empty
                if (el.tagName === 'SELECT') {
                    // Select is filled if it has a value and it's not the empty placeholder option
                    if (val !== '') filled++;
                } else {
                    // For inputs/textareas: any non-empty value counts (including "0")
                    if (val !== '') filled++;
                }
            });
            // Count file inputs separately
            const fileInputs = this.$el.querySelectorAll('input[type="file"]');
            fileInputs.forEach(el => {
                total++;
                // Filled if a new file is selected OR an existing preview image is shown
                const previewId = el.id ? el.id.replace('photo-', 'preview-') : null;
                const previewEl = previewId ? document.getElementById(previewId) : null;
                const hasExisting = previewEl ? !!previewEl.querySelector('img') : false;
                if (el.files && el.files.length > 0) filled++;
                else if (hasExisting) filled++;
            });
            this.filled = filled;
            this.total  = total;
        }
    };
}
function previewPhoto(input, index) {
    const file = input.files[0];
    if (!file) return;
    const preview = document.getElementById('preview-' + index);
    const reader = new FileReader();
    reader.onload = e => { preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover">'; };
    reader.readAsDataURL(file);
}
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('contextmenu', e => { e.preventDefault(); });
    });
});
</script>
