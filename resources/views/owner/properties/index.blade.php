@extends('layouts.owner')
@section('title', 'My Property Listings - Owner Portal')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">My Property Listings</h2>
            <p class="text-gray-500 text-sm mt-1">Submit and track your commercial & warehousing properties</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('owner.properties.select-type') }}"
                class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                + List New Property
            </a>
        </div>
    </div>

    {{-- Listing Type Pill Tabs --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
    </style>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-3">
        @php
            $currentGroup = request('group');
            $currentType = request('type');
            $isAllGroupActive = !$currentGroup && !$currentType;

            $categoryPills = [
                [
                    'key' => 'all',
                    'label' => 'All Categories',
                    'icon_svg' => '<svg class="w-3.5 h-3.5 mr-1.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0V5"/></svg>',
                    'count' => $groupCounts['all'] ?? 0,
                    'active' => $isAllGroupActive,
                    'url' => route('owner.properties.index', request()->except(['group', 'type', 'page'])),
                ],
                [
                    'key' => 'warehousing',
                    'label' => 'Warehousing & Industrial',
                    'icon_svg' => '<svg class="w-3.5 h-3.5 mr-1.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V10l-6-4-6 4v11m12 0H5m14 0h2M5 21H3m2 0v-6a2 2 0 012-2h2a2 2 0 012 2v6m0 0h2"/></svg>',
                    'count' => $groupCounts['warehousing'] ?? 0,
                    'active' => $currentGroup === 'warehousing' || $currentType === 'warehouse',
                    'url' => route('owner.properties.index', array_merge(request()->except(['type', 'page']), ['group' => 'warehousing'])),
                ],
                [
                    'key' => 'residential',
                    'label' => 'Residential',
                    'icon_svg' => '<svg class="w-3.5 h-3.5 mr-1.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'count' => $groupCounts['residential'] ?? 0,
                    'active' => $currentGroup === 'residential' || in_array($currentType, ['apartment_flat_studio', 'house_villa_farmhouse', 'builder_floor', 'residential_plot_land', 'service_apartment_pg']),
                    'url' => route('owner.properties.index', array_merge(request()->except(['type', 'page']), ['group' => 'residential'])),
                ],
                [
                    'key' => 'commercial',
                    'label' => 'Commercial & Retail',
                    'icon_svg' => '<svg class="w-3.5 h-3.5 mr-1.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'count' => $groupCounts['commercial'] ?? 0,
                    'active' => $currentGroup === 'commercial' || in_array($currentType, ['office_space', 'retail_shop_showroom', 'sez_eou_stpi_unit', 'factory_manufacturing_industrial', 'commercial_institutional_land', 'agricultural_farm_land', 'multi_tenant_building', 'hotel_resort_guesthouse_banquet']),
                    'url' => route('owner.properties.index', array_merge(request()->except(['type', 'page']), ['group' => 'commercial'])),
                ],
            ];
        @endphp

        {{-- Category Level 1: Group Pills --}}
        <div class="flex flex-wrap items-center gap-2 pb-1">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 mr-2 shrink-0">Listing Group:</span>

            @foreach($categoryPills as $pill)
                <a href="{{ $pill['url'] }}"
                   class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all whitespace-nowrap {{ $pill['active'] ? 'bg-zendo-navy text-white shadow-sm ring-2 ring-zendo-navy/20' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900' }}">
                    {!! $pill['icon_svg'] !!}
                    <span>{{ $pill['label'] }}</span>
                    <span class="ml-2 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pill['active'] ? 'bg-white/20 text-white' : 'bg-white text-gray-700 shadow-xs' }}">
                        {{ $pill['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Category Level 2: Specific Property Type Pills --}}
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 mr-2 shrink-0">Property Type:</span>

            <a href="{{ route('owner.properties.index', array_merge(request()->except(['type', 'page']), $currentGroup ? ['group' => $currentGroup] : [])) }}"
               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all whitespace-nowrap {{ !$currentType ? 'bg-zendo-gold text-white font-semibold shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All Types
            </a>

            @foreach($propertyTypesConfig as $key => $typeMeta)
                @php
                    $pType = $typeMeta['property_type'] ?? $key;
                    $pGroup = $typeMeta['group'] ?? 'commercial';
                    $cnt = $typeCounts[$pType] ?? 0;

                    // Filter sub-pills if group filter is active
                    if ($currentGroup && $currentGroup !== $pGroup && !($currentGroup === 'warehousing' && $pType === 'warehouse')) {
                        continue;
                    }

                    $isTypeActive = $currentType === $pType;
                @endphp
                <a href="{{ route('owner.properties.index', array_merge(request()->except(['page']), ['type' => $pType])) }}"
                   class="inline-flex items-center px-3 py-1 rounded-full text-xs transition-all whitespace-nowrap {{ $isTypeActive ? 'bg-zendo-gold text-white font-semibold shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    <span>{{ $typeMeta['label'] }}</span>
                    @if($cnt > 0)
                        <span class="ml-1.5 px-1.5 py-0.2 rounded-full text-[10px] {{ $isTypeActive ? 'bg-white/25 text-white' : 'bg-gray-200 text-gray-700 font-semibold' }}">
                            {{ $cnt }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $stats = [
                ['label' => 'Total', 'value' => $counters['total'], 'cls' => 'bg-gray-100 text-gray-700', 'b' => 'border-gray-200', 'status' => ''],
                ['label' => 'Draft', 'value' => $counters['draft'] ?? 0, 'cls' => 'bg-gray-50 text-gray-500', 'b' => 'border-gray-200', 'status' => 'draft'],
                ['label' => 'Under Review', 'value' => $counters['submitted'], 'cls' => 'bg-blue-50 text-blue-700', 'b' => 'border-blue-100', 'status' => 'submitted'],
                ['label' => 'Verified', 'value' => $counters['verified'], 'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100', 'status' => 'verified'],
                ['label' => 'Recheck', 'value' => $counters['recheck'], 'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'status' => 'recheck'],
                ['label' => 'Rejected', 'value' => $counters['rejected'], 'cls' => 'bg-red-50 text-red-700', 'b' => 'border-red-100', 'status' => 'rejected'],
            ];
        @endphp

        @foreach($stats as $stat)
            @php
                $params = request()->except(['status', 'page']);
                if ($stat['status']) {
                    $params['status'] = $stat['status'];
                }
                $isActive = $stat['status']
                    ? request('status') === $stat['status']
                    : !request('status');
            @endphp
            <a href="{{ route('owner.properties.index', $params) }}"
                class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm hover:shadow transition-shadow block {{ $isActive ? 'ring-2 ring-offset-1 ring-zendo-gold' : '' }}">
                <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">
                    {{ $stat['value'] }}
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium">
                    {{ $stat['label'] }}
                </div>
            </a>
        @endforeach
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Entries Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($entries->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium mb-2">No property listings found</p>
                <a href="{{ route('owner.properties.select-type') }}"
                    class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                    + Submit Your First Property
                </a>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sr No.</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">City</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Facility Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Updated</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($entries as $entry)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 text-sm text-gray-500">
                                    {{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->nearest_city ?? '—' }}</td>
                                <td class="px-5 py-3 text-sm font-mono font-medium text-zendo-navy">{{ $entry->code }}</td>
                                <td class="px-5 py-3 text-sm text-gray-700">{{ $entry->facility_type ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                        {{ $entry->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $entry->updated_at->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-right space-x-3">
                                    @if($entry->isEditable())
                                        <a href="{{ $entry->owner_edit_url }}"
                                            class="text-sm font-medium {{ $entry->status === 'draft' ? 'text-gray-600 hover:text-gray-800' : ($entry->status === 'rejected' ? 'text-orange-600 hover:text-orange-800' : 'text-blue-600 hover:text-blue-800') }}">
                                            {{ $entry->status === 'draft' ? 'Continue' : ($entry->status === 'rejected' ? 'Re-edit' : 'Edit') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('owner.properties.show', $entry) }}"
                                        class="text-gray-600 hover:text-gray-800 text-sm font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($entries as $entry)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-zendo-navy rounded-full">{{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</span>
                                    <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $entry->nearest_city ?? '—' }} • {{ $entry->facility_type ?? '—' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                {{ $entry->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">{{ $entry->updated_at->format('d M Y') }}</span>
                            <div class="flex gap-3">
                                @if($entry->isEditable())
                                    <a href="{{ $entry->owner_edit_url }}"
                                        class="text-sm font-medium {{ $entry->status === 'draft' ? 'text-gray-600' : ($entry->status === 'rejected' ? 'text-orange-600' : 'text-blue-600') }}">
                                        {{ $entry->status === 'draft' ? 'Continue' : ($entry->status === 'rejected' ? 'Re-edit' : 'Edit') }}
                                    </a>
                                @endif
                                <a href="{{ route('owner.properties.show', $entry) }}"
                                    class="text-sm text-gray-600 font-medium">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($entries->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $entries->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
