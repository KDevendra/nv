@extends('layouts.admin')

@section('title', 'Property Entry Report - ZendoIndia Admin')

@section('page-title', 'Property Entry Report')
@section('page-description', 'Full report of all field officer property entries with filters and Excel export.')

@section('content')

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

{{-- ── Summary Stat Cards (unfiltered, always full dataset) ─────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    {{-- Total --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Entries</p>
                <p class="text-2xl font-bold text-zendo-navy">{{ $summary['total'] }}</p>
            </div>
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Submitted / Pending --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Under Review</p>
                <p class="text-2xl font-bold text-blue-600">{{ $summary['submitted'] }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Verified --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Verified</p>
                <p class="text-2xl font-bold text-green-600">{{ $summary['verified'] }}</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Recheck --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Needs Recheck</p>
                <p class="text-2xl font-bold text-orange-500">{{ $summary['recheck'] }}</p>
            </div>
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rejected --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Rejected</p>
                <p class="text-2xl font-bold text-red-600">{{ $summary['rejected'] }}</p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ── Filter Bar ────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">
    <form method="GET" action="{{ route('admin.property-entry-report.index') }}" id="filter-form">
        <div class="flex flex-wrap gap-3 items-end">

            {{-- Supply Head --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Supply Head</label>
                <select name="supply_head_id" id="filter-supply-head"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Supply Heads</option>
                    @foreach($supplyHeads as $sh)
                        <option value="{{ $sh->id }}" {{ request('supply_head_id') == $sh->id ? 'selected' : '' }}>
                            {{ $sh->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Field Officer --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Field Officer</label>
                <select name="officer_id" id="filter-officer"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Officers</option>
                    @foreach($officers as $officer)
                        <option value="{{ $officer->id }}" {{ request('officer_id') == $officer->id ? 'selected' : '' }}>
                            {{ $officer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" id="filter-status"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Facility Type --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Facility Type</label>
                <select name="facility_type" id="filter-facility"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Types</option>
                    @foreach($facilityTypes as $type)
                        <option value="{{ $type }}" {{ request('facility_type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- City --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">City</label>
                <select name="city" id="filter-city"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>

            {{-- Date To --}}
            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>

            {{-- Apply + Clear + Export --}}
            <div class="flex items-end gap-2 flex-shrink-0">
                <button type="submit"
                    class="px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                    Apply
                </button>
                <a href="{{ route('admin.property-entry-report.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all">
                    Clear
                </a>
                <a href="{{ route('admin.property-entry-report.export', request()->query()) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all shadow">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

{{-- ── Active Filter Pills ───────────────────────────────────────────────── --}}
@php
    $activeFilters = array_filter([
        'supply_head_id' => request('supply_head_id') ? ['label' => 'Supply Head: ' . ($supplyHeads->firstWhere('id', request('supply_head_id'))?->name ?? request('supply_head_id')), 'key' => 'supply_head_id'] : null,
        'officer_id'    => request('officer_id')    ? ['label' => 'Officer: ' . ($officers->firstWhere('id', request('officer_id'))?->name ?? request('officer_id')), 'key' => 'officer_id'] : null,
        'status'        => request('status')        ? ['label' => 'Status: ' . ucfirst(request('status')),                'key' => 'status']        : null,
        'facility_type' => request('facility_type') ? ['label' => 'Type: '   . request('facility_type'),                  'key' => 'facility_type'] : null,
        'city'          => request('city')          ? ['label' => 'City: '   . request('city'),                            'key' => 'city']          : null,
        'date_from'     => request('date_from')     ? ['label' => 'From: '   . request('date_from'),                      'key' => 'date_from']     : null,
        'date_to'       => request('date_to')       ? ['label' => 'To: '     . request('date_to'),                        'key' => 'date_to']       : null,
    ]);
@endphp

@if(count($activeFilters))
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($activeFilters as $filter)
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-zendo-navy text-white">
                {{ $filter['label'] }}
                <a href="{{ route('admin.property-entry-report.index', array_merge(request()->except($filter['key']))) }}"
                    class="ml-1 hover:text-gray-300 transition-colors" title="Remove filter">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </span>
        @endforeach
    </div>
@endif

{{-- ── Results Count + Table ────────────────────────────────────────────── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="text-lg font-semibold text-zendo-navy font-heading">All Property Entries</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                Showing <span class="font-medium text-gray-700">{{ $entries->firstItem() ?? 0 }}–{{ $entries->lastItem() ?? 0 }}</span>
                of <span class="font-medium text-gray-700">{{ $entries->total() }}</span>
                {{ Str::plural('entry', $entries->total()) }}
                @if(count($activeFilters)) <span class="text-zendo-gold">(filtered)</span> @endif
            </p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">#</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Code</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Supply Head</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Field Officer</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Facility Type</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">City</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Built-up Area</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Submitted</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide whitespace-nowrap">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ ($entries->currentPage() - 1) * $entries->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono font-semibold text-zendo-navy text-xs">{{ $entry->code }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">
                            {{ $entry->supplyHead?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                            {{ $entry->fieldOfficer?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            {{ $entry->facility_type ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            {{ $entry->nearest_city ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            {{ $entry->built_up_area ? number_format($entry->built_up_area) . ' sq ft' : '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                            {{ $entry->submitted_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.property-entry-report.show', $entry) }}"
                                class="inline-flex items-center text-xs font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                                View
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No entries found</p>
                            <p class="text-gray-400 text-xs mt-1">Try adjusting your filters</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($entries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $entries->links() }}
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    // Build officer options map keyed by supply_head_id for dynamic filtering
    var officersBySupplyHead = {
        '': [
            @foreach($officers as $officer)
            { id: {{ $officer->id }}, name: {{ json_encode($officer->name) }} },
            @endforeach
        ],
        @foreach($supplyHeads as $sh)
        '{{ $sh->id }}': [
            @foreach($sh->fieldOfficers as $fo)
            { id: {{ $fo->id }}, name: {{ json_encode($fo->name) }} },
            @endforeach
        ],
        @endforeach
    };

    var selectedOfficerId = '{{ request('officer_id') }}';

    function rebuildOfficerDropdown(supplyHeadId) {
        var select = document.getElementById('filter-officer');
        if (!select) return;
        var list = officersBySupplyHead[supplyHeadId] || officersBySupplyHead[''];
        select.innerHTML = '<option value="">All Officers</option>';
        list.forEach(function(o) {
            var opt = document.createElement('option');
            opt.value = o.id;
            opt.textContent = o.name;
            if (String(o.id) === selectedOfficerId) opt.selected = true;
            select.appendChild(opt);
        });
    }

    // Initialise officer dropdown based on current supply head selection
    var supplyHeadSel = document.getElementById('filter-supply-head');
    if (supplyHeadSel) {
        rebuildOfficerDropdown(supplyHeadSel.value);
        supplyHeadSel.addEventListener('change', function() {
            selectedOfficerId = '';   // reset officer when supply head changes
            rebuildOfficerDropdown(this.value);
            document.getElementById('filter-form').submit();
        });
    }

    // Auto-submit on dropdown change
    ['filter-status', 'filter-facility', 'filter-city', 'filter-officer'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        }
    });
</script>
@endsection
