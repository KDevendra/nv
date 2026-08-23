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

@php
    // Every stat card and chart segment links back to this same page with
    // its dimension swapped in — start from the current filters (minus the
    // dimension the link itself controls) so clicking one never discards
    // other active filters.
    $urlWithStatus = fn (?string $status) => route('admin.property-entry-report.index', array_merge(
        request()->except(['status', 'page']), $status ? ['status' => $status] : []
    ));
    $urlWithType = fn (?string $type) => route('admin.property-entry-report.index', array_merge(
        request()->except(['property_type', 'page']), $type ? ['property_type' => $type] : []
    ));
@endphp

{{-- ── Summary Stat Cards (unfiltered, always full dataset — click to filter table below) ─────────────── --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    {{-- Total --}}
    <a href="{{ $urlWithStatus(null) }}"
        class="bg-white rounded-xl shadow-sm p-6 border transition-all hover:shadow-md hover:-translate-y-0.5 {{ !request('status') ? 'border-zendo-navy ring-1 ring-zendo-navy' : 'border-gray-100' }}">
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
    </a>

    {{-- Submitted / Pending --}}
    <a href="{{ $urlWithStatus('submitted') }}"
        class="bg-white rounded-xl shadow-sm p-6 border transition-all hover:shadow-md hover:-translate-y-0.5 {{ request('status') === 'submitted' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-gray-100' }}">
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
    </a>

    {{-- Verified --}}
    <a href="{{ $urlWithStatus('verified') }}"
        class="bg-white rounded-xl shadow-sm p-6 border transition-all hover:shadow-md hover:-translate-y-0.5 {{ request('status') === 'verified' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-100' }}">
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
    </a>

    {{-- Recheck --}}
    <a href="{{ $urlWithStatus('recheck') }}"
        class="bg-white rounded-xl shadow-sm p-6 border transition-all hover:shadow-md hover:-translate-y-0.5 {{ request('status') === 'recheck' ? 'border-orange-500 ring-1 ring-orange-500' : 'border-gray-100' }}">
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
    </a>

    {{-- Rejected --}}
    <a href="{{ $urlWithStatus('rejected') }}"
        class="bg-white rounded-xl shadow-sm p-6 border transition-all hover:shadow-md hover:-translate-y-0.5 {{ request('status') === 'rejected' ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-100' }}">
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
    </a>
</div>

{{-- ── Draft vs Submitted+ ratio ─────────────────────────────────────────── --}}
@php
    $dvs = $analytics['draft_vs_submitted'];
    $dvsTotal = $dvs['draft'] + $dvs['beyond_draft'];
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
        <div>
            <h3 class="text-sm font-semibold text-zendo-navy">Draft vs Submitted+</h3>
            <p class="text-xs text-gray-500 mt-0.5">
                @if($dvsTotal > 0)
                    <span class="font-semibold text-gray-700">{{ $dvs['draft'] }} drafts</span>,
                    <span class="font-semibold text-gray-700">{{ $dvs['beyond_draft'] }} submitted or further</span>
                    — {{ $dvs['draft_percent'] }}% of entries never left draft
                @else
                    No entries match the current filters.
                @endif
            </p>
        </div>
        @if($dvs['draft_percent'] >= 50 && $dvsTotal > 0)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 w-fit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Majority stuck in draft
            </span>
        @endif
    </div>
    <div class="w-full h-3 rounded-full bg-gray-100 overflow-hidden flex">
        @if($dvsTotal > 0)
            <a href="{{ $urlWithStatus('draft') }}" title="{{ $dvs['draft'] }} drafts — click to filter"
                class="h-full bg-gray-400 hover:bg-gray-500 transition-colors" style="width: {{ $dvs['draft_percent'] }}%"></a>
            {{-- Not a single status value, so not a filter link — "beyond draft" spans submitted/verified/recheck/rejected --}}
            <div title="{{ $dvs['beyond_draft'] }} submitted or further"
                class="h-full bg-zendo-gold flex-1"></div>
        @endif
    </div>
</div>

{{-- ── By Property Type + Submissions Over Time ───────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- By Property Type --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-zendo-navy">By Property Type</h3>
            @if(request('property_type'))
                <a href="{{ $urlWithType(null) }}" class="text-xs text-zendo-gold hover:underline">Clear</a>
            @endif
        </div>
        <div class="relative" style="height: 340px;">
            <canvas id="chart-by-type"></canvas>
        </div>
    </div>

    {{-- Submissions Over Time --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-zendo-navy">Submissions Over Time</h3>
            <div class="flex gap-1 bg-gray-100 rounded-lg p-0.5" id="time-range-toggle">
                <button type="button" data-range="7" class="time-range-btn px-2.5 py-1 text-xs font-medium rounded-md transition-colors">7d</button>
                <button type="button" data-range="30" class="time-range-btn px-2.5 py-1 text-xs font-medium rounded-md transition-colors">30d</button>
                <button type="button" data-range="90" class="time-range-btn px-2.5 py-1 text-xs font-medium rounded-md transition-colors">90d</button>
                <button type="button" data-range="all" class="time-range-btn px-2.5 py-1 text-xs font-medium rounded-md transition-colors">All</button>
            </div>
        </div>
        <div class="relative" style="height: 340px;">
            <canvas id="chart-submissions"></canvas>
        </div>
    </div>
</div>

{{-- ── By City + By Field Officer leaderboards ────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    @foreach([['title' => 'By City', 'data' => $analytics['by_city']], ['title' => 'By Field Officer', 'data' => $analytics['by_officer']]] as $board)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5" x-data="{ expanded: false }">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-zendo-navy">{{ $board['title'] }}</h3>
                <span class="text-xs text-gray-400">{{ $board['data']['total_count'] }} total</span>
            </div>

            @if(empty($board['data']['top']))
                <p class="text-xs text-gray-400 italic py-4 text-center">No data for the current filters.</p>
            @else
                @php $maxCount = max(array_column($board['data']['top'], 'count')) ?: 1; @endphp
                <ul class="space-y-2.5" x-show="!expanded">
                    @foreach($board['data']['top'] as $row)
                        <li>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-700 truncate pr-2">{{ $row['label'] }}</span>
                                <span class="font-semibold text-gray-500 flex-shrink-0">{{ $row['count'] }}</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-zendo-navy rounded-full" style="width: {{ round($row['count'] / $maxCount * 100) }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if($board['data']['total_count'] > 5)
                    <ul class="space-y-2.5" x-show="expanded" x-cloak>
                        @foreach($board['data']['all'] as $row)
                            <li>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-gray-700 truncate pr-2">{{ $row['label'] }}</span>
                                    <span class="font-semibold text-gray-500 flex-shrink-0">{{ $row['count'] }}</span>
                                </div>
                                <div class="w-full h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full bg-zendo-navy rounded-full" style="width: {{ round($row['count'] / $maxCount * 100) }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <button type="button" @click="expanded = !expanded"
                        class="mt-3 text-xs font-medium text-zendo-gold hover:underline">
                        <span x-show="!expanded">Show all {{ $board['data']['total_count'] }}</span>
                        <span x-show="expanded" x-cloak>Show top 5 only</span>
                    </button>
                @endif
            @endif
        </div>
    @endforeach
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

            {{-- Zone --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Zone</label>
                <select name="zone_id" id="filter-zone"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Zones</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                            {{ $zone->name }}
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
        'zone_id'       => request('zone_id')       ? ['label' => 'Zone: ' . ($zones->firstWhere('id', request('zone_id'))?->name ?? request('zone_id')), 'key' => 'zone_id'] : null,
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
        @foreach($officersBySupplyHead as $supplyHeadId => $zoneOfficers)
        '{{ $supplyHeadId }}': [
            @foreach($zoneOfficers as $fo)
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── By Property Type ────────────────────────────────────────────────────
    @php
        $byTypeJs = collect($analytics['by_property_type'])->map(fn ($r) => [
            'key'   => $r['key'],
            'label' => $r['label'],
            'count' => $r['count'],
            'url'   => $urlWithType(request('property_type') === $r['key'] ? null : $r['key']),
        ]);
    @endphp
    var byTypeData = @json($byTypeJs);

    // Stable per-type colour, cycling a categorical palette rather than
    // hardcoding 13 colours by hand — order matches config('property_types').
    var typePalette = ['#0B2C3D', '#B39359', '#2563EB', '#059669', '#DC2626', '#7C3AED',
        '#EA580C', '#0891B2', '#DB2777', '#65A30D', '#4F46E5', '#CA8A04', '#0D9488', '#9333EA'];

    (function () {
        var ctx = document.getElementById('chart-by-type');
        if (!ctx || !byTypeData.length) return;

        var activeType = {{ request('property_type') ? json_encode(request('property_type')) : 'null' }};

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: byTypeData.map(function (r) { return r.label; }),
                datasets: [{
                    data: byTypeData.map(function (r) { return r.count; }),
                    backgroundColor: byTypeData.map(function (r, i) {
                        var base = typePalette[i % typePalette.length];
                        return (activeType && r.key !== activeType) ? base + '55' : base;
                    }),
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 } },
                    y: { ticks: { autoSkip: false, font: { size: 11 } } },
                },
                onClick: function (evt, elements) {
                    if (!elements.length) return;
                    window.location.href = byTypeData[elements[0].index].url;
                },
                onHover: function (evt, elements) {
                    evt.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                },
            }
        });
    })();

    // ── Submissions Over Time (7d / 30d / 90d / all toggle) ────────────────
    var submissionsDaily = @json(collect($analytics['submissions_daily'])->map(fn ($r) => ['label' => \Carbon\Carbon::parse($r['date'])->format('d M'), 'count' => $r['count']]));
    var submissionsMonthly = @json(collect($analytics['submissions_monthly'])->map(fn ($r) => ['label' => \Carbon\Carbon::createFromFormat('Y-m', $r['month'])->format('M Y'), 'count' => $r['count']]));

    (function () {
        var ctx = document.getElementById('chart-submissions');
        if (!ctx) return;

        function sliceRange(range) {
            if (range === 'all') return submissionsMonthly;
            var n = parseInt(range, 10);
            return submissionsDaily.slice(Math.max(0, submissionsDaily.length - n));
        }

        var initial = sliceRange('30');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initial.map(function (r) { return r.label; }),
                datasets: [{
                    data: initial.map(function (r) { return r.count; }),
                    borderColor: '#B39359',
                    backgroundColor: 'rgba(179, 147, 89, 0.12)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxTicksLimit: 12, font: { size: 10 } } },
                },
            }
        });

        var buttons = document.querySelectorAll('.time-range-btn');
        function setActiveRange(range) {
            buttons.forEach(function (b) {
                var isActive = b.dataset.range === range;
                b.classList.toggle('bg-white', isActive);
                b.classList.toggle('shadow-sm', isActive);
                b.classList.toggle('text-zendo-navy', isActive);
                b.classList.toggle('text-gray-500', !isActive);
            });
            var rows = sliceRange(range);
            chart.data.labels = rows.map(function (r) { return r.label; });
            chart.data.datasets[0].data = rows.map(function (r) { return r.count; });
            chart.update();
        }

        buttons.forEach(function (b) {
            b.addEventListener('click', function () { setActiveRange(b.dataset.range); });
        });
        setActiveRange('30');
    })();
</script>
@endsection
