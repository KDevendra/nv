{{-- Shared form partial: create & edit. Expects: $entry (null on create), $slots, $fieldConfigs --}}
@php
    if (!isset($fieldConfigs)) {
        $fieldConfigs = \App\Models\PropertyFieldConfig::allKeyed();
    }
    if (!isset($fieldRemarks)) {
        $fieldRemarks = [];
    }
    $fc = fn(string $k) => \App\Models\PropertyFieldConfig::forField($k);
    $v = fn($f) => old($f, $entry?->$f ?? '');
    $sel = fn($f, $o) => old($f, $entry?->$f ?? '') == $o ? 'selected' : '';
    $bv = fn($f) => old($f, $entry?->$f ?? '') !== '' ? (int) old($f, $entry?->$f ?? '') : '';

    // Supply head adds properties remotely (never visits the site), so the
    // location section swaps the field officer's live-GPS readout for a
    // search-and-select map picker — see the location section below and its
    // script block near the end of this file.
    $isRemoteEntry = auth()->check() && in_array(auth()->user()->role, ['supply_head', 'owner']);
    $isSupplyHead = auth()->check() && auth()->user()->role === 'supply_head';

    // Supply heads can be exempted from PropertyFieldConfig's mandatory
    // fields entirely via SUPPLY_HEAD_ENFORCE_REQUIRED_FIELDS (see
    // config/property.php) — mirrors the server-side rule building in
    // SupplyHead\PropertyEntryController::validateEntry(). $mand() is the
    // single source of truth for "is this field required right now"; use it
    // instead of reading ->mandatory_field directly.
    $requiredEnforced = !$isSupplyHead || \App\Http\Controllers\SupplyHead\PropertyEntryController::enforcesRequiredFields();
    $mand = fn(string $k) => $requiredEnforced && $fc($k)->mandatory_field;

    $req = fn(string $k) => $mand($k) ? 'required' : '';
    $ast = fn(string $k) => $mand($k) ? '<span class="text-red-500 ml-0.5">*</span>' : '';

    $rmk = fn(string $k) => isset($fieldRemarks[$k]) && $fieldRemarks[$k]
        ? '<div class="mt-1"><p class="text-xs text-red-800">⚠ ' . e($fieldRemarks[$k]) . '</p></div>'
        : '';

    if (!isset($correctFields)) {
        $correctFields = [];
    }
    $isRestrictedEdit = isset($entry) && $entry->status === 'rejected' && $entry->allow_resubmit && !empty($correctFields);
    $dis = fn(string $k) => $isRestrictedEdit && in_array($k, $correctFields) ? 'disabled' : '';
    $isLocked = fn(string $k) => $isRestrictedEdit && in_array($k, $correctFields);

    $__sfm = [
        'A. Location & Identification' => ['facility_type', 'property_name', 'name_full_address', 'village', 'tehsil', 'district', 'state', 'country', 'postal_address_pin', 'nearest_city', 'nearest_highway', 'nearest_railway_station', 'nearest_airport', 'owner_contact_name', 'owner_contact_phone', 'owner_email'],
        'B. Legal & Statutory Compliance' => ['tenure', 'approved_land_use', 'fire_noc', 'clu_conversion_status', 'pollution_noc', 'pollution_category', 'occupancy_certificate'],
        'C. Property Dimensions' => ['area_unit', 'plot_area', 'built_up_area', 'carpet_area', 'available_area', 'clear_height_highest', 'clear_height_side', 'shed_width', 'shed_length', 'number_of_floors', 'fsi_far'],
        'D. Dock, Exit & Width Details' => ['dock_door_count', 'dock_front', 'dock_left', 'dock_right', 'dock_back', 'has_dock_leveller', 'dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back', 'fire_exit_front', 'fire_exit_left', 'fire_exit_right', 'fire_exit_back', 'canopy_width_front', 'canopy_length_front', 'canopy_width_left', 'canopy_length_left', 'canopy_width_right', 'canopy_length_right', 'canopy_width_back', 'canopy_length_back', 'road_width_front', 'road_width_left', 'road_width_right', 'road_width_back'],
        'E. Facility Details' => ['has_offices', 'no_of_offices', 'office_sizes', 'canteen', 'canteen_size', 'stp_plant', 'stp_capacity', 'washrooms', 'no_of_urinals', 'no_of_closets', 'female_washroom', 'driver_rest_room', 'mezzanine', 'mezzanine_size', 'structure_type', 'flooring_type', 'ventilation_lighting', 'insulation_roof', 'insulation_side', 'fire_sprinkler', 'scrap_yard', 'no_of_companies_same_premise', 'extension_possible'],
        'F. Loading & Docking' => ['dock_type', 'dock_height', 'truck_movement', 'office_cabin_area'],
        'G. Utilities & Infrastructure' => ['power_sanctioned_kva', 'discom_name', 'water_source', 'water_tank_capacity', 'fire_fighting_system', 'solar'],
        'H. Financial & Lease Terms' => ['deal_type', 'expected_rent', 'expected_sale_price', 'security_deposit_months', 'lock_in_years', 'available_from'],
        'I. Surroundings & Environment' => ['approach_road_width', 'top_neighbouring_companies', 'flood_risk'],
        'J. Health & Emergency Nearby' => ['nearest_hospital_km', 'nearest_fire_station_km', 'nearest_police_station_km'],
        'K. Photographs' => collect(range(0, 7))->map(fn($i) => 'photo_' . $i)->toArray(),
        'L. General Remarks' => ['remarks'],
    ];
    $__eb = isset($errors) ? $errors->getBag('default') : null;
    $sec_errs = fn(string $t) => $__eb ? collect($__sfm[$t] ?? [])->filter(fn($f) => $__eb->has($f) || $__eb->has($f . '.*'))->count() : 0;

    // Section error counts indexed by step number for JS.
    // A 13th entry (always 0) is appended for the trailing Review & Submit
    // step, which has no fields of its own.
    $stepErrCounts = array_values(array_map(fn($t) => $sec_errs($t), array_keys($__sfm)));
    $stepErrCounts[] = 0;

    // Section remark counts — how many fields per section the supply head
    // flagged as incorrect with a remark, so the officer can see at a glance
    // which sections still need attention (independent of form validation).
    $sec_remarks = fn(string $t) => collect($__sfm[$t] ?? [])->filter(fn($f) => !empty($fieldRemarks[$f]))->count();
    $stepRemarkCounts = array_values(array_map(fn($t) => $sec_remarks($t), array_keys($__sfm)));
    $stepRemarkCounts[] = 0;
    // First step with errors (0-indexed), -1 if none
    $firstErrStep = -1;
    foreach ($stepErrCounts as $i => $c) {
        if ($c > 0) {
            $firstErrStep = $i;
            break;
        }
    }

    // Whether each lettered step (A-L) has all of its currently-mandatory,
    // currently-kept fields filled in — drives how far the wizard progress
    // dots unlock. Photos (K) are always server-side optional, so treat
    // that step as complete regardless of upload state.
    $stepComplete = [];
    foreach ($__sfm as $secName => $fields) {
        if ($secName === 'K. Photographs') {
            $stepComplete[] = true;
            continue;
        }
        $complete = true;
        foreach ($fields as $f) {
            if (!$fc($f)->keep_field || !$mand($f)) {
                continue;
            }
            // Mirrors the display-only "India" default shown for country —
            // that default isn't reflected by $v() itself.
            $val = $f === 'country' ? ($v($f) ?: 'India') : $v($f);
            // office_sizes (array-cast) can't go through trim()/(string) —
            // check emptiness directly. Boolean-cast fields (canteen, etc.)
            // stringify `false` to '', which would wrongly read an explicit
            // "No" as unanswered — any real bool is always a filled answer.
            $isBlank = match (true) {
                is_array($val) => empty($val),
                is_bool($val) => false,
                default => trim((string) $val) === '',
            };
            if ($isBlank) {
                $complete = false;
                break;
            }
        }
        $stepComplete[] = $complete;
    }
    // First incomplete lettered step unlocks navigation up to (and including)
    // itself; if every lettered step is complete, the Review step (index 12) unlocks too.
    // This progressive lock only makes sense while an entry is being filled in
    // for the first time. Once it has been submitted at least once (recheck,
    // rejected-with-resubmit, etc.), every mandatory field was already filled
    // at submit time, so the officer should be free to jump straight to
    // whichever section needs fixing instead of re-earning access step by step.
    $wizInitFrontier = 12;
    if (!$entry || !$entry->submitted_at) {
        foreach ($stepComplete as $i => $complete) {
            if (!$complete) {
                $wizInitFrontier = $i;
                break;
            }
        }
    }

    $ic = new class($errors ?? null) {
        private $errors;
        public function __construct($errors) { $this->errors = $errors; }
        public function __invoke(string $k = ''): string {
            $hasErr = $k && $this->errors && ($this->errors->has($k) || $this->errors->has($k . '.*'));
            $errCls = $hasErr ? 'border-red-500 ring-2 ring-red-300' : 'border-gray-300';
            return 'w-full px-3 py-2 border ' . $errCls . ' rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed';
        }
        public function __toString(): string { return $this->__invoke(); }
    };
    $sc = new class($errors ?? null) {
        private $errors;
        public function __construct($errors) { $this->errors = $errors; }
        public function __invoke(string $k = ''): string {
            $hasErr = $k && $this->errors && ($this->errors->has($k) || $this->errors->has($k . '.*'));
            $errCls = $hasErr ? 'border-red-500 ring-2 ring-red-300' : 'border-gray-300';
            return 'w-full px-3 py-2 border ' . $errCls . ' rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent text-sm bg-white disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed';
        }
        public function __toString(): string { return $this->__invoke(); }
    };
    $lc = 'block text-sm font-medium text-gray-700 mb-1';
    $sb = 'px-5 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4';
@endphp

<style>
select:disabled,
select[disabled],
input:disabled,
input[disabled],
textarea:disabled,
textarea[disabled] {
    background-color: #f3f4f6 !important;
    color: #374151 !important;
    cursor: not-allowed !important;
}
</style>

@php
    // Zone shown to the field officer: taken from the entry once it exists,
    // otherwise from the logged-in officer's own assignment. The entry is
    // routed to the supply heads of this zone on submit — the officer never
    // picks it, so it is displayed read-only.
    $entryZone = $entry?->zone ?? (auth()->check() ? auth()->user()->zone : null);
@endphp

@if(auth()->check() && auth()->user()->role === 'field_officer')
    <div class="mb-4 flex items-center justify-between gap-3 rounded-lg border border-zendo-gold/40 bg-amber-50 px-4 py-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-zendo-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-medium">Zone</p>
                <p class="text-base font-semibold text-zendo-navy">{{ $entryZone?->name ?? 'Not assigned' }}</p>
            </div>
        </div>
        @unless($entryZone)
            <p class="text-xs text-red-700 text-right max-w-xs">
                No zone is assigned to your account — ask an administrator to assign one before submitting.
            </p>
        @endunless
    </div>
@endif

@unless($requiredEnforced)
    <div class="mb-4 flex items-start gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>All fields are optional right now — fill in whatever you have and submit. Leave the rest blank.</span>
    </div>
@endunless

{{-- Captured client-side via the browser's Geolocation API — see script at the bottom of this file --}}
<input type="hidden" name="form_submited_location" id="form_submited_location"
    value="{{ old('form_submited_location', $entry?->form_submited_location ?? '') }}">

{{-- ═══════════════════════════════════════════════════
WIZARD — TOP STEP PROGRESS BAR
═══════════════════════════════════════════════════ --}}
<div id="wizard-progress" class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm -mx-1 px-3 py-2 mb-4">
    {{-- Mobile: "Step N of 12 · Title" --}}
    <div class="flex items-center justify-between sm:hidden">
        <span id="wiz-mobile-label" class="text-sm font-semibold text-zendo-navy"></span>
        <span id="wiz-mobile-count" class="text-xs text-gray-400"></span>
    </div>
    {{-- Desktop: letter circles --}}
    <div class="hidden sm:flex items-center gap-1">
        @php
            $stepLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', '✓'];
            $stepTitles = [
                'Location',
                'Legal',
                'Dimensions',
                'Docks',
                'Facilities',
                'Loading',
                'Utilities',
                'Financial',
                'Surroundings',
                'Emergency',
                'Photos',
                'Remarks',
                'Review'
            ];
        @endphp
        @foreach($stepLetters as $i => $ltr)
            <div class="flex-1 flex items-center">
                <button type="button" onclick="wizardGoTo({{ $i }})" id="wiz-dot-{{ $i }}"
                    title="{{ $ltr === '✓' ? $stepTitles[$i] : $stepLetters[$i] . '. ' . $stepTitles[$i] }}" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all flex-shrink-0
                               border-2 border-transparent wiz-dot" data-step="{{ $i }}">
                    {{-- Error count badge shown if step has errors; otherwise a remark
                         count badge if the supply head flagged fields in this section --}}
                    @if($stepErrCounts[$i] > 0)
                        <span class="relative">{{ $ltr }}<span
                                class="absolute -top-1 -right-2 w-3 h-3 bg-red-500 rounded-full text-[8px] text-white flex items-center justify-center">!</span></span>
                    @elseif($stepRemarkCounts[$i] > 0)
                        <span class="relative">{{ $ltr }}<span
                                class="absolute -top-1 -right-2 min-w-[0.75rem] h-3 px-0.5 bg-amber-500 rounded-full text-[8px] text-white flex items-center justify-center">{{ $stepRemarkCounts[$i] }}</span></span>
                    @else
                        {{ $ltr }}
                    @endif
                </button>
                @if($i < 12)
                    <div id="wiz-line-{{ $i }}" class="flex-1 h-0.5 bg-gray-200 mx-0.5 wiz-line"></div>
                @endif
            </div>
        @endforeach
    </div>
    {{-- Step title bar (desktop) --}}
    <div class="hidden sm:block mt-1.5">
        <p id="wiz-title" class="text-xs text-gray-500 text-center font-medium"></p>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
STEP 0 — A. Location & Identification
══════════════════════════════════════════════════════ --}}
<div class="wizard-step" data-step="0" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="A. Location &amp; Identification">A.
                Location &amp; Identification</h3>
            @if($sec_errs('A. Location & Identification') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">
                    {{ $sec_errs('A. Location & Identification') }} error(s)
                </span>
            @endif
            @if($sec_errs('A. Location & Identification') == 0 && $sec_remarks('A. Location & Identification') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('A. Location & Identification') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">

            @if($fc('facility_type')->keep_field)
                <div>
                    <label class="{{ $lc }}">Facility Type {!! $ast('facility_type') !!}</label>
                    <select name="facility_type" {{ $req('facility_type') }} class="{{ $sc('facility_type') }}">
                        <option value="">— Select —</option>
                        @foreach(['Warehouse', 'Industrial Shed', 'Cold Storage', 'Open Land', 'Commercial Space', 'Factory'] as $o)
                            <option value="{{ $o }}" {{ $sel('facility_type', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('facility_type')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('facility_type') !!}
                </div>
            @endif

            @if($fc('property_name')->keep_field)
                <div class="md:col-span-3">
                    <label class="{{ $lc }}">Name of Property {!! $ast('property_name') !!}</label>
                    <input type="text" name="property_name" value="{{ $v('property_name') }}" {{ $req('property_name') }}
                        class="{{ $ic('property_name') }}">
                    @error('property_name')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('property_name') !!}
                </div>
            @endif

            @if($fc('name_full_address')->keep_field)
                <div class="md:col-span-3 lg:col-span-3">
                    <label class="{{ $lc }}">Address {!! $ast('name_full_address') !!}</label>
                    <textarea name="name_full_address" rows="2" {{ $req('name_full_address') }}
                        class="{{ $ic('name_full_address') }}">{{ $v('name_full_address') }}</textarea>
                    @error('name_full_address')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('name_full_address') !!}
                </div>
            @endif

            @if($fc('postal_address_pin')->keep_field)
                <div>
                    <label class="{{ $lc }}">PIN Code {!! $ast('postal_address_pin') !!}</label>
                    <input type="text" name="postal_address_pin" value="{{ $v('postal_address_pin') }}" {{ $req('postal_address_pin') }} maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                        class="{{ $ic('postal_address_pin') }}">
                    @error('postal_address_pin')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('postal_address_pin') !!}
                </div>
            @endif

            @if($fc('village')->keep_field)
                <div>
                    <label class="{{ $lc }}">Village {!! $ast('village') !!}</label>
                    <input type="text" name="village" value="{{ $v('village') }}" {{ $req('village') }} class="{{ $ic('village') }}">
                    @error('village')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('village') !!}
                </div>
            @endif

            @if($fc('tehsil')->keep_field)
                <div>
                    <label class="{{ $lc }}">Tehsil {!! $ast('tehsil') !!}</label>
                    <input type="text" name="tehsil" value="{{ $v('tehsil') }}" {{ $req('tehsil') }} class="{{ $ic('tehsil') }}">
                    @error('tehsil')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('tehsil') !!}
                </div>
            @endif

            @if($fc('district')->keep_field)
                <div>
                    <label class="{{ $lc }}">District {!! $ast('district') !!}</label>
                    <input type="text" name="district" value="{{ $v('district') }}" {{ $req('district') }}
                        class="{{ $ic('district') }}">
                    @error('district')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('district') !!}
                </div>
            @endif

            @if($fc('state')->keep_field)
                <div>
                    <label class="{{ $lc }}">State {!! $ast('state') !!}</label>
                    <input type="text" name="state" value="{{ $v('state') }}" {{ $req('state') }} class="{{ $ic('state') }}">
                    @error('state')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('state') !!}
                </div>
            @endif

            @if($fc('country')->keep_field)
                <div>
                    <label class="{{ $lc }}">Country {!! $ast('country') !!}</label>
                    <input type="text" name="country" value="{{ $v('country') ?: 'India' }}" {{ $req('country') }}
                        class="{{ $ic('country') }}">
                    @error('country')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('country') !!}
                </div>
            @endif

            @if($fc('nearest_city')->keep_field)
                <div>
                    <label class="{{ $lc }}">Nearest City {!! $ast('nearest_city') !!}</label>
                    <input type="text" name="nearest_city" value="{{ $v('nearest_city') }}" {{ $req('nearest_city') }}
                        class="{{ $ic('nearest_city') }}">
                    @error('nearest_city')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_city') !!}
                </div>
            @endif

            @if($fc('nearest_highway')->keep_field)
                <div>
                    <label class="{{ $lc }}">Road Connectivity / Nearest Highway {!! $ast('nearest_highway') !!}</label>
                    <input type="text" name="nearest_highway" value="{{ $v('nearest_highway') }}" {{ $req('nearest_highway') }} class="{{ $ic('nearest_highway') }}">
                    @error('nearest_highway')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_highway') !!}
                </div>
            @endif

            @if($fc('nearest_railway_station')->keep_field)
                <div>
                    <label class="{{ $lc }}">Nearest Railway Station {!! $ast('nearest_railway_station') !!}</label>
                    <input type="text" name="nearest_railway_station" value="{{ $v('nearest_railway_station') }}" {{ $req('nearest_railway_station') }} class="{{ $ic('nearest_railway_station') }}">
                    @error('nearest_railway_station')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_railway_station') !!}
                </div>
            @endif

            @if($fc('nearest_airport')->keep_field)
                <div>
                    <label class="{{ $lc }}">Nearest Airport {!! $ast('nearest_airport') !!}</label>
                    <input type="text" name="nearest_airport" value="{{ $v('nearest_airport') }}" {{ $req('nearest_airport') }} class="{{ $ic('nearest_airport') }}">
                    @error('nearest_airport')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_airport') !!}
                </div>
            @endif

            @if($fc('owner_contact_name')->keep_field)
                <div>
                    <label class="{{ $lc }}">Owner Name {!! $ast('owner_contact_name') !!}</label>
                    <input type="text" name="owner_contact_name" value="{{ $v('owner_contact_name') }}" {{ $req('owner_contact_name') }} class="{{ $ic('owner_contact_name') }}">
                    @error('owner_contact_name')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('owner_contact_name') !!}
                </div>
            @endif

            @if($fc('owner_contact_phone')->keep_field)
                <div>
                    <label class="{{ $lc }}">Owner Contact Number {!! $ast('owner_contact_phone') !!}</label>
                    <input type="text" name="owner_contact_phone" value="{{ $v('owner_contact_phone') }}" {{ $req('owner_contact_phone') }} maxlength="10" inputmode="numeric" pattern="[6-9][0-9]{9}"
                        title="Enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9" class="{{ $ic('owner_contact_phone') }}">
                    @error('owner_contact_phone')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('owner_contact_phone') !!}
                </div>
            @endif

            @if($fc('owner_email')->keep_field)
                <div>
                    <label class="{{ $lc }}">Owner E-mail {!! $ast('owner_email') !!}</label>
                    <input type="email" name="owner_email" value="{{ $v('owner_email') }}" {{ $req('owner_email') }}
                        class="{{ $ic('owner_email') }}">
                    @error('owner_email')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('owner_email') !!}
                </div>
            @endif

        </div>
    </div>

    {{-- Property location — search-and-select (supply head) or live GPS readout (field officer / owner), reverse-geocoded --}}
    <div class="mb-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
            {{ $isRemoteEntry ? 'Property Current Location' : 'Field Officer Current Location' }}
        </p>

        @if($isRemoteEntry)
            <div class="relative mb-2">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                </svg>
                <input type="text" id="supply-head-location-search" autocomplete="off"
                    placeholder="Search for the property's location — locality, city, landmark…"
                    class="{{ $ic }} pl-9">
            </div>
            <p class="text-xs text-gray-400 mb-2">Since you aren't at the property yourself, search for its location above and pick the exact match from the results.</p>
        @endif

        <div id="current-location-line"
            class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm">
            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
            <span id="current-location-country" class="text-gray-500"></span>
            <span id="current-location-sep" class="text-gray-300"></span>
            <span id="current-location-rest" class="text-gray-400 flex-1">{{ $isRemoteEntry ? 'No location selected yet — search and pick above.' : 'Detecting current location…' }}</span>
            <a id="current-location-maps-link" href="#" target="_blank" rel="noopener"
                class="hidden items-center gap-1 text-xs font-semibold text-zendo-navy hover:underline flex-shrink-0 whitespace-nowrap">
                View on Google Maps
            </a>
        </div>
        <div id="mappls-map" class="w-full h-64 rounded-lg border border-gray-200 mt-2 bg-gray-100"></div>
    </div>
</div>{{-- /step 0 --}}


{{-- ══ STEP 1 — B. Legal & Statutory Compliance ══ --}}
<div class="wizard-step" data-step="1" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="B. Legal &amp; Statutory Compliance">
                B. Legal &amp; Statutory Compliance</h3>
            @if($sec_errs('B. Legal & Statutory Compliance') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('B. Legal & Statutory Compliance') }}
                    error(s)</span>
            @endif
            @if($sec_errs('B. Legal & Statutory Compliance') == 0 && $sec_remarks('B. Legal & Statutory Compliance') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('B. Legal & Statutory Compliance') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">

            @if($fc('tenure')->keep_field)
                <div>
                    <label class="{{ $lc }}">Tenure {!! $ast('tenure') !!}</label>
                    <select name="tenure" {{ $req('tenure') }} class="{{ $sc('tenure') }}">
                        <option value="">— Select —</option>
                        @foreach(['Freehold', 'Leasehold', 'Other'] as $o)
                            <option value="{{ $o }}" {{ $sel('tenure', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('tenure')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('tenure') !!}
                </div>
            @endif

            @if($fc('approved_land_use')->keep_field)
                <div>
                    <label class="{{ $lc }}">Approved Land Use {!! $ast('approved_land_use') !!}</label>
                    <select name="approved_land_use" {{ $req('approved_land_use') }} class="{{ $sc('approved_land_use') }}">
                        <option value="">— Select —</option>
                        @foreach(['Industrial', 'Commercial', 'Warehousing', 'Agricultural', 'Mixed', 'Not sure'] as $o)
                            <option value="{{ $o }}" {{ $sel('approved_land_use', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('approved_land_use')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('approved_land_use') !!}
                </div>
            @endif

            @if($fc('fire_noc')->keep_field)
                <div>
                    <label class="{{ $lc }}">Fire NOC Availability {!! $ast('fire_noc') !!}</label>
                    <select name="fire_noc" {{ $req('fire_noc') }} class="{{ $sc('fire_noc') }}">
                        <option value="">— Select —</option>
                        @foreach(['Yes', 'No', 'Applied'] as $o)
                            <option value="{{ $o }}" {{ $sel('fire_noc', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('fire_noc')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('fire_noc') !!}
                </div>
            @endif

            @if($fc('clu_conversion_status')->keep_field)
                <div>
                    <label class="{{ $lc }}">CLU / Conversion Status {!! $ast('clu_conversion_status') !!}</label>
                    <input type="text" name="clu_conversion_status" value="{{ $v('clu_conversion_status') }}" {{ $req('clu_conversion_status') }} class="{{ $ic('clu_conversion_status') }}">
                    @error('clu_conversion_status')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('clu_conversion_status') !!}
                </div>
            @endif

            @if($fc('pollution_noc')->keep_field)
                <div>
                    <label class="{{ $lc }}">Pollution NOC {!! $ast('pollution_noc') !!}</label>
                    <select name="pollution_noc" {{ $req('pollution_noc') }} class="{{ $sc('pollution_noc') }}">
                        <option value="">— Select —</option>
                        @foreach(['Yes', 'No', 'Applied'] as $o)
                            <option value="{{ $o }}" {{ $sel('pollution_noc', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('pollution_noc')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('pollution_noc') !!}
                </div>
            @endif

            @if($fc('pollution_category')->keep_field)
                <div>
                    <label class="{{ $lc }}">Pollution Category {!! $ast('pollution_category') !!}</label>
                    <input type="text" name="pollution_category" value="{{ $v('pollution_category') }}" {{ $req('pollution_category') }} class="{{ $ic('pollution_category') }}">
                    @error('pollution_category')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('pollution_category') !!}
                </div>
            @endif

            @if($fc('occupancy_certificate')->keep_field)
                <div>
                    <label class="{{ $lc }}">Occupancy Certificate {!! $ast('occupancy_certificate') !!}</label>
                    <select name="occupancy_certificate" {{ $req('occupancy_certificate') }} class="{{ $sc('occupancy_certificate') }}">
                        <option value="">— Select —</option>
                        @foreach(['Yes', 'No', 'NA'] as $o)
                            <option value="{{ $o }}" {{ $sel('occupancy_certificate', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('occupancy_certificate')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('occupancy_certificate') !!}
                </div>
            @endif

        </div>
    </div>
</div>{{-- /step 1 --}}


{{-- ══ STEP 2 — C. Property Dimensions ══ --}}
<div class="wizard-step" data-step="2" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4"
        x-data="Object.assign({}, { unit: '{{ old('area_unit', $entry?->area_unit ?? 'sq_ft') }}' })">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="C. Property Dimensions">C. Property
                Dimensions</h3>
            @if($sec_errs('C. Property Dimensions') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('C. Property Dimensions') }}
                    error(s)</span>
            @endif
            @if($sec_errs('C. Property Dimensions') == 0 && $sec_remarks('C. Property Dimensions') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('C. Property Dimensions') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">

            {{-- Area Unit Selector --}}
            <div class="md:col-span-3 lg:col-span-3 flex flex-col pb-2 border-b border-gray-100 mb-1">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Area
                        Unit</span>
                    <div class="flex rounded-lg border border-gray-300 overflow-hidden text-xs font-semibold">
                        @foreach(['sq_ft' => 'Sq Ft', 'sq_mt' => 'Sq Mt', 'sq_yd' => 'Sq Yd'] as $val => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="area_unit" value="{{ $val }}" x-model="unit" class="sr-only">
                                <span class="block px-4 py-2 transition-colors select-none"
                                    :class="unit === '{{ $val }}' ? 'bg-zendo-navy text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                                    style="{{ !$loop->last ? 'border-right: 1px solid #d1d5db;' : '' }}">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <span class="text-xs text-gray-400 italic">Applies to all area fields below</span>
                </div>
                @error('area_unit')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            @php $unitLabel = fn() => '<span class="text-gray-400 font-normal text-xs" x-text="\'(\' + unit.replace(\'_\',\' \') + \')\'">&nbsp;</span>'; @endphp

            @if($fc('plot_area')->keep_field)
                <div>
                    <label class="{{ $lc }}">Plot Area — as per CLU {!! $unitLabel() !!} {!! $ast('plot_area') !!}</label>
                    <input type="number" step="0.01" min="0" name="plot_area" value="{{ $v('plot_area') }}" {{ $req('plot_area') }} class="{{ $ic('plot_area') }}">
                    @error('plot_area')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('plot_area') !!}
                </div>
            @endif

            @if($fc('built_up_area')->keep_field)
                <div>
                    <label class="{{ $lc }}">Built-up Area {!! $unitLabel() !!} {!! $ast('built_up_area') !!}</label>
                    <input type="number" step="0.01" min="0" name="built_up_area" value="{{ $v('built_up_area') }}" {{ $req('built_up_area') }} class="{{ $ic('built_up_area') }}">
                    @error('built_up_area')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('built_up_area') !!}
                </div>
            @endif

            @if($fc('carpet_area')->keep_field)
                <div>
                    <label class="{{ $lc }}">Carpet Area {!! $unitLabel() !!} {!! $ast('carpet_area') !!}</label>
                    <input type="number" step="0.01" min="0" name="carpet_area" value="{{ $v('carpet_area') }}" {{ $req('carpet_area') }} class="{{ $ic('carpet_area') }}">
                    @error('carpet_area')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('carpet_area') !!}
                </div>
            @endif

            @if($fc('available_area')->keep_field)
                <div>
                    <label class="{{ $lc }}">Available Area {!! $unitLabel() !!} {!! $ast('available_area') !!}</label>
                    <input type="number" step="0.01" min="0" name="available_area" value="{{ $v('available_area') }}" {{ $req('available_area') }} class="{{ $ic('available_area') }}">
                    @error('available_area')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('available_area') !!}
                </div>
            @endif

            @if($fc('clear_height_highest')->keep_field)
                <div>
                    <label class="{{ $lc }}">Clear Height — Highest (ft) {!! $ast('clear_height_highest') !!}</label>
                    <input type="number" step="0.01" min="0" name="clear_height_highest"
                        value="{{ $v('clear_height_highest') }}" {{ $req('clear_height_highest') }} class="{{ $ic('clear_height_highest') }}">
                    @error('clear_height_highest')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('clear_height_highest') !!}
                </div>
            @endif

            @if($fc('clear_height_side')->keep_field)
                <div>
                    <label class="{{ $lc }}">Clear Height — Side Wall (ft) {!! $ast('clear_height_side') !!}</label>
                    <input type="number" step="0.01" min="0" name="clear_height_side" value="{{ $v('clear_height_side') }}"
                        {{ $req('clear_height_side') }} class="{{ $ic('clear_height_side') }}">
                    @error('clear_height_side')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('clear_height_side') !!}
                </div>
            @endif

            @if($fc('shed_width')->keep_field)
                <div>
                    <label class="{{ $lc }}">Shed Width (ft) {!! $ast('shed_width') !!}</label>
                    <input type="number" step="0.01" min="0" name="shed_width" value="{{ $v('shed_width') }}" {{ $req('shed_width') }} class="{{ $ic('shed_width') }}">
                    @error('shed_width')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('shed_width') !!}
                </div>
            @endif

            @if($fc('shed_length')->keep_field)
                <div>
                    <label class="{{ $lc }}">Shed Length (ft) {!! $ast('shed_length') !!}</label>
                    <input type="number" step="0.01" min="0" name="shed_length" value="{{ $v('shed_length') }}" {{ $req('shed_length') }} class="{{ $ic('shed_length') }}">
                    @error('shed_length')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('shed_length') !!}
                </div>
            @endif

            @if($fc('number_of_floors')->keep_field)
                <div>
                    <label class="{{ $lc }}">Number of Floors {!! $ast('number_of_floors') !!}</label>
                    <input type="number" min="0" name="number_of_floors" value="{{ $v('number_of_floors') }}" {{ $req('number_of_floors') }} class="{{ $ic('number_of_floors') }}">
                    @error('number_of_floors')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('number_of_floors') !!}
                </div>
            @endif

            @if($fc('fsi_far')->keep_field)
                <div>
                    <label class="{{ $lc }}">FSI / FAR {!! $ast('fsi_far') !!}</label>
                    <input type="text" name="fsi_far" value="{{ $v('fsi_far') }}" {{ $req('fsi_far') }} class="{{ $ic('fsi_far') }}">
                    @error('fsi_far')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('fsi_far') !!}
                </div>
            @endif

        </div>
    </div>
</div>{{-- /step 2 --}}


{{-- ══ STEP 3 — D. Dock, Exit & Width Details ══ --}}
<div class="wizard-step" data-step="3" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="D. Dock, Exit &amp; Width Details">D.
                Dock, Exit &amp; Width Details</h3>
            @if($sec_errs('D. Dock, Exit & Width Details') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('D. Dock, Exit & Width Details') }}
                    error(s)</span>
            @endif
            @if($sec_errs('D. Dock, Exit & Width Details') == 0 && $sec_remarks('D. Dock, Exit & Width Details') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('D. Dock, Exit & Width Details') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="px-5 py-5 space-y-6">

            {{-- Dock Doors --}}
            <div x-data="{
            dock_front: {{ (int) ($entry?->dock_front ?? 0) }},
            dock_left:  {{ (int) ($entry?->dock_left ?? 0) }},
            dock_right: {{ (int) ($entry?->dock_right ?? 0) }},
            dock_back:  {{ (int) ($entry?->dock_back ?? 0) }},
            get total() { return (parseInt(this.dock_front)||0)+(parseInt(this.dock_left)||0)+(parseInt(this.dock_right)||0)+(parseInt(this.dock_back)||0); }
        }">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Dock Doors</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">
                    @if($fc('dock_front')->keep_field)
                        <div>
                            <label class="{{ $lc }}">Front {!! $ast('dock_front') !!}</label>
                            <input type="number" min="0" name="dock_front" x-model.number="dock_front"
                                value="{{ $v('dock_front') }}" {{ $req('dock_front') }} class="{{ $ic('dock_front') }}">
                            @error('dock_front')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_left')->keep_field)
                        <div>
                            <label class="{{ $lc }}">Left {!! $ast('dock_left') !!}</label>
                            <input type="number" min="0" name="dock_left" x-model.number="dock_left"
                                value="{{ $v('dock_left') }}" {{ $req('dock_left') }} class="{{ $ic('dock_left') }}">
                            @error('dock_left')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_right')->keep_field)
                        <div>
                            <label class="{{ $lc }}">Right {!! $ast('dock_right') !!}</label>
                            <input type="number" min="0" name="dock_right" x-model.number="dock_right"
                                value="{{ $v('dock_right') }}" {{ $req('dock_right') }} class="{{ $ic('dock_right') }}">
                            @error('dock_right')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_back')->keep_field)
                        <div>
                            <label class="{{ $lc }}">Back {!! $ast('dock_back') !!}</label>
                            <input type="number" min="0" name="dock_back" x-model.number="dock_back"
                                value="{{ $v('dock_back') }}" {{ $req('dock_back') }} class="{{ $ic('dock_back') }}">
                            @error('dock_back')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_door_count')->keep_field)
                        <div>
                            <label class="{{ $lc }} flex items-center gap-1">Total <span
                                     class="text-[10px] text-gray-400 font-normal">(auto)</span>
                                {!! $ast('dock_door_count') !!}</label>
                            <div class="relative">
                                <input type="number" min="0" name="dock_door_count" :value="total" readonly
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700 cursor-not-allowed font-semibold">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400">=
                                    F+L+R+B</span>
                            </div>
                            @error('dock_door_count')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                </div>
            </div>

            {{-- Dock Levellers --}}
            @php
                // Respect old() first (validation-failure redisplay), then the
                // saved entry, defaulting to "No" — same fallback order as the
                // Office Space widget above, so a never-touched toggle still
                // submits an explicit answer instead of leaving the radio
                // group entirely unchecked (which used to omit has_dock_leveller
                // from the request and fail server-side "required" silently).
                $oldHasLev = old('has_dock_leveller', $entry?->has_dock_leveller);
                $initHasLev = ($oldHasLev === true || $oldHasLev === '1' || $oldHasLev == 1) ? 'true' : 'false';
            @endphp
            <div x-data="{
            hasLev: {{ $initHasLev }},
            lev_front: {{ (int) old('dock_leveller_front', $entry?->dock_leveller_front ?? 0) }},
            lev_left:  {{ (int) old('dock_leveller_left', $entry?->dock_leveller_left ?? 0) }},
            lev_right: {{ (int) old('dock_leveller_right', $entry?->dock_leveller_right ?? 0) }},
            lev_back:  {{ (int) old('dock_leveller_back', $entry?->dock_leveller_back ?? 0) }},
            get total() { if(this.hasLev!==true)return 0; return (parseInt(this.lev_front)||0)+(parseInt(this.lev_left)||0)+(parseInt(this.lev_right)||0)+(parseInt(this.lev_back)||0); },
            setNo() {
                this.hasLev=false;
                this.lev_front=0;
                this.lev_left=0;
                this.lev_right=0;
                this.lev_back=0;
            }
        }">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Dock Levellers</h4>
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-sm text-gray-600 font-medium">Dock Levellers Available?</span>
                    <div class="flex rounded-lg border border-gray-300 overflow-hidden text-xs font-semibold">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="has_dock_leveller" value="1" x-model.number="hasLev"
                                @change="hasLev=true" {{ $oldHasLev === true || $oldHasLev == '1' ? 'checked' : '' }}
                                class="sr-only">
                            <span class="block px-5 py-2 transition-colors border-r border-gray-300"
                                :class="hasLev===true ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'">Yes</span>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="has_dock_leveller" value="0" @change="setNo()" {{ $oldHasLev === false || $oldHasLev === null || $oldHasLev == '0' ? 'checked' : '' }}
                                class="sr-only">
                            <span class="block px-5 py-2 transition-colors"
                                :class="hasLev===false ? 'bg-red-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'">No</span>
                        </label>
                    </div>
                </div>
                @error('has_dock_leveller')
                <p class="mt-1 text-xs text-red-600 font-medium mb-3">{{ $message }}</p>@enderror
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">
                    @if($fc('dock_leveller_front')->keep_field)
                        <div>
                            <label class="{{ $lc }}" :class="hasLev===false ? 'text-gray-400' : ''">Front
                                @if($mand('dock_leveller_front'))
                                    <span x-show="hasLev===true && total===0" class="text-red-500 ml-0.5">*</span>
                                @endif
                            </label>
                            <input type="number" min="0" name="dock_leveller_front" x-model.number="lev_front"
                                :readonly="hasLev===false"
                                :required="{{ $mand('dock_leveller_front') ? '(hasLev===true && total===0)' : 'false' }}"
                                :class="hasLev===false ? 'w-full px-3 py-2 border border-gray-100 rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed' : '{{ $ic('dock_leveller_front') }}'">
                            @error('dock_leveller_front')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_leveller_left')->keep_field)
                        <div>
                            <label class="{{ $lc }}" :class="hasLev===false ? 'text-gray-400' : ''">Left
                                @if($mand('dock_leveller_left'))
                                    <span x-show="hasLev===true && total===0" class="text-red-500 ml-0.5">*</span>
                                @endif
                            </label>
                            <input type="number" min="0" name="dock_leveller_left" x-model.number="lev_left"
                                :readonly="hasLev===false"
                                :required="{{ $mand('dock_leveller_left') ? '(hasLev===true && total===0)' : 'false' }}"
                                :class="hasLev===false ? 'w-full px-3 py-2 border border-gray-100 rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed' : '{{ $ic('dock_leveller_left') }}'">
                            @error('dock_leveller_left')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_leveller_right')->keep_field)
                        <div>
                            <label class="{{ $lc }}" :class="hasLev===false ? 'text-gray-400' : ''">Right
                                @if($mand('dock_leveller_right'))
                                    <span x-show="hasLev===true && total===0" class="text-red-500 ml-0.5">*</span>
                                @endif
                            </label>
                            <input type="number" min="0" name="dock_leveller_right" x-model.number="lev_right"
                                :readonly="hasLev===false"
                                :required="{{ $mand('dock_leveller_right') ? '(hasLev===true && total===0)' : 'false' }}"
                                :class="hasLev===false ? 'w-full px-3 py-2 border border-gray-100 rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed' : '{{ $ic('dock_leveller_right') }}'">
                            @error('dock_leveller_right')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('dock_leveller_back')->keep_field)
                        <div>
                            <label class="{{ $lc }}" :class="hasLev===false ? 'text-gray-400' : ''">Back
                                @if($mand('dock_leveller_back'))
                                    <span x-show="hasLev===true && total===0" class="text-red-500 ml-0.5">*</span>
                                @endif
                            </label>
                            <input type="number" min="0" name="dock_leveller_back" x-model.number="lev_back"
                                :readonly="hasLev===false"
                                :required="{{ $mand('dock_leveller_back') ? '(hasLev===true && total===0)' : 'false' }}"
                                :class="hasLev===false ? 'w-full px-3 py-2 border border-gray-100 rounded-lg text-sm bg-gray-50 text-gray-400 cursor-not-allowed' : '{{ $ic('dock_leveller_back') }}'">
                            @error('dock_leveller_back')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <div>
                        <label class="{{ $lc }} flex items-center gap-1" :class="hasLev===false ? 'text-gray-400' : ''">
                            Total <span class="text-[10px] text-gray-400 font-normal">(auto)</span>
                        </label>
                        <div class="relative">
                            <input type="number" :value="total" readonly
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700 cursor-not-allowed font-semibold">
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400">=
                                F+L+R+B</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fire Exit Doors --}}
            <div x-data="{
            fe_front: {{ (int) ($entry?->fire_exit_front ?? 0) }},
            fe_left:  {{ (int) ($entry?->fire_exit_left ?? 0) }},
            fe_right: {{ (int) ($entry?->fire_exit_right ?? 0) }},
            fe_back:  {{ (int) ($entry?->fire_exit_back ?? 0) }},
            get total() { return (parseInt(this.fe_front)||0)+(parseInt(this.fe_left)||0)+(parseInt(this.fe_right)||0)+(parseInt(this.fe_back)||0); }
        }">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Fire Exit Doors</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">
                    @if($fc('fire_exit_front')->keep_field)
                        <div><label class="{{ $lc }}">Front {!! $ast('fire_exit_front') !!}</label><input type="number"
                                min="0" name="fire_exit_front" x-model.number="fe_front" value="{{ $v('fire_exit_front') }}"
                                {{ $req('fire_exit_front') }} class="{{ $ic('fire_exit_front') }}">@error('fire_exit_front')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('fire_exit_left')->keep_field)
                        <div><label class="{{ $lc }}">Left {!! $ast('fire_exit_left') !!}</label><input type="number"
                                min="0" name="fire_exit_left" x-model.number="fe_left" value="{{ $v('fire_exit_left') }}" {{ $req('fire_exit_left') }} class="{{ $ic('fire_exit_left') }}">@error('fire_exit_left')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('fire_exit_right')->keep_field)
                        <div><label class="{{ $lc }}">Right {!! $ast('fire_exit_right') !!}</label><input type="number"
                                min="0" name="fire_exit_right" x-model.number="fe_right" value="{{ $v('fire_exit_right') }}"
                                {{ $req('fire_exit_right') }} class="{{ $ic('fire_exit_right') }}">@error('fire_exit_right')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    @if($fc('fire_exit_back')->keep_field)
                        <div><label class="{{ $lc }}">Back {!! $ast('fire_exit_back') !!}</label><input type="number"
                                min="0" name="fire_exit_back" x-model.number="fe_back" value="{{ $v('fire_exit_back') }}" {{ $req('fire_exit_back') }} class="{{ $ic('fire_exit_back') }}">@error('fire_exit_back')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                    @endif
                    <div>
                        <label class="{{ $lc }} flex items-center gap-1">Total <span
                                class="text-[10px] text-gray-400 font-normal">(auto)</span></label>
                        <div class="relative"><input type="number" :value="total" readonly
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700 cursor-not-allowed font-semibold"><span
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400">=
                                F+L+R+B</span></div>
                    </div>
                </div>
            </div>

            {{-- Canopy Length & Width --}}
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Canopy Length &amp; Width
                    (ft)</h4>
                <div class="space-y-3">
                    @foreach(['front' => 'Front', 'left' => 'Left', 'right' => 'Right', 'back' => 'Back'] as $side => $sideLabel)
                        @php $lf = 'canopy_length_' . $side;
                        $wf = 'canopy_width_' . $side; @endphp
                        @if($fc($wf)->keep_field)
                            <div class="grid grid-cols-3 gap-3 items-end">
                                <div class="flex items-center pt-6"><span
                                        class="text-sm font-semibold text-gray-600 w-14">{{ $sideLabel }}</span></div>
                                <div>
                                    <label class="{{ $lc }}">Length (L) {!! $ast($lf) !!}</label>
                                    <input type="number" step="0.01" min="0" name="{{ $lf }}" value="{{ $v($lf) }}" {{ $req($lf) }} class="{{ $ic($lf) }}">
                                    @error($lf)
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="{{ $lc }}">Width (W) {!! $ast($wf) !!}</label>
                                    <input type="number" step="0.01" min="0" name="{{ $wf }}" value="{{ $v($wf) }}" {{ $req($wf) }} class="{{ $ic($wf) }}">
                                    @error($wf)
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Road Widths --}}
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Road Width (ft)</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(['road_width_front' => 'Front', 'road_width_left' => 'Left', 'road_width_right' => 'Right', 'road_width_back' => 'Back'] as $fk => $lbl)
                        @if($fc($fk)->keep_field)
                            <div>
                                <label class="{{ $lc }}">{{ $lbl }} {!! $ast($fk) !!}</label>
                                <input type="number" step="0.01" min="0" name="{{ $fk }}" value="{{ $v($fk) }}" {{ $req($fk) }}
                                    class="{{ $ic($fk) }}">
                                @error($fk)
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>{{-- /step 3 --}}


{{-- ══ STEP 4 — E. Facility Details ══ --}}
<div class="wizard-step" data-step="4" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="E. Facility Details">E. Facility
                Details</h3>
            @if($sec_errs('E. Facility Details') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('E. Facility Details') }}
                    error(s)</span>
            @endif
            @if($sec_errs('E. Facility Details') == 0 && $sec_remarks('E. Facility Details') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('E. Facility Details') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}" x-data="{
            canteen: '{{ old('canteen', $entry?->canteen !== null && $entry?->canteen !== '' ? (string) (int) $entry?->canteen : '') }}',
            canteen_size: '{{ addslashes(old('canteen_size', $entry?->canteen_size ?? '')) }}',
            stp_plant: '{{ old('stp_plant', $entry?->stp_plant !== null && $entry?->stp_plant !== '' ? (string) (int) $entry?->stp_plant : '') }}',
            stp_capacity: '{{ addslashes(old('stp_capacity', $entry?->stp_capacity ?? '')) }}',
            mezzanine: '{{ old('mezzanine', $entry?->mezzanine !== null && $entry?->mezzanine !== '' ? (string) (int) $entry?->mezzanine : '') }}',
            mezzanine_size: '{{ addslashes(old('mezzanine_size', $entry?->mezzanine_size ?? '')) }}'
        }">

            @if($fc('no_of_offices')->keep_field)
                @php
                    $savedOffices = [];
                    if ($entry && $entry->office_sizes) {
                        $decoded = is_array($entry->office_sizes) ? $entry->office_sizes : json_decode($entry->office_sizes, true);
                        if (is_array($decoded))
                            $savedOffices = $decoded;
                    }
                    while (count($savedOffices) < 3)
                        $savedOffices[] = ['l' => '', 'w' => ''];
                    $savedOffices = array_values($savedOffices);

                    // Default to "No" (false) when there's no entry and no old value yet,
                    // instead of leaving it unselected (null).
                    $initHasOffices = $entry?->has_offices === true
                        ? 'true'
                        : ($entry?->has_offices === false ? 'false' : 'false');
                @endphp

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('officeWidget', () => ({
                            hasOffices: {{ $initHasOffices }},
                            offices: @json($savedOffices),
                            get officeCount() { return this.hasOffices ? this.offices.filter(o => o.l || o.w).length : 0; },
                            get serialized() { if (!this.hasOffices) return '[]'; return JSON.stringify(this.offices.map(o => ({ l: o.l, w: o.w }))); },
                            setNo() { this.hasOffices = false; this.offices = [{ l: '', w: '' }, { l: '', w: '' }, { l: '', w: '' }]; },
                            addOffice() { if (this.offices.length < 3) this.offices.push({ l: '', w: '' }); },
                            removeOffice(i) { if (i > 0) this.offices.splice(i, 1); }
                        }));
                    });
                </script>

                <div class="md:col-span-3 lg:col-span-3" x-data="officeWidget">
                    <div class="flex items-center gap-4 mb-3">
                        <label class="{{ $lc }} mb-0">Office Space Availability {!! $ast('no_of_offices') !!}</label>
                        <div class="flex rounded-lg border border-gray-300 overflow-hidden text-xs font-semibold">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="has_offices" value="1" @change="hasOffices=true" {{ old('has_offices', $entry?->has_offices) === true || old('has_offices', $entry?->has_offices) == 1 ? 'checked' : '' }} class="sr-only">
                                <span class="block px-5 py-2 transition-colors border-r border-gray-300 select-none"
                                    :class="hasOffices===true ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'">
                                    Yes
                                </span>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="has_offices" value="0" @change="setNo()" {{ old('has_offices', $entry?->has_offices) === false || old('has_offices', $entry?->has_offices) === null || old('has_offices', $entry?->has_offices) == 0 ? 'checked' : '' }} class="sr-only">
                                <span class="block px-5 py-2 transition-colors select-none"
                                    :class="hasOffices===false ? 'bg-red-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'">
                                    No
                                </span>
                            </label>
                        </div>
                        <span x-show="hasOffices===true && officeCount>0"
                            class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full"
                            x-text="officeCount + (officeCount===1 ? ' office' : ' offices')"></span>
                    </div>
                    @error('has_offices')
                    <p class="mt-1 text-xs text-red-600 font-medium mb-2">{{ $message }}</p>@enderror
                    <input type="hidden" name="no_of_offices" :value="officeCount">
                    <input type="hidden" name="office_sizes" :value="serialized">
                    <div x-show="hasOffices===true" x-cloak class="space-y-3">
                        <template x-for="(office, i) in offices" :key="i">
                            <div class="flex items-end gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="w-20 flex-shrink-0">
                                    <span class="text-xs font-semibold text-gray-500 uppercase"
                                        x-text="'Office '+(i+1)"></span>
                                    @if($requiredEnforced)
                                        <span x-show="i===0" class="block text-[10px] text-red-500 font-medium">Required</span>
                                    @endif
                                    <span x-show="{{ $requiredEnforced ? 'i>0' : 'true' }}" class="block text-[10px] text-gray-400">Optional</span>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Length (L) <span
                                            x-show="{{ $requiredEnforced ? 'i===0' : 'false' }}" class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" x-model="office.l"
                                        :required="{!! $requiredEnforced ? 'i===0 && !$el.form.noValidate' : 'false' !!}" placeholder="L (ft)"
                                        class="{{ $ic('office_sizes') }} text-sm">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Width (W) <span
                                            x-show="{{ $requiredEnforced ? 'i===0' : 'false' }}" class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" x-model="office.w"
                                        :required="{!! $requiredEnforced ? 'i===0 && !$el.form.noValidate' : 'false' !!}" placeholder="W (ft)"
                                        class="{{ $ic('office_sizes') }} text-sm">
                                </div>
                                <div class="w-24 flex-shrink-0">
                                    <label class="block text-xs font-medium text-gray-400 mb-1">Area (auto)</label>
                                    <div class="px-2 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 text-center"
                                        x-text="(office.l&&office.w)?(parseFloat(office.l)*parseFloat(office.w)).toFixed(1)+' sq ft':'—'">
                                    </div>
                                </div>
                                <button type="button" x-show="i>0" @click="removeOffice(i)"
                                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div x-show="i===0" class="w-8 flex-shrink-0"></div>
                            </div>
                        </template>
                        <button type="button" x-show="offices.length<3" @click="addOffice()"
                            class="flex items-center gap-2 text-xs font-semibold text-zendo-navy hover:opacity-70 transition-colors mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Office <span class="text-gray-400 font-normal" x-text="'('+offices.length+'/3)'"></span>
                        </button>
                    </div>
                    <div x-show="hasOffices===false" x-cloak class="text-xs text-gray-400 italic px-1 mt-1">No offices —
                        count and sizes set to zero.</div>
                    @error('no_of_offices')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    @error('office_sizes')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            @endif

            @if($fc('canteen')->keep_field)
                <div><label class="{{ $lc }}">Canteen {!! $ast('canteen') !!}</label>
                    <select name="canteen" x-model="canteen" @change="if(canteen !== '1') canteen_size = ''" {{ $req('canteen') }} class="{{ $sc('canteen') }}">
                        <option value="">— Select —</option>
                        <option value="1" {{ $bv('canteen') === 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $bv('canteen') === 0 && $bv('canteen') !== '' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('canteen')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('canteen') !!}
                </div>
            @endif

            @if($fc('canteen_size')->keep_field)
                <div x-show="canteen == '1'" x-cloak>
                    <label class="{{ $lc }}">Canteen Size
                        @if($mand('canteen_size'))
                            <span x-show="canteen == '1'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="text" name="canteen_size" x-model="canteen_size"
                        :required="{{ $mand('canteen_size') ? 'canteen == \'1\'' : 'false' }}"
                        :disabled="canteen != '1'" class="{{ $ic('canteen_size') }}">
                    @error('canteen_size')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('canteen_size') !!}
                </div>
                <div x-show="canteen == '0'" x-cloak class="flex items-center">
                    <span class="text-xs text-gray-400 italic">No canteen — size not required.</span>
                </div>
            @endif

            @if($fc('stp_plant')->keep_field)
                <div><label class="{{ $lc }}">STP Plant {!! $ast('stp_plant') !!}</label>
                    <select name="stp_plant" x-model="stp_plant" @change="if(stp_plant !== '1') stp_capacity = ''" {{ $req('stp_plant') }} class="{{ $sc('stp_plant') }}">
                        <option value="">— Select —</option>
                        <option value="1" {{ $bv('stp_plant') === 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $bv('stp_plant') === 0 && $bv('stp_plant') !== '' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('stp_plant')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('stp_plant') !!}
                </div>
            @endif

            @if($fc('stp_capacity')->keep_field)
                <div x-show="stp_plant == '1'" x-cloak>
                    <label class="{{ $lc }}">STP Capacity
                        @if($mand('stp_capacity'))
                            <span x-show="stp_plant == '1'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="text" name="stp_capacity" x-model="stp_capacity"
                        :required="{{ $mand('stp_capacity') ? 'stp_plant == \'1\'' : 'false' }}"
                        :disabled="stp_plant != '1'" class="{{ $ic('stp_capacity') }}">
                    @error('stp_capacity')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('stp_capacity') !!}
                </div>
                <div x-show="stp_plant == '0'" x-cloak class="flex items-center">
                    <span class="text-xs text-gray-400 italic">No STP plant — capacity not required.</span>
                </div>
            @endif

            @if($fc('washrooms')->keep_field)
                <div><label class="{{ $lc }}">No. of Washrooms {!! $ast('washrooms') !!}</label>
                    <input type="number" min="0" name="washrooms" value="{{ $v('washrooms') }}" {{ $req('washrooms') }}
                        class="{{ $ic('washrooms') }}">
                    @error('washrooms')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('washrooms') !!}
                </div>
            @endif

            @if($fc('no_of_urinals')->keep_field)
                <div><label class="{{ $lc }}">No. of Urinals {!! $ast('no_of_urinals') !!}</label>
                    <input type="number" min="0" name="no_of_urinals" value="{{ $v('no_of_urinals') }}" {{ $req('no_of_urinals') }} class="{{ $ic('no_of_urinals') }}">
                    @error('no_of_urinals')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('no_of_urinals') !!}
                </div>
            @endif

            @if($fc('no_of_closets')->keep_field)
                <div><label class="{{ $lc }}">No. of Closets {!! $ast('no_of_closets') !!}</label>
                    <input type="number" min="0" name="no_of_closets" value="{{ $v('no_of_closets') }}" {{ $req('no_of_closets') }} class="{{ $ic('no_of_closets') }}">
                    @error('no_of_closets')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('no_of_closets') !!}
                </div>
            @endif

            @foreach(['female_washroom' => 'Female Washroom', 'driver_rest_room' => 'Driver Rest Room', 'scrap_yard' => 'Scrap Yard', 'extension_possible' => 'Extension Possible?'] as $fk => $flbl)
                @if($fc($fk)->keep_field)
                    <div><label class="{{ $lc }}">{{ $flbl }} {!! $ast($fk) !!}</label>
                        <select name="{{ $fk }}" {{ $req($fk) }} class="{{ $sc($fk) }}">
                            <option value="">— Select —</option>
                            <option value="1" {{ $bv($fk) === 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $bv($fk) === 0 && $bv($fk) !== '' ? 'selected' : '' }}>No</option>
                        </select>
                        @error($fk)
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror {!! $rmk($fk) !!}
                    </div>
                @endif
            @endforeach

            @if($fc('mezzanine')->keep_field)
                <div><label class="{{ $lc }}">Mezzanine {!! $ast('mezzanine') !!}</label>
                    <select name="mezzanine" x-model="mezzanine" @change="if(mezzanine !== '1') mezzanine_size = ''" {{ $req('mezzanine') }} class="{{ $sc('mezzanine') }}">
                        <option value="">— Select —</option>
                        <option value="1" {{ $bv('mezzanine') === 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $bv('mezzanine') === 0 && $bv('mezzanine') !== '' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('mezzanine')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('mezzanine') !!}
                </div>
            @endif

            @if($fc('mezzanine_size')->keep_field)
                <div x-show="mezzanine == '1'" x-cloak>
                    <label class="{{ $lc }}">Mezzanine Size
                        @if($mand('mezzanine_size'))
                            <span x-show="mezzanine == '1'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="text" name="mezzanine_size" x-model="mezzanine_size"
                        :required="{{ $mand('mezzanine_size') ? 'mezzanine == \'1\'' : 'false' }}"
                        :disabled="mezzanine != '1'" class="{{ $ic('mezzanine_size') }}">
                    @error('mezzanine_size')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('mezzanine_size') !!}
                </div>
                <div x-show="mezzanine == '0'" x-cloak class="flex items-center">
                    <span class="text-xs text-gray-400 italic">No mezzanine — size not required.</span>
                </div>
            @endif

            @if($fc('structure_type')->keep_field)
                <div><label class="{{ $lc }}">Structure Type {!! $ast('structure_type') !!}</label>
                    <input type="text" name="structure_type" value="{{ $v('structure_type') }}" {{ $req('structure_type') }}
                        class="{{ $ic('structure_type') }}">
                    @error('structure_type')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('structure_type') !!}
                </div>
            @endif

            @if($fc('flooring_type')->keep_field)
                <div><label class="{{ $lc }}">Flooring Type {!! $ast('flooring_type') !!}</label>
                    <select name="flooring_type" {{ $req('flooring_type') }} class="{{ $sc('flooring_type') }}">
                        <option value="">— Select —</option>
                        @foreach(['FM2', 'VDF', 'Trimix', 'Concrete', 'Kota / Tile', 'Kachha'] as $o)
                            <option value="{{ $o }}" {{ $sel('flooring_type', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('flooring_type')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('flooring_type') !!}
                </div>
            @endif

            @if($fc('ventilation_lighting')->keep_field)
                <div><label class="{{ $lc }}">Ventilation &amp; Lighting {!! $ast('ventilation_lighting') !!}</label>
                    <select name="ventilation_lighting" {{ $req('ventilation_lighting') }} class="{{ $sc('ventilation_lighting') }}">
                        <option value="">— Select —</option>
                        @foreach(['Good', 'Average', 'Poor'] as $o)
                            <option value="{{ $o }}" {{ $sel('ventilation_lighting', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('ventilation_lighting')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('ventilation_lighting') !!}
                </div>
            @endif

            @if($fc('insulation_roof')->keep_field)
                <div><label class="{{ $lc }}">Roof Insulation {!! $ast('insulation_roof') !!}</label>
                    <input type="text" name="insulation_roof" value="{{ $v('insulation_roof') }}" {{ $req('insulation_roof') }} class="{{ $ic('insulation_roof') }}">
                    @error('insulation_roof')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('insulation_roof') !!}
                </div>
            @endif

            @if($fc('insulation_side')->keep_field)
                <div><label class="{{ $lc }}">Side Insulation {!! $ast('insulation_side') !!}</label>
                    <input type="text" name="insulation_side" value="{{ $v('insulation_side') }}" {{ $req('insulation_side') }} class="{{ $ic('insulation_side') }}">
                    @error('insulation_side')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('insulation_side') !!}
                </div>
            @endif

            @if($fc('fire_sprinkler')->keep_field)
                <div><label class="{{ $lc }}">Fire Sprinkler {!! $ast('fire_sprinkler') !!}</label>
                    <select name="fire_sprinkler" {{ $req('fire_sprinkler') }} class="{{ $sc('fire_sprinkler') }}">
                        <option value="">— Select —</option>
                        @foreach(['Yes', 'No', 'Partial'] as $o)
                            <option value="{{ $o }}" {{ $sel('fire_sprinkler', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('fire_sprinkler')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('fire_sprinkler') !!}
                </div>
            @endif

            @if($fc('no_of_companies_same_premise')->keep_field)
                <div><label class="{{ $lc }}">No. of Companies in Same Premise
                        {!! $ast('no_of_companies_same_premise') !!}</label>
                    <input type="number" min="0" name="no_of_companies_same_premise"
                        value="{{ $v('no_of_companies_same_premise') }}" {{ $req('no_of_companies_same_premise') }}
                        class="{{ $ic('no_of_companies_same_premise') }}">
                    @error('no_of_companies_same_premise')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('no_of_companies_same_premise') !!}
                </div>
            @endif

        </div>
    </div>
</div>{{-- /step 4 --}}


{{-- ══ STEP 5 — F. Loading & Docking ══ --}}
<div class="wizard-step" data-step="5" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="F. Loading &amp; Docking">F. Loading
                &amp; Docking Facilities</h3>
            @if($sec_errs('F. Loading & Docking') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('F. Loading & Docking') }}
                    error(s)</span>
            @endif
            @if($sec_errs('F. Loading & Docking') == 0 && $sec_remarks('F. Loading & Docking') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('F. Loading & Docking') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">
            @if($fc('dock_type')->keep_field)
                <div><label class="{{ $lc }}">Dock Type {!! $ast('dock_type') !!}</label>
                    <select name="dock_type" {{ $req('dock_type') }} class="{{ $sc('dock_type') }}">
                        <option value="">— Select —</option>
                        @foreach(['Ground level', 'Dock high', 'Both', 'None'] as $o)
                            <option value="{{ $o }}" {{ $sel('dock_type', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('dock_type')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('dock_type') !!}
                </div>
            @endif
            @if($fc('dock_height')->keep_field)
                <div><label class="{{ $lc }}">Dock Height (ft) {!! $ast('dock_height') !!}</label>
                    <input type="number" step="0.01" min="0" name="dock_height" value="{{ $v('dock_height') }}" {{ $req('dock_height') }} class="{{ $ic('dock_height') }}">
                    @error('dock_height')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('dock_height') !!}
                </div>
            @endif
            @if($fc('truck_movement')->keep_field)
                <div><label class="{{ $lc }}">Truck Movement {!! $ast('truck_movement') !!}</label>
                    <select name="truck_movement" {{ $req('truck_movement') }} class="{{ $sc('truck_movement') }}">
                        <option value="">— Select —</option>
                        @foreach(['40 ft container', '32 ft truck', 'Tempo only', 'Restricted'] as $o)
                            <option value="{{ $o }}" {{ $sel('truck_movement', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('truck_movement')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('truck_movement') !!}
                </div>
            @endif
            @if($fc('office_cabin_area')->keep_field)
                <div><label class="{{ $lc }}">Office / Cabin Area (sq ft) {!! $ast('office_cabin_area') !!}</label>
                    <input type="number" step="0.01" min="0" name="office_cabin_area" value="{{ $v('office_cabin_area') }}"
                        {{ $req('office_cabin_area') }} class="{{ $ic('office_cabin_area') }}">
                    @error('office_cabin_area')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('office_cabin_area') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 5 --}}

{{-- ══ STEP 6 — G. Utilities & Infrastructure ══ --}}
<div class="wizard-step" data-step="6" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="G. Utilities &amp; Infrastructure">G.
                Utilities &amp; Infrastructure</h3>
            @if($sec_errs('G. Utilities & Infrastructure') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('G. Utilities & Infrastructure') }}
                    error(s)</span>
            @endif
            @if($sec_errs('G. Utilities & Infrastructure') == 0 && $sec_remarks('G. Utilities & Infrastructure') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('G. Utilities & Infrastructure') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">
            @if($fc('power_sanctioned_kva')->keep_field)
                <div><label class="{{ $lc }}">Power Sanctioned (KVA) {!! $ast('power_sanctioned_kva') !!}</label>
                    <input type="number" step="0.01" min="0" name="power_sanctioned_kva"
                        value="{{ $v('power_sanctioned_kva') }}" {{ $req('power_sanctioned_kva') }} class="{{ $ic('power_sanctioned_kva') }}">
                    @error('power_sanctioned_kva')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('power_sanctioned_kva') !!}
                </div>
            @endif
            @if($fc('discom_name')->keep_field)
                <div><label class="{{ $lc }}">DISCOM Name {!! $ast('discom_name') !!}</label>
                    <input type="text" name="discom_name" value="{{ $v('discom_name') }}" {{ $req('discom_name') }}
                        class="{{ $ic('discom_name') }}">
                    @error('discom_name')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('discom_name') !!}
                </div>
            @endif
            @if($fc('water_source')->keep_field)
                <div><label class="{{ $lc }}">Water Source {!! $ast('water_source') !!}</label>
                    <select name="water_source" {{ $req('water_source') }} class="{{ $sc('water_source') }}">
                        <option value="">— Select —</option>
                        @foreach(['Borewell', 'Municipal', 'Tanker', 'None'] as $o)
                            <option value="{{ $o }}" {{ $sel('water_source', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('water_source')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('water_source') !!}
                </div>
            @endif
            @if($fc('water_tank_capacity')->keep_field)
                <div><label class="{{ $lc }}">Water Tank Capacity {!! $ast('water_tank_capacity') !!}</label>
                    <input type="text" name="water_tank_capacity" value="{{ $v('water_tank_capacity') }}" {{ $req('water_tank_capacity') }} class="{{ $ic('water_tank_capacity') }}">
                    @error('water_tank_capacity')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('water_tank_capacity') !!}
                </div>
            @endif
            @if($fc('fire_fighting_system')->keep_field)
                <div><label class="{{ $lc }}">Fire Fighting System {!! $ast('fire_fighting_system') !!}</label>
                    <select name="fire_fighting_system" {{ $req('fire_fighting_system') }} class="{{ $sc('fire_fighting_system') }}">
                        <option value="">— Select —</option>
                        @foreach(['Full sprinkler', 'Hydrant only', 'Extinguishers', 'None'] as $o)
                            <option value="{{ $o }}" {{ $sel('fire_fighting_system', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('fire_fighting_system')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('fire_fighting_system') !!}
                </div>
            @endif
            @if($fc('solar')->keep_field)
                <div><label class="{{ $lc }}">Solar {!! $ast('solar') !!}</label>
                    <select name="solar" {{ $req('solar') }} class="{{ $sc('solar') }}">
                        <option value="">— Select —</option>
                        <option value="1" {{ $bv('solar') === 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $bv('solar') === 0 && $bv('solar') !== '' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('solar')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror {!! $rmk('solar') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 6 --}}

{{-- ══ STEP 7 — H. Financial & Lease Terms ══ --}}
<div class="wizard-step" data-step="7" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="H. Financial &amp; Lease Terms">H.
                Financial &amp; Lease Terms</h3>
            @if($sec_errs('H. Financial & Lease Terms') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('H. Financial & Lease Terms') }}
                    error(s)</span>
            @endif
            @if($sec_errs('H. Financial & Lease Terms') == 0 && $sec_remarks('H. Financial & Lease Terms') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('H. Financial & Lease Terms') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}" x-data="{
            deal_type: '{{ old('deal_type', $entry?->deal_type ?? '') }}',
            expected_rent: '{{ old('expected_rent', $entry?->expected_rent ?? '') }}',
            expected_sale_price: '{{ old('expected_sale_price', $entry?->expected_sale_price ?? '') }}',
            security_deposit_months: '{{ old('security_deposit_months', $entry?->security_deposit_months ?? '') }}',
            lock_in_years: '{{ old('lock_in_years', $entry?->lock_in_years ?? '') }}',
            handleDealTypeChange() {
                if (this.deal_type === 'Sale') {
                    this.expected_rent = '';
                    this.security_deposit_months = '';
                    this.lock_in_years = '';
                } else if (this.deal_type === 'Lease') {
                    this.expected_sale_price = '';
                }
            }
        }">
            @if($fc('deal_type')->keep_field)
                <div><label class="{{ $lc }}">Lease / Sale Status {!! $ast('deal_type') !!}</label>
                    <select name="deal_type" x-model="deal_type" @change="handleDealTypeChange()" {{ $req('deal_type') }}
                        class="{{ $sc('deal_type') }}">
                        <option value="">— Select —</option>
                        @foreach(['Lease', 'Sale', 'Both'] as $o)
                            <option value="{{ $o }}" {{ $sel('deal_type', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('deal_type')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('deal_type') !!}
                </div>
            @endif
            @if($fc('expected_rent')->keep_field)
                <div :class="deal_type === 'Sale' ? 'opacity-50' : ''">
                    <label class="{{ $lc }}">Expected Rent (₹/sq ft/month)
                        @if($mand('expected_rent'))
                            <span x-show="deal_type === 'Lease' || deal_type === 'Both'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="number" step="0.01" min="0" name="expected_rent" x-model="expected_rent"
                        :required="{{ $mand('expected_rent') ? '(deal_type === \'Lease\' || deal_type === \'Both\')' : 'false' }}"
                        :disabled="deal_type === 'Sale'" class="{{ $ic('expected_rent') }}">
                    @error('expected_rent')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('expected_rent') !!}
                </div>
            @endif
            @if($fc('expected_sale_price')->keep_field)
                <div :class="deal_type === 'Lease' ? 'opacity-50' : ''">
                    <label class="{{ $lc }}">Expected Sale Price (₹)
                        @if($mand('expected_sale_price'))
                            <span x-show="deal_type === 'Sale' || deal_type === 'Both'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="number" step="0.01" min="0" name="expected_sale_price" x-model="expected_sale_price"
                        :required="{{ $mand('expected_sale_price') ? '(deal_type === \'Sale\' || deal_type === \'Both\')' : 'false' }}"
                        :disabled="deal_type === 'Lease'" class="{{ $ic('expected_sale_price') }}">
                    @error('expected_sale_price')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('expected_sale_price') !!}
                </div>
            @endif
            @if($fc('security_deposit_months')->keep_field)
                <div :class="deal_type === 'Sale' ? 'opacity-50' : ''">
                    <label class="{{ $lc }}">Security Deposit (months)
                        @if($mand('security_deposit_months'))
                            <span x-show="deal_type === 'Lease' || deal_type === 'Both'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="number" step="0.1" min="0" max="60" name="security_deposit_months"
                        x-model="security_deposit_months"
                        :required="{{ $mand('security_deposit_months') ? '(deal_type === \'Lease\' || deal_type === \'Both\')' : 'false' }}"
                        :disabled="deal_type === 'Sale'" class="{{ $ic('security_deposit_months') }}">
                    @error('security_deposit_months')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('security_deposit_months') !!}
                </div>
            @endif
            @if($fc('lock_in_years')->keep_field)
                <div :class="deal_type === 'Sale' ? 'opacity-50' : ''">
                    <label class="{{ $lc }}">Lock-in Period (years)
                        @if($mand('lock_in_years'))
                            <span x-show="deal_type === 'Lease' || deal_type === 'Both'" class="text-red-500 ml-0.5">*</span>
                        @endif
                    </label>
                    <input type="number" step="0.1" min="0" max="99" name="lock_in_years" x-model="lock_in_years"
                        :required="{{ $mand('lock_in_years') ? '(deal_type === \'Lease\' || deal_type === \'Both\')' : 'false' }}"
                        :disabled="deal_type === 'Sale'" class="{{ $ic('lock_in_years') }}">
                    @error('lock_in_years')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('lock_in_years') !!}
                </div>
            @endif
            @if($fc('available_from')->keep_field)
                <div><label class="{{ $lc }}">Available From Date {!! $ast('available_from') !!}</label>
                    <input type="date" name="available_from" {{ $req('available_from') }}
                        value="{{ old('available_from', ($entry && $entry->available_from) ? $entry->available_from->format('Y-m-d') : '') }}"
                        class="{{ $ic('available_from') }}">
                    @error('available_from')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('available_from') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 7 --}}


{{-- ══ STEP 8 — I. Surroundings & Environment ══ --}}
<div class="wizard-step" data-step="8" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="I. Surroundings &amp; Environment">I.
                Surroundings &amp; Environment</h3>
            @if($sec_errs('I. Surroundings & Environment') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('I. Surroundings & Environment') }}
                    error(s)</span>
            @endif
            @if($sec_errs('I. Surroundings & Environment') == 0 && $sec_remarks('I. Surroundings & Environment') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('I. Surroundings & Environment') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">
            @if($fc('approach_road_width')->keep_field)
                <div><label class="{{ $lc }}">Approach Road Width (ft) {!! $ast('approach_road_width') !!}</label>
                    <input type="number" step="0.01" min="0" name="approach_road_width"
                        value="{{ $v('approach_road_width') }}" {{ $req('approach_road_width') }} class="{{ $ic('approach_road_width') }}">
                    @error('approach_road_width')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('approach_road_width') !!}
                </div>
            @endif
            @if($fc('top_neighbouring_companies')->keep_field)
                <div class="md:col-span-3"><label class="{{ $lc }}">Top Neighbouring Companies
                        {!! $ast('top_neighbouring_companies') !!}</label>
                    <textarea name="top_neighbouring_companies" rows="2" {{ $req('top_neighbouring_companies') }}
                        class="{{ $ic('top_neighbouring_companies') }}">{{ $v('top_neighbouring_companies') }}</textarea>
                    @error('top_neighbouring_companies')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('top_neighbouring_companies') !!}
                </div>
            @endif
            @if($fc('flood_risk')->keep_field)
                <div><label class="{{ $lc }}">Flood / Water-Logging Risk {!! $ast('flood_risk') !!}</label>
                    <select name="flood_risk" {{ $req('flood_risk') }} class="{{ $sc('flood_risk') }}">
                        <option value="">— Select —</option>
                        @foreach(['None', 'Low', 'Moderate', 'High'] as $o)
                            <option value="{{ $o }}" {{ $sel('flood_risk', $o) }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('flood_risk')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('flood_risk') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 8 --}}

{{-- ══ STEP 9 — J. Health & Emergency Nearby ══ --}}
<div class="wizard-step" data-step="9" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="J. Health &amp; Emergency Nearby">J.
                Health &amp; Emergency Facilities Nearby</h3>
            @if($sec_errs('J. Health & Emergency Nearby') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('J. Health & Emergency Nearby') }}
                    error(s)</span>
            @endif
            @if($sec_errs('J. Health & Emergency Nearby') == 0 && $sec_remarks('J. Health & Emergency Nearby') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('J. Health & Emergency Nearby') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">
            @if($fc('nearest_hospital_km')->keep_field)
                <div><label class="{{ $lc }}">Nearest Hospital (km) {!! $ast('nearest_hospital_km') !!}</label>
                    <input type="number" step="0.01" min="0" name="nearest_hospital_km"
                        value="{{ $v('nearest_hospital_km') }}" {{ $req('nearest_hospital_km') }} class="{{ $ic('nearest_hospital_km') }}">
                    @error('nearest_hospital_km')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_hospital_km') !!}
                </div>
            @endif
            @if($fc('nearest_fire_station_km')->keep_field)
                <div><label class="{{ $lc }}">Nearest Fire Station (km) {!! $ast('nearest_fire_station_km') !!}</label>
                    <input type="number" step="0.01" min="0" name="nearest_fire_station_km"
                        value="{{ $v('nearest_fire_station_km') }}" {{ $req('nearest_fire_station_km') }} class="{{ $ic('nearest_fire_station_km') }}">
                    @error('nearest_fire_station_km')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_fire_station_km') !!}
                </div>
            @endif
            @if($fc('nearest_police_station_km')->keep_field)
                <div><label class="{{ $lc }}">Nearest Police Station (km) {!! $ast('nearest_police_station_km') !!}</label>
                    <input type="number" step="0.01" min="0" name="nearest_police_station_km"
                        value="{{ $v('nearest_police_station_km') }}" {{ $req('nearest_police_station_km') }}
                        class="{{ $ic('nearest_police_station_km') }}">
                    @error('nearest_police_station_km')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('nearest_police_station_km') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 9 --}}


{{-- ══ STEP 10 — K. Photographs ══ --}}

{{-- Camera Modal — shared, opened per slot --}}
<div id="camera-modal" class="fixed inset-0 z-[9999] bg-black flex-col items-center justify-center hidden"
    style="touch-action:none;">
    <video id="camera-stream" autoplay playsinline muted class="w-full h-full object-cover absolute inset-0"></video>
    <div
        class="absolute top-0 left-0 right-0 flex items-center justify-between px-4 py-3 bg-gradient-to-b from-black/70 to-transparent z-10">
        <span id="camera-slot-label" class="text-white text-sm font-semibold truncate"></span>
        <button type="button" onclick="closeCamera()"
            class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="absolute bottom-8 left-0 right-0 flex items-center justify-center gap-8 z-10">
        <button type="button" id="flip-btn" onclick="flipCamera()"
            class="w-12 h-12 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </button>
        <button type="button" onclick="capturePhoto()"
            class="w-18 h-18 rounded-full bg-white border-4 border-white/50 shadow-lg hover:scale-105 active:scale-95 transition-transform flex items-center justify-center"
            style="width:72px;height:72px;">
            <span class="w-14 h-14 rounded-full bg-white block" style="width:56px;height:56px;"></span>
        </button>
        <div class="w-12 h-12"></div>
    </div>
    <canvas id="camera-canvas" class="hidden"></canvas>
</div>

<div class="wizard-step" data-step="10" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy">K. Photographs</h3>
            @if($sec_errs('K. Photographs') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('K. Photographs') }}
                    error(s)</span>
            @endif
            @if($sec_errs('K. Photographs') == 0 && $sec_remarks('K. Photographs') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('K. Photographs') }}
                    remark(s) to fix</span>
            @endif
        </div>
        @error('photos')
            <p class="px-5 pt-3 text-xs text-red-600 font-semibold">{{ $message }}</p>
        @enderror
        <div class="px-5 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($slots as $index => $slotLabel)
                @php
                    $existing = $entry?->photos?->firstWhere('slot_label', $slotLabel);
                    $photoLocked = $isRestrictedEdit && in_array('photo_' . $index, $correctFields);
                    $photoHasError = $errors->has('photos.' . $index) || $errors->has('photo_' . $index);
                @endphp
                <div class="flex flex-col items-center gap-2">
                    <div class="relative w-full aspect-square rounded-xl overflow-hidden border-2 border-dashed {{ $photoHasError ? 'border-red-500 ring-2 ring-red-300 bg-red-50' : 'border-gray-200 bg-gray-50' }} group cursor-pointer"
                        id="preview-box-{{ $index }}" @if(!$photoLocked)
                        onclick="{!! $isRemoteEntry ? "document.getElementById('photo-{$index}').click()" : "openCamera({$index}, '" . addslashes($slotLabel) . "')" !!}" @endif>
                        <img id="preview-img-{{ $index }}"
                            src="{{ $existing ? asset('images/property_photos/' . basename($existing->file_path)) : '' }}"
                            alt="{{ $slotLabel }}" class="w-full h-full object-cover {{ $existing ? '' : 'hidden' }}">
                        <div id="placeholder-{{ $index }}"
                            class="w-full h-full flex flex-col items-center justify-center gap-1 {{ $existing ? 'hidden' : '' }}">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[10px] text-gray-400">No photo</span>
                        </div>
                        @if(!$photoLocked)
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="flex flex-col items-center gap-1 text-white pointer-events-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-[11px] font-semibold">{{ $isRemoteEntry ? 'Tap to select file' : 'Tap to capture' }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                    <span class="text-[11px] text-gray-600 text-center font-semibold leading-tight">{{ $slotLabel }}</span>
                    <input type="file" name="photos[{{ $index }}]" id="photo-{{ $index }}" accept="image/*" class="sr-only"
                        @if($photoLocked) disabled @endif onchange="handleFileSelect(this, {{ $index }})">
                    @if($photoLocked)
                        <div
                            class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Locked
                        </div>
                    @else
                        <button type="button" onclick="{!! $isRemoteEntry ? "document.getElementById('photo-{$index}').click()" : "openCamera({$index}, '" . addslashes($slotLabel) . "')" !!}"
                            id="cam-btn-{{ $index }}"
                            class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition-colors
                                               {{ $existing ? 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' : 'bg-zendo-navy text-white hover:bg-opacity-90' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span id="cam-btn-label-{{ $index }}">{{ $existing ? 'Change Photo' : ($isRemoteEntry ? 'Select Photo' : 'Take Photo') }}</span>
                        </button>
                    @endif
                    @error('photos.' . $index)
                        <p class="text-[11px] font-semibold text-red-600 text-center leading-tight mt-1">{{ $message }}</p>
                    @enderror
                    @error('photo_' . $index)
                        <p class="text-[11px] font-semibold text-red-600 text-center leading-tight mt-1">{{ $message }}</p>
                    @enderror
                    @if(isset($fieldRemarks['photo_' . $index]) && $fieldRemarks['photo_' . $index])
                        <div class="w-full px-2 py-1.5 bg-red-50 border border-red-200 rounded-lg text-center">
                            <p class="text-[10px] font-semibold text-red-700 mb-0.5">⚠ Remark:</p>
                            <p class="text-xs text-red-800">{{ $fieldRemarks['photo_' . $index] }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>{{-- /step 10 --}}

{{-- ══ STEP 11 — L. General Remarks ══ --}}
<div class="wizard-step" data-step="11" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy" data-section-title="L. General Remarks">L. General Remarks
                &amp; Field Observations</h3>
            @if($sec_errs('L. General Remarks') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200">{{ $sec_errs('L. General Remarks') }}
                    error(s)</span>
            @endif
            @if($sec_errs('L. General Remarks') == 0 && $sec_remarks('L. General Remarks') > 0)
                <span
                    class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">{{ $sec_remarks('L. General Remarks') }}
                    remark(s) to fix</span>
            @endif
        </div>
        <div class="{{ $sb }}">
            @if($fc('remarks')->keep_field)
                <div class="md:col-span-3 lg:col-span-3">
                    <label class="{{ $lc }}">Remarks / Observations {!! $ast('remarks') !!}</label>
                    <textarea name="remarks" rows="3" {{ $req('remarks') }} class="{{ $ic('remarks') }}">{{ $v('remarks') }}</textarea>
                    @error('remarks')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                    {!! $rmk('remarks') !!}
                </div>
            @endif
        </div>
    </div>
</div>{{-- /step 11 --}}


{{-- ══ STEP 12 — Review & Submit ══ --}}
<div class="wizard-step" data-step="12" style="display:none">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-zendo-navy">Review &amp; Submit</h3>
            <p class="text-xs text-gray-500 mt-0.5">Check the details below before submitting. Use "Edit" on any
                section to go back and make changes.</p>
        </div>
    </div>
    <div id="wiz-unvisited-banner" class="mb-4 border border-amber-200 bg-amber-50 rounded-lg p-4 flex items-start gap-3" style="display:none">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <h4 class="text-sm font-semibold text-amber-800">Some sections haven't been opened yet</h4>
            <p class="text-sm text-amber-700 mt-0.5" id="wiz-unvisited-text"></p>
        </div>
    </div>
    <div id="review-content"></div>
</div>{{-- /step 12 --}}


{{-- Hidden: current wizard step — submitted with draft saves so we can restore position --}}
<input type="hidden" name="wizard_step" id="wizard_step_input" value="{{ session('wizard_step', 0) }}">

{{-- ═══════════════════════════════════════════════════════
WIZARD — BOTTOM NAV BAR
═══════════════════════════════════════════════════════ --}}
<div id="wizard-nav"
    class="sticky bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.06)] px-4 py-3 mt-4 -mx-1">
    <div class="flex items-center justify-between gap-3 max-w-3xl mx-auto">

        {{-- Previous --}}
        <button type="button" id="wiz-prev-btn" onclick="wizardPrev()"
            class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Previous
        </button>

        <div class="flex items-center gap-2 flex-1 justify-end">

            {{-- Save Draft — every role can park a half-filled entry and resume
                 it later from its own properties list --}}
            <button type="submit" name="action" value="draft" formnovalidate
                onclick="document.querySelector('form').noValidate=true"
                class="px-4 py-2.5 rounded-lg text-sm font-semibold text-gray-600 border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                Save Draft
            </button>

            {{-- Save & Next (hidden on last step) --}}
            <button type="button" id="wiz-next-btn" onclick="wizardNext()"
                class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-zendo-navy hover:bg-opacity-90 transition-colors">
                Save &amp; Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Submit to Office / Submit for Admin Approval (shown only on last step) --}}
            <button type="submit" id="wiz-submit-btn" name="action" value="submit" formnovalidate
                onclick="return wizardCanSubmit() && confirm('{{ $isSupplyHead ? 'Submit this property for admin approval?' : 'Submit this property entry to the office?' }}');"
                style="display:none"
                class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                {{ $isSupplyHead ? 'Submit for Admin Approval' : 'Submit to Office' }}
            </button>

        </div>
    </div>
</div>


<script>
    // ─────────────────────────────────────────────
    // WIZARD CONTROLLER — vanilla JS, no Alpine dep
    // ─────────────────────────────────────────────
    const WIZ_TOTAL = 13;
    const WIZ_LABELS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', '✓'];
    const WIZ_TITLES = [
        'A. Location & Identification',
        'B. Legal & Statutory Compliance',
        'C. Property Dimensions',
        'D. Dock, Exit & Width Details',
        'E. Facility Details',
        'F. Loading & Docking',
        'G. Utilities & Infrastructure',
        'H. Financial & Lease Terms',
        'I. Surroundings & Environment',
        'J. Health & Emergency Nearby',
        'K. Photographs',
        'L. General Remarks',
        'Review & Submit'
    ];
    // Step error counts baked in from server (0 if no errors)
    const WIZ_ERR_COUNTS = @json($stepErrCounts);

    // With required-field enforcement switched off there is nothing left for a
    // walk-through of every section to catch, so submitting no longer waits on
    // one — see wizardCanSubmit().
    const WIZ_REQUIRE_ALL_VISITED = @json($requiredEnforced);

    let wizCurrent = 0; // will be set correctly in DOMContentLoaded

    // Highest step index the user is allowed to jump to. Steps beyond this
    // haven't had their mandatory fields filled in yet, so their progress
    // dots stay locked (not clickable) until earlier steps are completed.
    // Seeded server-side from actual field values (works for drafts/edits
    // reloaded fresh, not just same-session navigation).
    let wizMaxUnlocked = {{ $wizInitFrontier }};

    // Which lettered sections (steps 0-11) have actually been opened this
    // visit. Once a resubmission's sections are all unlocked up front (see
    // $wizInitFrontier), free navigation alone no longer guarantees the
    // officer looked at every section before resubmitting — so "Submit to
    // Office" additionally requires every section to have been visited at
    // least once, not just that its fields already hold values.
    const wizVisited = new Set();

    function wizardAllVisited() {
        for (let i = 0; i < WIZ_TOTAL - 1; i++) { // exclude the trailing Review step
            if (!wizVisited.has(i)) return false;
        }
        return true;
    }

    function wizardFirstUnvisited() {
        for (let i = 0; i < WIZ_TOTAL - 1; i++) {
            if (!wizVisited.has(i)) return i;
        }
        return -1;
    }

    function wizardGoTo(step) {
        if (step < 0 || step >= WIZ_TOTAL) return;
        if (step > wizMaxUnlocked) return; // locked — section ahead isn't filled in yet

        if (step < WIZ_TOTAL - 1) wizVisited.add(step);

        // Hide all steps
        document.querySelectorAll('.wizard-step').forEach(el => el.style.display = 'none');

        // Show target
        const target = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (target) target.style.display = 'block';

        wizCurrent = step;

        // Update progress dots
        document.querySelectorAll('.wiz-dot').forEach((dot, i) => {
            dot.classList.remove(
                'bg-zendo-navy', 'text-white', 'border-zendo-navy',
                'bg-green-500', 'border-green-500',
                'bg-gray-100', 'text-gray-400', 'border-gray-200',
                'bg-red-100', 'text-red-600', 'border-red-300'
            );
            if (i === step) {
                // Current
                if (WIZ_ERR_COUNTS[i] > 0) {
                    dot.classList.add('bg-red-100', 'text-red-600', 'border-red-300');
                } else {
                    dot.classList.add('bg-zendo-navy', 'text-white', 'border-zendo-navy');
                }
            } else if (i < step) {
                // Completed
                dot.classList.add('bg-green-500', 'text-white', 'border-green-500');
            } else {
                // Upcoming
                if (WIZ_ERR_COUNTS[i] > 0) {
                    dot.classList.add('bg-red-100', 'text-red-600', 'border-red-300');
                } else {
                    dot.classList.add('bg-gray-100', 'text-gray-400', 'border-gray-200');
                }
            }

            // Locked (not yet reachable) — disable the dot so it can't be
            // clicked past sections that still have required fields missing.
            const locked = i > wizMaxUnlocked;
            dot.disabled = locked;
            dot.classList.toggle('opacity-40', locked);
            dot.classList.toggle('cursor-not-allowed', locked);
        });

        // Update connector lines
        document.querySelectorAll('.wiz-line').forEach((line, i) => {
            line.classList.remove('bg-green-400', 'bg-gray-200');
            line.classList.add(i < step ? 'bg-green-400' : 'bg-gray-200');
        });

        // Update title bar
        const titleEl = document.getElementById('wiz-title');
        if (titleEl) titleEl.textContent = WIZ_TITLES[step];

        // Mobile label
        const mobileLabel = document.getElementById('wiz-mobile-label');
        const mobileCount = document.getElementById('wiz-mobile-count');
        if (mobileLabel) mobileLabel.textContent = WIZ_TITLES[step];
        if (mobileCount) mobileCount.textContent = `Step ${step + 1} of ${WIZ_TOTAL}`;

        // Prev button
        const prevBtn = document.getElementById('wiz-prev-btn');
        if (prevBtn) {
            prevBtn.disabled = step === 0;
            prevBtn.classList.toggle('opacity-40', step === 0);
            prevBtn.classList.toggle('cursor-not-allowed', step === 0);
        }

        // Next / Submit toggle
        const nextBtn = document.getElementById('wiz-next-btn');
        const submitBtn = document.getElementById('wiz-submit-btn');
        if (nextBtn && submitBtn) {
            if (step === WIZ_TOTAL - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-flex';
            } else {
                nextBtn.style.display = 'inline-flex';
                submitBtn.style.display = 'none';
            }
        }

        // Scroll to top of page
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Keep hidden input in sync so draft saves carry the step
        const stepInput = document.getElementById('wizard_step_input');
        if (stepInput) stepInput.value = step;

        // Build the review summary fresh each time it's opened, so it
        // reflects the latest edits rather than a stale snapshot.
        if (step === WIZ_TOTAL - 1) renderReviewStep();
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP VALIDATION
    // Collects all required inputs/selects/textareas in the given step div
    // that are empty, highlights them, and returns false if any found.
    // ─────────────────────────────────────────────────────────────────────
    function wizardValidateStep(stepIndex) {
        const stepEl = document.querySelector(`.wizard-step[data-step="${stepIndex}"]`);
        if (!stepEl) return true;

        // Clear previous error highlights in this step
        stepEl.querySelectorAll('.wiz-field-error').forEach(el => {
            el.classList.remove('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
        });
        stepEl.querySelectorAll('.wiz-inline-err').forEach(el => el.remove());

        const fields = stepEl.querySelectorAll('input, select, textarea');
        let firstInvalid = null;

        fields.forEach(field => {
            // Skip hidden / disabled / readonly fields
            // offsetParent === null catches ALL hidden cases (Blade's style="display:none" AND Alpine's x-show)
            if (field.disabled || field.type === 'hidden' || field.offsetParent === null) return;

            const val = field.value ? field.value.trim() : '';
            let errMsg = null;

            if (field.required && val === '') {
                errMsg = 'This field is required';
            } else if (val !== '') {
                if (field.type === 'number') {
                    const num = parseFloat(val);
                    if (isNaN(num)) {
                        errMsg = 'Please enter a valid number';
                    } else if (field.hasAttribute('min') && field.getAttribute('min') !== '' && !isNaN(parseFloat(field.getAttribute('min'))) && num < parseFloat(field.getAttribute('min'))) {
                        errMsg = `Value must not be less than ${field.getAttribute('min')}`;
                    } else if (field.hasAttribute('max') && field.getAttribute('max') !== '' && !isNaN(parseFloat(field.getAttribute('max'))) && num > parseFloat(field.getAttribute('max'))) {
                        errMsg = `Value must not be greater than ${field.getAttribute('max')}`;
                    }
                } else if (field.type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(val)) {
                        errMsg = 'Please enter a valid email address';
                    }
                }
            }

            if (errMsg) {
                field.classList.add('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
                let wrapper = field.parentElement;
                if (wrapper) {
                    wrapper.querySelectorAll('p.text-red-600').forEach(m => m.remove());
                    if (!wrapper.querySelector('.wiz-inline-err')) {
                        const msg = document.createElement('p');
                        msg.className = 'wiz-inline-err mt-1 text-xs text-red-600 font-medium';
                        msg.textContent = errMsg;
                        wrapper.appendChild(msg);
                    }
                }
                if (!firstInvalid) firstInvalid = field;
            }
        });

        // Dock levellers: "Available? = Yes" needs a real count somewhere
        // (mirrors the server-side group check). The blank-string scan above
        // can't catch this — these fields default to "0", which is never
        // blank, so a plain [required] attribute is inert here regardless of
        // its current value.
        const levFields = ['dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back']
            .map(n => stepEl.querySelector(`[name="${n}"]`))
            .filter(Boolean);
        if (levFields.length) {
            const hasLevYes = stepEl.querySelector('input[name="has_dock_leveller"]:checked')?.value === '1';
            const anyMandatory = levFields.some(f => f.required);
            const sum = levFields.reduce((s, f) => s + (parseInt(f.value, 10) || 0), 0);
            if (hasLevYes && anyMandatory && sum <= 0) {
                levFields.forEach(f => {
                    f.classList.add('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
                    const wrapper = f.parentElement;
                    if (!wrapper.querySelector('.wiz-inline-err')) {
                        const msg = document.createElement('p');
                        msg.className = 'wiz-inline-err mt-1 text-xs text-red-600 font-medium';
                        msg.textContent = 'Enter at least one dock leveller count (front/left/right/back)';
                        wrapper.appendChild(msg);
                    }
                });
                if (!firstInvalid) firstInvalid = levFields[0];
            }
        }

        if (firstInvalid) {
            // Scroll to first invalid field
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
            return false;
        }
        return true;
    }

    function wizardValidateAll() {
        let firstFailStep = -1;
        for (let i = 0; i < WIZ_TOTAL; i++) {
            if (!wizardValidateStep(i)) {
                if (firstFailStep === -1) firstFailStep = i;
            }
        }
        if (firstFailStep !== -1) {
            wizardGoTo(firstFailStep);
            return false;
        }
        return true;
    }

    // Gate for "Submit to Office": all fields must validate AND every
    // lettered section must have been opened at least once this visit.
    // Needed because a resubmission unlocks every section up front (see
    // $wizInitFrontier) — without this, an officer could jump straight to
    // Review & Submit and resubmit without ever opening the sections the
    // supply head flagged, since their previously-filled values already
    // pass plain field validation.
    function wizardCanSubmit() {
        if (WIZ_REQUIRE_ALL_VISITED && !wizardAllVisited()) {
            const first = wizardFirstUnvisited();
            wizardGoTo(first >= 0 ? first : 0);
            alert('Please open and check every section before submitting — some sections haven\'t been visited yet.');
            return false;
        }
        return wizardValidateAll();
    }

    function wizardNext() {
        if (!wizardValidateStep(wizCurrent)) return; // blocked — errors shown inline
        wizMaxUnlocked = Math.max(wizMaxUnlocked, wizCurrent + 1);
        wizardGoTo(wizCurrent + 1);
    }

    function wizardPrev() { wizardGoTo(wizCurrent - 1); }

    // ─────────────────────────────────────────────────────────────────────
    // REVIEW & SUBMIT — builds a read-only recap of steps A-L straight from
    // the live DOM (not a hand-maintained field list), so it can't drift out
    // of sync with whatever fields PropertyFieldConfig currently keeps/hides.
    // ─────────────────────────────────────────────────────────────────────
    function wizHumanize(name) {
        return name.replace(/\[.*?\]/g, '').replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()).trim();
    }

    // Walk up from `el` toward `stopEl`, checking each ancestor's preceding
    // siblings for a LABEL/SPAN/H4 with real text — covers both "label sits
    // right before the control" and "control is nested inside a styled
    // radio option, real label is a level up" (the Yes/No toggle widgets).
    function wizFindPrecedingText(el, stopEl, tags) {
        let node = el;
        while (node && node !== stopEl) {
            let sib = node.previousElementSibling;
            while (sib) {
                if (tags.includes(sib.tagName) && sib.textContent.replace('*', '').trim().length > 1) {
                    return sib.textContent.replace('*', '').trim();
                }
                sib = sib.previousElementSibling;
            }
            node = node.parentElement;
        }
        return null;
    }

    function wizFieldLabel(control, stepEl) {
        const prev = control.previousElementSibling;
        if (prev && prev.tagName === 'LABEL') return prev.textContent.replace('*', '').trim();
        const wrappingLabel = control.closest('label');
        const searchFrom = wrappingLabel ? wrappingLabel.parentElement : control.parentElement;
        return wizFindPrecedingText(searchFrom, stepEl, ['LABEL', 'SPAN']) || wizHumanize(control.name);
    }

    function wizGroupHeading(control, stepEl) {
        return wizFindPrecedingText(control, stepEl, ['H4']);
    }

    function wizCollectStepFields(stepEl) {
        const seen = new Set();
        const rows = [];
        stepEl.querySelectorAll('input, select, textarea').forEach(control => {
            if (!control.name || control.disabled) return;
            if (control.type === 'hidden' || control.type === 'file') return;

            if (control.type === 'radio') {
                if (seen.has(control.name)) return;
                const checked = stepEl.querySelector(`input[name="${CSS.escape(control.name)}"]:checked`);
                if (!checked) return;
                seen.add(control.name);
                let val = checked.value;
                if (val === '1') val = 'Yes'; else if (val === '0') val = 'No';
                const heading = wizGroupHeading(control, stepEl);
                const label = wizFieldLabel(control, stepEl);
                rows.push({ label: heading ? `${heading} — ${label}` : label, value: val });
                return;
            }

            if (seen.has(control.name)) return;
            seen.add(control.name);

            let val = (control.value || '').trim();
            if (control.tagName === 'SELECT') {
                const opt = control.options[control.selectedIndex];
                val = control.value ? (opt ? opt.textContent.trim() : val) : '';
            }
            if (val === '') return; // keep the recap concise — only show what's filled

            const heading = wizGroupHeading(control, stepEl);
            const label = wizFieldLabel(control, stepEl);
            rows.push({ label: heading ? `${heading} — ${label}` : label, value: val });
        });
        return rows;
    }

    function wizPhotoSummary() {
        let count = 0;
        for (let i = 0; i < 8; i++) {
            const img = document.getElementById('preview-img-' + i);
            if (img && !img.classList.contains('hidden') && img.getAttribute('src')) count++;
        }
        return count;
    }

    function wizEscapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function renderReviewStep() {
        const container = document.getElementById('review-content');
        if (!container) return;

        const unvisitedBanner = document.getElementById('wiz-unvisited-banner');
        const unvisitedText = document.getElementById('wiz-unvisited-text');
        if (unvisitedBanner && unvisitedText && WIZ_REQUIRE_ALL_VISITED) {
            const unvisited = [];
            for (let i = 0; i < WIZ_TOTAL - 1; i++) {
                if (!wizVisited.has(i)) unvisited.push(WIZ_TITLES[i]);
            }
            if (unvisited.length) {
                unvisitedText.textContent = 'Open and check: ' + unvisited.join(', ') + ' before submitting.';
                unvisitedBanner.style.display = 'flex';
            } else {
                unvisitedBanner.style.display = 'none';
            }
        }

        let html = '';
        for (let i = 0; i < WIZ_TOTAL - 1; i++) {
            const stepEl = document.querySelector(`.wizard-step[data-step="${i}"]`);
            if (!stepEl) continue;

            const rows = (i === 10) // K. Photographs
                ? [{ label: 'Photos added', value: `${wizPhotoSummary()} of 8` }]
                : wizCollectStepFields(stepEl);

            html += `
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-semibold text-zendo-navy">${wizEscapeHtml(WIZ_TITLES[i])}</h3>
                        <button type="button" onclick="wizardGoTo(${i})"
                            class="text-xs font-semibold text-zendo-navy border border-gray-300 rounded-lg px-3 py-1 hover:bg-gray-50 transition-colors">
                            Edit
                        </button>
                    </div>
                    <div class="px-5 py-4">
                        ${rows.length ? `<dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5">${rows.map(r => `
                            <div class="flex items-baseline justify-between gap-3 text-sm border-b border-gray-50 pb-1.5 sm:border-0 sm:pb-0">
                                <dt class="text-gray-500 flex-shrink-0">${wizEscapeHtml(r.label)}</dt>
                                <dd class="font-medium text-gray-800 text-right">${wizEscapeHtml(r.value)}</dd>
                            </div>`).join('')}</dl>` : `<p class="text-sm text-gray-400 italic">No details entered.</p>`}
                    </div>
                </div>`;
        }
        container.innerHTML = html;
    }

    // Remove error highlight when user fills in a field
    document.addEventListener('input', function (e) {
        const el = e.target;
        if (el.classList.contains('wiz-field-error') && el.value.trim() !== '') {
            el.classList.remove('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
            const msg = el.parentElement.querySelector('.wiz-inline-err');
            if (msg) msg.remove();
        }
    }, true);
    document.addEventListener('change', function (e) {
        const el = e.target;
        if (el.classList.contains('wiz-field-error') && el.value.trim() !== '') {
            el.classList.remove('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
            const msg = el.parentElement.querySelector('.wiz-inline-err');
            if (msg) msg.remove();
        }
    }, true);

    // Dock levellers are a group requirement (sum > 0), not a per-field one —
    // the listeners above only clear a field's OWN highlight once ITS OWN
    // value is non-blank, but these fields are never blank ("0" counts), so
    // fixing just one would leave the other three stuck red. Clear all four
    // together once the group condition is actually satisfied.
    const DOCK_LEVELLER_FIELDS = ['dock_leveller_front', 'dock_leveller_left', 'dock_leveller_right', 'dock_leveller_back'];
    document.addEventListener('input', function (e) {
        const el = e.target;
        if (!DOCK_LEVELLER_FIELDS.includes(el.name)) return;
        const stepEl = el.closest('.wizard-step');
        if (!stepEl) return;
        const levFields = DOCK_LEVELLER_FIELDS.map(n => stepEl.querySelector(`[name="${n}"]`)).filter(Boolean);
        const sum = levFields.reduce((s, f) => s + (parseInt(f.value, 10) || 0), 0);
        if (sum > 0) {
            levFields.forEach(f => {
                f.classList.remove('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
                const msg = f.parentElement.querySelector('.wiz-inline-err');
                if (msg) msg.remove();
            });
        }
    }, true);

    // Init on DOM ready — restore step from session (after draft save) or go to first error step
    document.addEventListener('DOMContentLoaded', function () {
        const sessionStep = {{ session('wizard_step', -1) }};
        const errStep = {{ $firstErrStep }};
        // Priority: validation errors > session-restored step > 0
        wizCurrent = errStep >= 0 ? errStep : (sessionStep >= 0 ? sessionStep : 0);
        // Whatever step we're actually landing on must never render locked
        wizMaxUnlocked = Math.max(wizMaxUnlocked, wizCurrent);
        wizardGoTo(wizCurrent);
    });

    // ── Auto-strip non-numeric characters & clear field errors live on edit ──
    document.addEventListener('input', function (e) {
        const el = e.target;
        if (el.tagName === 'INPUT' && el.type === 'number') {
            let val = el.value;

            // Allow only digits, one leading minus, and one decimal point
            let cleaned = val
                .replace(/[^0-9.\-]/g, '')      // strip letters, e, +, etc.
                .replace(/(?!^)-/g, '')         // minus allowed only at start
                .replace(/(\..*)\./g, '$1');    // only one decimal point

            if (cleaned !== val) {
                const cursorPos = el.selectionStart;
                const diff = val.length - cleaned.length;
                el.value = cleaned;
                // try to keep cursor position sane after stripping
                if (cursorPos !== null) {
                    el.setSelectionRange(Math.max(0, cursorPos - diff), Math.max(0, cursorPos - diff));
                }
            }
        }

        // Live clear error styling and messages (server & inline) as field is edited
        if (el.matches('input, select, textarea')) {
            clearFieldError(el);
        }
    }, true);

    document.addEventListener('change', function (e) {
        const el = e.target;
        if (el.matches('input, select, textarea')) {
            clearFieldError(el);
        }
    }, true);

    function clearFieldError(el) {
        el.classList.remove('wiz-field-error', 'border-red-500', 'ring-2', 'ring-red-300');
        const wrapper = el.parentElement;
        if (wrapper) {
            wrapper.querySelectorAll('.wiz-inline-err').forEach(m => m.remove());
            // Clears server-rendered validation text only. `.field-format-err`
            // is the shared live client-side validator's own message (see
            // components/wizard-field-validation.blade.php) — it manages its
            // own lifecycle, and sweeping it here would erase an error the
            // instant the user typed the next character.
            wrapper.querySelectorAll('p.text-red-600:not(.field-format-err)').forEach(m => m.remove());
        }

        const stepEl = el.closest('.wizard-step');
        if (stepEl) {
            const stepIdx = parseInt(stepEl.getAttribute('data-step'), 10);
            if (!isNaN(stepIdx) && typeof WIZ_ERR_COUNTS !== 'undefined') {
                const remainingErrs = stepEl.querySelectorAll('.wiz-field-error, .wiz-inline-err, p.text-red-600').length;
                if (remainingErrs === 0 && WIZ_ERR_COUNTS[stepIdx] > 0) {
                    WIZ_ERR_COUNTS[stepIdx] = 0;
                    const dot = document.getElementById(`wiz-dot-${stepIdx}`);
                    if (dot) {
                        const badge = dot.querySelector('.bg-red-500');
                        if (badge) badge.remove();
                    }
                }
            }
        }
    }

    // ── Also block letter keys at keydown level (extra safety, blocks 'e', 'E' exponent too) ──
    document.addEventListener('keydown', function (e) {
        const el = e.target;
        if (el.tagName === 'INPUT' && el.type === 'number') {
            const allowedKeys = [
                'Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                'Home', 'End', '.', '-'
            ];
            const isCtrlCmd = e.ctrlKey || e.metaKey; // allow copy/paste/select-all shortcuts
            if (isCtrlCmd || allowedKeys.includes(e.key)) return;

            if (!/^[0-9]$/.test(e.key)) {
                e.preventDefault();
            }
        }
    }, true);
</script>


@if(config('services.mappls.access_token'))
    <script>
        // Resolved once the map SDK has loaded *and* the map itself has
        // fired its own 'load' event — Mappls' script tag calls this by
        // name (via callback=) once ready, which is more reliable than
        // guessing whether `mappls` exists yet from other scripts.
        window.__mapplsMapReady = new Promise((resolve) => {
            window.initMapplsSDK = function () {
                try {
                    const map = new mappls.Map('mappls-map', { center: [20.5937, 78.9629], zoom: 5 });
                    map.addListener('load', function () {
                        // Mappls' SDK is built on MapLibre/Mapbox GL JS, which
                        // only measures its container's size once at init and
                        // never re-checks afterward. If that first measurement
                        // happens before the surrounding layout has settled
                        // (e.g. this section just became visible, or text
                        // above it is still reflowing), the canvas gets stuck
                        // at the wrong size — a squashed/partial map that never
                        // corrects itself. A ResizeObserver keeps it in sync
                        // with the container's *actual* size, whenever it changes.
                        const mapContainer = document.getElementById('mappls-map');
                        if (window.ResizeObserver && mapContainer) {
                            new ResizeObserver(() => { if (map.resize) map.resize(); }).observe(mapContainer);
                        }
                        resolve(map);
                    });
                } catch (e) {
                    console.error('Mappls map init failed:', e);
                }
            };
        });
    </script>
    <script
        src="https://sdk.mappls.com/map/sdk/web?v=3.0&access_token={{ config('services.mappls.access_token') }}&callback=initMapplsSDK"></script>
    <script
        src="https://sdk.mappls.com/map/sdk/plugins?access_token={{ config('services.mappls.access_token') }}&v=3.0&libraries=direction,search"></script>
@endif

<script>
    // ── Capture the field officer's current location into the hidden
    // form_submited_location field, so it's saved alongside the entry. ──
    (function () {
        const locInput = document.getElementById('form_submited_location');
        if (!locInput) return;

        // Supply head adds properties remotely — no GPS to read, so the
        // whole capture-on-load / re-capture-on-submit dance below is
        // skipped in favor of the search-and-select flow further down.
        const IS_REMOTE_ENTRY = @json($isRemoteEntry ?? false);

        function capture(options, hardCapMs) {
            const geo = new Promise((resolve) => {
                if (!('geolocation' in navigator)) return resolve(null);
                navigator.geolocation.getCurrentPosition(
                    (pos) => resolve(pos.coords.latitude.toFixed(6) + ',' + pos.coords.longitude.toFixed(6)),
                    () => resolve(null), // denied / unavailable — fall back silently, field stays optional
                    options
                );
            });
            // Belt-and-braces: some browsers don't count time spent waiting on
            // the permission prompt itself against getCurrentPosition's own
            // `timeout`, which could otherwise stall this indefinitely. This
            // guarantees we always move on within hardCapMs regardless.
            const hardCap = new Promise((resolve) => setTimeout(() => resolve(null), hardCapMs));
            return Promise.race([geo, hardCap]);
        }

        // ── Live "you are here" readout under Section A — reverse-geocodes
        // the captured coordinates into a human-readable place name, and
        // doubles as the source for what gets saved into form_submited_location. ──
        const locCountryEl = document.getElementById('current-location-country');
        const locSepEl = document.getElementById('current-location-sep');
        const locRestEl = document.getElementById('current-location-rest');
        const mapsLinkEl = document.getElementById('current-location-maps-link');

        // Cache of the last successfully resolved address, reused at submit
        // time so we don't re-hit the geocoding API (and its latency) right
        // when the officer is trying to submit.
        let lastAddress = '';
        let lastCountry = '';

        function setLocationLine(country, rest, muted) {
            if (locCountryEl) locCountryEl.textContent = country || '';
            if (locSepEl) locSepEl.textContent = (country && rest) ? '|' : '';
            if (locRestEl) {
                locRestEl.textContent = rest || '';
                locRestEl.className = (muted ? 'text-gray-400' : 'font-semibold text-zendo-navy') + ' flex-1';
            }
        }

        function updateMapsLink(coords) {
            if (!mapsLinkEl || !coords) return;
            mapsLinkEl.href = 'https://www.google.com/maps?q=' + coords;
            mapsLinkEl.classList.remove('hidden');
            mapsLinkEl.classList.add('flex');
        }

        // ── Live map preview (Mappls) — the map itself is created once, as
        // soon as the SDK reports ready (see window.__mapplsMapReady in the
        // script block that loads the SDK, above). We only place/move the
        // marker here. Guarded throughout since the SDK may not have loaded
        // (no token configured, network hiccup, etc.) — the rest of the
        // form must never depend on it. ──
        const mapEl = document.getElementById('mappls-map');
        let mapplsMarker = null;

        function updateMap(coords) {
            if (!mapEl || !coords || !window.__mapplsMapReady) return;
            const [lat, lng] = coords.split(',').map(Number);

            window.__mapplsMapReady.then((map) => {
                try {
                    if (!mapplsMarker) {
                        mapplsMarker = new mappls.Marker({ map: map, position: { lat, lng } });
                    } else if (mapplsMarker.setPosition) {
                        mapplsMarker.setPosition({ lat, lng });
                    }
                    // Note the reversed order here vs. the constructor's `center`
                    // option above: Mappls' setCenter() inherits directly from
                    // the underlying MapLibre/Mapbox GL engine, which expects
                    // [lng, lat] — confirmed by testing against the live SDK
                    // (getCenter() after setCenter([lat,lng]) came back with
                    // an invalid swapped result). The map constructor's own
                    // `center` option is a separate Mappls-specific parameter
                    // and stays [lat, lng].
                    if (map.setCenter) map.setCenter([lng, lat]);
                    if (map.setZoom) map.setZoom(16);
                } catch (e) {
                    console.error('Mappls marker update failed:', e);
                }
            });
        }

        // Resolves to { address, country } — empty strings if the lookup
        // fails. Proxied through our own backend (see FieldOfficer\PropertyEntryController::reverseGeocode)
        // rather than calling Mappls directly, so the access token stays server-side.
        // Also updates the visible readout as a side effect.
        function reverseGeocode(coords) {
            const [lat, lng] = coords.split(',');
            return fetch(`{{ route(match(true) {
                auth()->check() && auth()->user()->role === 'owner' => 'owner.location.reverse-geocode',
                auth()->check() && auth()->user()->role === 'supply_head' => 'supplyhead.location.reverse-geocode',
                default => 'field.location.reverse-geocode',
            }) }}?lat=${lat}&lng=${lng}`, {
                headers: { 'Accept': 'application/json' },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (!data || !data.address) {
                        setLocationLine('', 'Location detected, but address lookup failed.', true);
                        return { address: '', country: '' };
                    }
                    const address = data.address;
                    const country = data.country || '';
                    setLocationLine(country, address, false);
                    lastAddress = address;
                    lastCountry = country;
                    return { address: address, country: country };
                })
                .catch(() => {
                    setLocationLine('', 'Location detected, but address lookup failed.', true);
                    return { address: '', country: '' };
                });
        }

        function buildPayload(coords, address, country) {
            const [lat, long] = coords.split(',');
            return JSON.stringify({ address: address || '', country: country || '', lat: lat, long: long });
        }

        // Builds the final JSON payload for a set of coordinates — reuses the
        // cached address if we already have one (fast path, no extra network
        // call right before submitting); otherwise attempts a bounded lookup.
        function resolvePayload(coords) {
            if (lastAddress || lastCountry) {
                return Promise.resolve(buildPayload(coords, lastAddress, lastCountry));
            }
            return Promise.race([
                reverseGeocode(coords).then(({ address, country }) => buildPayload(coords, address, country)),
                new Promise((resolve) => setTimeout(() => resolve(buildPayload(coords, '', '')), 2500)),
            ]);
        }

        function onInitialCoords(coords) {
            updateMapsLink(coords);
            updateMap(coords);
            locInput.value = buildPayload(coords, '', ''); // provisional, refined below once resolved
            return reverseGeocode(coords).then(({ address, country }) => {
                locInput.value = buildPayload(coords, address, country);
                return { address, country };
            });
        }

        let hasExistingLocation = false;
        if (locInput.value) {
            try {
                const existing = JSON.parse(locInput.value);
                if (existing.lat && existing.long) {
                    hasExistingLocation = true;
                    const coords = existing.lat + ',' + existing.long;
                    updateMapsLink(coords);
                    updateMap(coords);
                    setLocationLine(existing.country || '', existing.address || '', false);
                    lastAddress = existing.address || '';
                    lastCountry = existing.country || '';
                }
            } catch (e) {}
        }

        if (!IS_REMOTE_ENTRY) {
            // Best-effort capture as soon as the page loads, so we have *something*
            // even if the officer submits before a fresh GPS fix comes through.
            // On mobile, a cold GPS fix after granting permission can easily take
            // longer than desktop's near-instant Wi-Fi/IP-based fix, so we give it
            // a generous window before falling back to a lower-accuracy attempt.
            if (!hasExistingLocation) {
                capture({ enableHighAccuracy: true, timeout: 15000, maximumAge: 60000 }, 20000).then((coords) => {
                    if (coords) {
                        onInitialCoords(coords);
                        return;
                    }
                    capture({ enableHighAccuracy: false, timeout: 10000, maximumAge: 60000 }, 12000).then((fallbackCoords) => {
                        if (fallbackCoords) {
                            onInitialCoords(fallbackCoords);
                        } else {
                            setLocationLine('', 'Current location unavailable — check your browser’s location permission.', true);
                        }
                    });
                });
            }

            // Re-capture right before the form actually submits, so the stored
            // location reflects where the officer was at submit time rather than
            // just page-load time. Falls back to the page-load value if a fresh
            const form = locInput.closest('form');
            if (form) {
                let resubmitting = false;
                form.addEventListener('submit', function (e) {
                    if (resubmitting) return;
                    
                    if (hasExistingLocation) {
                        // Let it submit normally without recapturing GPS
                        return;
                    }

                    e.preventDefault();
                    resubmitting = true;

                    const submitter = e.submitter;

                    // Block a second click (e.g. "Submit to Office" right after
                    // "Save Draft") while we're still waiting on geolocation.
                    // Leave the submitter itself enabled — a disabled button's
                    // name/value (e.g. action=draft) is dropped from the
                    // form data, which would make the server fall back to the
                    // "submit" action and run full validation instead of the
                    // draft's fields-optional validation.
                    form.querySelectorAll('button[type="submit"]').forEach(btn => {
                        if (btn !== submitter) btn.disabled = true;
                    });
                    capture({ enableHighAccuracy: true, timeout: 3000, maximumAge: 0 }, 4000).then((coords) => {
                        if (!coords) {
                            if (form.requestSubmit) { form.requestSubmit(submitter); } else { form.submit(); }
                            return;
                        }
                        updateMapsLink(coords);
                        updateMap(coords);
                        resolvePayload(coords).then((payload) => {
                            locInput.value = payload;
                            if (form.requestSubmit) {
                                form.requestSubmit(submitter);
                            } else {
                                form.submit();
                            }
                        });
                    });
                });
            }
        } else {
            // ── Supply head: search-and-select instead of GPS ──────────────
            // Binds Mappls' Place Search plugin (mappls.search) to the search
            // input added above. Selecting a suggestion feeds its coordinates
            // through the same onInitialCoords() pipeline used for GPS fixes
            // above, so the map preview, "Google Maps" link, reverse-geocoded
            // readout and hidden form_submited_location field all populate
            // exactly the same way.
            const searchInput = document.getElementById('supply-head-location-search');

            // The picked location is the property's address for this flow
            // (per the helper text above: "search for its location... and
            // pick the exact match") — so whatever ends up in the search box
            // also gets written into the actual Address/Country fields that
            // get submitted with the form, not just the hidden geo payload.
            const addressInput = document.querySelector('textarea[name="name_full_address"]');
            const countryInput = document.querySelector('input[name="country"]');
            function syncAddressRecord(address, country) {
                if (addressInput && address) addressInput.value = address;
                if (countryInput && country) countryInput.value = country;
            }

            // Clicking directly on the map is an alternate way to pick a spot
            // (search may not have an exact match, or the user just wants to
            // nudge the pin). Routes through the same onInitialCoords()
            // pipeline as search selection, then also writes the resolved
            // address into the search box so it reflects the clicked point
            // instead of being left stale from the last text search.
            //
            // Guarded to only fire for clicks that land directly on the map's
            // own <canvas> — placing/selecting a marker (e.g. via the search
            // flow's pinMarker() below) fires this same 'click' event with
            // e.originalEvent.target pointing at the marker's DOM element
            // instead, which would otherwise re-resolve the click's raw
            // coordinates and clobber the precise place name the search
            // selection had already written into the box with a generic
            // road/area-level reverse-geocoded address.
            let mapClickToken = 0;
            if (mapEl && window.__mapplsMapReady) {
                window.__mapplsMapReady.then((map) => {
                    map.on('click', function (e) {
                        try {
                            const target = e.originalEvent && e.originalEvent.target;
                            if (!target || target.tagName !== 'CANVAS') return;
                            const lngLat = e.lngLat;
                            if (!lngLat) return;
                            const coords = Number(lngLat.lat).toFixed(6) + ',' + Number(lngLat.lng).toFixed(6);
                            const token = ++mapClickToken;

                            // If the click landed on a labelled point of interest,
                            // prefer its name over a raw reverse-geocode — reverse
                            // geocoding only snaps to the nearest road/area and
                            // loses the specific place clicked (e.g. "Hitkarni
                            // College of Law" vs. just the road it sits on).
                            // Mirrors the exact property fallback chain Mappls'
                            // own SDK uses internally for its "Open with Mappls"
                            // click popup (see the mapsdk bundle's map.on('click')
                            // handler), so this matches what the popup itself shows.
                            let poiName = null;
                            try {
                                const features = map.queryRenderedFeatures(e.point);
                                const f = features && features[0];
                                if (f && f.layer && f.layer.type === 'symbol' && f.properties && f.properties.ELOC) {
                                    const p = f.properties;
                                    poiName = p.description || p.c || p.BLDG_NO || p.name_en || p.LBL_NME || null;
                                }
                            } catch (queryErr) {}

                            onInitialCoords(coords).then(({ address, country }) => {
                                if (!searchInput || token !== mapClickToken) return;
                                const finalText = poiName ? (address ? poiName + ', ' + address : poiName) : (address || coords);
                                searchInput.value = finalText;
                                syncAddressRecord(finalText, country);
                            });
                        } catch (err) {
                            console.error('Mappls map click handling failed:', err);
                        }
                    });
                });
            }

            function initSupplyHeadSearch() {
                if (!searchInput || typeof mappls === 'undefined' || !mappls.search) return;

                new mappls.search(searchInput, { region: 'IND', height: 300 }, function (data) {
                    // Autosuggest results only carry a place code (eLoc), not
                    // lat/lng directly — confirmed against the live plugin
                    // response (fields are type/placeName/placeAddress/eLoc/
                    // etc., no coordinates). pinMarker() is the documented way
                    // to resolve an eLoc into a real position, via the
                    // underlying marker's getLngLat(). We remove that marker
                    // immediately — updateMap() below (called through
                    // onInitialCoords) draws the actual marker the user sees.
                    if (!data || !data.length || !data[0].eLoc || !window.__mapplsMapReady) return;
                    const eloc = data[0].eLoc;

                    window.__mapplsMapReady.then((map) => {
                        mappls.pinMarker({ map: map, pin: eloc }, function (marker) {
                            try {
                                const lngLat = marker && marker.obj && marker.obj.getLngLat ? marker.obj.getLngLat() : null;
                                if (marker && marker.remove) marker.remove();
                                if (!lngLat) return;
                                const coords = Number(lngLat.lat).toFixed(6) + ',' + Number(lngLat.lng).toFixed(6);
                                onInitialCoords(coords).then(({ country }) => {
                                    // Mappls' own search plugin already wrote the
                                    // precise place name + address into searchInput
                                    // on selection — reuse that for the Address
                                    // field rather than our own reverse-geocode of
                                    // the same point, which (like the map-click
                                    // handler above) only resolves to the nearest
                                    // road/area, not the specific place picked.
                                    syncAddressRecord(searchInput.value, country);
                                });
                            } catch (e) {
                                console.error('Mappls eLoc resolution failed:', e);
                            }
                        });
                    });
                });
            }

            if (typeof mappls !== 'undefined' && mappls.search) {
                initSupplyHeadSearch();
            } else {
                // The plugins script (loaded async, callback=initMapplsSDK) may
                // not have finished evaluating yet — the map's own ready promise
                // is a convenient, already-existing signal that the SDK is live.
                if (window.__mapplsMapReady) {
                    window.__mapplsMapReady.then(initSupplyHeadSearch);
                }
            }
        }
    })();
</script>

{{-- Per-field format/required validation with inline messages below each
     input. This replaces the three hand-written PIN / phone / e-mail
     validators that used to live here — the shared component applies the
     same rules and the same error styling to every field on the form
     instead of just those three. Also used by all 13 dedicated property
     wizards via components/property-wizard-shell.blade.php. --}}
<x-wizard-field-validation />

<script>
    // ── PIN code autofill — Village / Tehsil / District / State / Country ──
    (function () {
        const pinInput = document.querySelector('input[name="postal_address_pin"]');
        if (!pinInput) return; // field not present in this form's config

        const villageInput = document.querySelector('input[name="village"]');
        const tehsilInput = document.querySelector('input[name="tehsil"]');
        const districtInput = document.querySelector('input[name="district"]');
        const stateInput = document.querySelector('input[name="state"]');
        const countryInput = document.querySelector('input[name="country"]');

        const statusEl = document.createElement('p');
        statusEl.className = 'mt-1 text-xs text-gray-400';
        pinInput.parentElement.appendChild(statusEl);

        // Shown only when a PIN code covers more than one post office/locality,
        // so the user can pick the right one instead of silently using the first.
        let localitySelect = null;
        if (villageInput) {
            localitySelect = document.createElement('select');
            localitySelect.className = 'mt-1 w-full px-2 py-1.5 border border-gray-300 rounded-md text-xs bg-white hidden';
            villageInput.parentElement.appendChild(localitySelect);
        }

        function setStatus(text, kind) {
            statusEl.textContent = text;
            statusEl.className = 'mt-1 text-xs ' + (kind === 'error' ? 'text-red-600' : kind === 'success' ? 'text-emerald-600' : 'text-gray-400');
        }

        function applyPostOffice(po) {
            if (villageInput && po.Name) villageInput.value = po.Name;
            if (tehsilInput) tehsilInput.value = (po.Block && po.Block !== 'NA') ? po.Block : (po.Division || '');
            if (districtInput && po.District) districtInput.value = po.District;
            if (stateInput && po.State) stateInput.value = po.State;
            if (countryInput && po.Country) countryInput.value = po.Country;
        }

        function populateLocalityPicker(offices) {
            if (!localitySelect) return;
            if (offices.length <= 1) {
                localitySelect.classList.add('hidden');
                localitySelect.innerHTML = '';
                return;
            }
            localitySelect.innerHTML = offices
                .map((po, i) => `<option value="${i}">${po.Name}${po.Block && po.Block !== 'NA' ? ' — ' + po.Block : ''}</option>`)
                .join('');
            localitySelect.classList.remove('hidden');
            localitySelect.onchange = () => applyPostOffice(offices[localitySelect.value]);
        }

        let lookupToken = 0;
        async function lookupPincode(pin) {
            const token = ++lookupToken;
            setStatus('Looking up PIN code…', 'muted');
            if (localitySelect) { localitySelect.classList.add('hidden'); localitySelect.innerHTML = ''; }

            try {
                const res = await fetch(`https://api.postalpincode.in/pincode/${pin}`);
                const data = await res.json();
                if (token !== lookupToken) return; // superseded by a newer lookup

                const result = Array.isArray(data) ? data[0] : null;
                const offices = result && result.Status === 'Success' ? (result.PostOffice || []) : [];

                if (!offices.length) {
                    setStatus('No location found for this PIN code.', 'error');
                    return;
                }

                applyPostOffice(offices[0]);
                populateLocalityPicker(offices);
                setStatus(
                    offices.length > 1
                        ? `Auto-filled from ${offices[0].Name} — ${offices.length - 1} more nearby, pick below if needed.`
                        : `Auto-filled from ${offices[0].Name}.`,
                    'success'
                );
            } catch (e) {
                if (token !== lookupToken) return;
                setStatus('Could not reach PIN code lookup service.', 'error');
            }
        }

        let debounceTimer = null;
        let lastLookedUp = pinInput.value.trim().length === 6 ? pinInput.value.trim() : '';
        pinInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const pin = pinInput.value.trim();
            if (pin.length !== 6) {
                setStatus('', 'muted');
                return;
            }
            debounceTimer = setTimeout(() => {
                if (pin === lastLookedUp) return;
                lastLookedUp = pin;
                lookupPincode(pin);
            }, 400);
        });
    })();
</script>

<script>
    // ── Camera API — direct camera capture, no file picker ──
    let _cameraStream = null;
    let _cameraSlotIdx = null;
    let _facingMode = 'environment';

    async function openCamera(slotIndex, slotLabel) {
        _cameraSlotIdx = slotIndex;
        document.getElementById('camera-slot-label').textContent = slotLabel;
        const modal = document.getElementById('camera-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        await _startStream();
    }

    async function _startStream() {
        if (_cameraStream) { _cameraStream.getTracks().forEach(t => t.stop()); _cameraStream = null; }
        const video = document.getElementById('camera-stream');
        try {
            _cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: _facingMode, width: { ideal: 1920 }, height: { ideal: 1080 } }, audio: false
            });
            video.srcObject = _cameraStream;
        } catch (err) {
            try {
                _cameraStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                video.srcObject = _cameraStream;
            } catch (e) {
                alert('Camera access denied or not available.');
                closeCamera();
            }
        }
    }

    async function flipCamera() {
        _facingMode = _facingMode === 'environment' ? 'user' : 'environment';
        await _startStream();
    }

    function capturePhoto() {
        const video = document.getElementById('camera-stream');
        const canvas = document.getElementById('camera-canvas');
        canvas.width = video.videoWidth || 1280;
        canvas.height = video.videoHeight || 720;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(blob => {
            const idx = _cameraSlotIdx;
            const file = new File([blob], 'photo_' + idx + '.jpg', { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('photo-' + idx).files = dt.files;

            const dataURL = canvas.toDataURL('image/jpeg', 0.92);
            const img = document.getElementById('preview-img-' + idx);
            const ph = document.getElementById('placeholder-' + idx);
            if (img) { img.src = dataURL; img.classList.remove('hidden'); }
            if (ph) { ph.classList.add('hidden'); }

            const btnLabel = document.getElementById('cam-btn-label-' + idx);
            const btn = document.getElementById('cam-btn-' + idx);
            if (btnLabel) btnLabel.textContent = 'Retake Photo';
            if (btn) { btn.classList.remove('bg-zendo-navy', 'text-white'); btn.classList.add('bg-gray-100', 'text-gray-600', 'border', 'border-gray-200'); }

            closeCamera();
        }, 'image/jpeg', 0.92);
    }

    function closeCamera() {
        if (_cameraStream) { _cameraStream.getTracks().forEach(t => t.stop()); _cameraStream = null; }
        const modal = document.getElementById('camera-modal');
        const video = document.getElementById('camera-stream');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        if (video) video.srcObject = null;
        document.body.style.overflow = '';
        _cameraSlotIdx = null;
    }

    function handleFileSelect(input, idx) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview-img-' + idx);
                const ph = document.getElementById('placeholder-' + idx);
                if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
                if (ph) { ph.classList.add('hidden'); }

                const btnLabel = document.getElementById('cam-btn-label-' + idx);
                const btn = document.getElementById('cam-btn-' + idx);
                if (btnLabel) btnLabel.textContent = 'Change Photo';
                if (btn) { btn.classList.remove('bg-zendo-navy', 'text-white'); btn.classList.add('bg-gray-100', 'text-gray-600', 'border', 'border-gray-200'); }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCamera(); });

    // ── Restricted Edit: lock correct fields for rejected+allow_resubmit ──
    @if($isRestrictedEdit)
        document.addEventListener('DOMContentLoaded', function () {
            const lockedFields = @json($correctFields);
            lockedFields.forEach(fieldName => {
                const el = document.querySelector('[name="' + fieldName + '"]');
                if (!el) return;
                el.disabled = true;
                el.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed', 'border-gray-200');
                const hidden = document.createElement('input');
                hidden.type = 'hidden'; hidden.name = fieldName; hidden.value = el.value;
                el.parentNode.appendChild(hidden);
            });
        });
    @endif

    // Rule: Plot Area >= Built-up Area >= Carpet Area
    // Behavior: if a value violates the hierarchy, it is auto-cleared and a brief warning is shown
    function validateAreaHierarchy(changedField) {
        const plotEl = document.querySelector('[name="plot_area"]');
        const builtEl = document.querySelector('[name="built_up_area"]');
        const carpetEl = document.querySelector('[name="carpet_area"]');

        if (!plotEl && !builtEl && !carpetEl) return true;

        // Clear any previous warning messages
        [plotEl, builtEl, carpetEl].forEach(el => {
            if (!el) return;
            const msg = el.parentElement.querySelector('.wiz-hierarchy-err');
            if (msg) msg.remove();
        });

        const plot = plotEl && plotEl.value !== '' ? parseFloat(plotEl.value) : null;
        const built = builtEl && builtEl.value !== '' ? parseFloat(builtEl.value) : null;
        const carpet = carpetEl && carpetEl.value !== '' ? parseFloat(carpetEl.value) : null;

        const showWarning = (el, text) => {
            if (!el) return;
            const msg = document.createElement('p');
            msg.className = 'wiz-hierarchy-err mt-1 text-xs text-red-600 font-medium';
            msg.textContent = text;
            el.parentElement.appendChild(msg);
            // auto-remove the warning after a few seconds
            setTimeout(() => msg.remove(), 3000);
        };

        // Built-up Area cannot exceed Plot Area
        if (plot !== null && built !== null && built > plot) {
            builtEl.value = '';
            showWarning(builtEl, `Cleared — Built-up Area cannot exceed Plot Area (${plot})`);
        }

        // Re-read built (in case it was just cleared above) before checking carpet
        const builtAfter = builtEl && builtEl.value !== '' ? parseFloat(builtEl.value) : null;

        // Carpet Area cannot exceed Built-up Area
        if (builtAfter !== null && carpet !== null && carpet > builtAfter) {
            carpetEl.value = '';
            showWarning(carpetEl, `Cleared — Carpet Area cannot exceed Built-up Area (${builtAfter})`);
        }

        return true;
    }

    // Live validation as user types — only clear the field that was just edited if it breaks the rule
    ['plot_area', 'built_up_area', 'carpet_area'].forEach(fieldName => {
        document.addEventListener('input', function (e) {
            if (e.target.name === fieldName) {
                validateAreaHierarchy(fieldName);
            }
        }, true);

        // Also validate on blur, in case value was pasted or changed via other means
        document.addEventListener('blur', function (e) {
            if (e.target.name === fieldName) {
                validateAreaHierarchy(fieldName);
            }
        }, true);
    });
</script>