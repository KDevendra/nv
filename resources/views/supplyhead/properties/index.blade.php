@extends('layouts.field')
@section('title', 'Property Submissions')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Property Submissions</h2>
            <p class="text-gray-500 text-sm mt-1">Review and take action on field officer submissions</p>
        </div>
        <a href="{{ route('field.dashboard') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        @php
            $stats = [
                ['label' => 'Total',         'value' => $counters['total'],      'cls' => 'bg-gray-100 text-gray-700',    'b' => 'border-gray-200',   'status' => '',          'not_opened' => false],
                ['label' => 'Pending Review', 'value' => $counters['pending'],    'cls' => 'bg-blue-50 text-blue-700',     'b' => 'border-blue-100',   'status' => 'submitted', 'not_opened' => false],
                ['label' => 'Verified',       'value' => $counters['verified'],   'cls' => 'bg-green-50 text-green-700',   'b' => 'border-green-100',  'status' => 'verified',  'not_opened' => false],
                ['label' => 'Rejected',       'value' => $counters['rejected'],   'cls' => 'bg-red-50 text-red-700',       'b' => 'border-red-100',    'status' => 'rejected',  'not_opened' => false],
                ['label' => 'Recheck',        'value' => $counters['recheck'],    'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'status' => 'recheck',   'not_opened' => false],
                ['label' => 'Not Opened',     'value' => $counters['not_opened'], 'cls' => 'bg-purple-50 text-purple-700', 'b' => 'border-purple-200', 'status' => '',          'not_opened' => true],
            ];
        @endphp
        @foreach($stats as $stat)
            @php
                $params = [];
                if ($stat['not_opened']) $params['not_opened'] = '1';
                elseif ($stat['status']) $params['status'] = $stat['status'];
                $isActive = $stat['not_opened']
                    ? request()->boolean('not_opened')
                    : (request('status') === $stat['status'] && !request()->boolean('not_opened'));
            @endphp
            <a href="{{ route('supplyhead.properties.index', $params) }}"
                class="bg-white rounded-xl border {{ $stat['b'] }} p-4 text-center shadow-sm hover:shadow transition-shadow block {{ $isActive ? 'ring-2 ring-offset-1 ring-zendo-gold' : '' }}">
                <div class="text-2xl font-heading font-bold {{ $stat['cls'] }} rounded-lg py-1">{{ $stat['value'] }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium">{{ $stat['label'] }}</div>
            </a>
        @endforeach
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Active not-opened indicator --}}
    @if(request()->boolean('not_opened'))
        <div class="flex items-center gap-2 text-sm text-purple-700 bg-purple-50 border border-purple-200 rounded-lg px-4 py-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Showing only <strong class="mx-1">not-opened</strong> entries
            <a href="{{ route('supplyhead.properties.index') }}" class="ml-auto text-purple-600 hover:text-purple-900 font-semibold">Clear ×</a>
        </div>
    @endif

    {{-- Filters — all in one row --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
        @if(request()->boolean('not_opened'))
            <input type="hidden" name="not_opened" value="1">
        @endif
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search code, city, facility, officer..."
                class="flex-1 min-w-[180px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">

            <select name="field_officer"
                class="w-44 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                <option value="">All Officers</option>
                @foreach($fieldOfficers as $officer)
                    <option value="{{ $officer->id }}" {{ request('field_officer') == $officer->id ? 'selected' : '' }}>
                        {{ $officer->name }}
                    </option>
                @endforeach
            </select>

            <select name="status"
                class="w-40 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                <option value="">All Status</option>
                @foreach(['submitted' => 'Pending Review', 'verified' => 'Verified', 'rejected' => 'Rejected', 'recheck' => 'Recheck'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>

            <button type="submit"
                class="px-5 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all whitespace-nowrap">
                Filter
            </button>
            <a href="{{ route('supplyhead.properties.index') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-all whitespace-nowrap text-center">
                Clear
            </a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($entries->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium">No entries found</p>
                <a href="{{ route('supplyhead.properties.index') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Clear filters</a>
            </div>
        @else
            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">City</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Officer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Facility Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $i => $entry)
                            @php
                                $isUnread = is_null($entry->supply_head_viewed_at);
                                $rowBase  = $isUnread ? 'bg-amber-50' : ($i % 2 === 0 ? 'bg-white' : 'bg-gray-50/70');
                                $srNo     = ($entries->currentPage() - 1) * $entries->perPage() + $loop->iteration;
                            @endphp
                            <tr class="{{ $rowBase }} hover:bg-blue-50 transition-colors duration-100 border-b border-gray-100/80 group">
                                <td class="px-4 py-3 text-xs text-gray-400 font-medium">{{ $srNo }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $entry->nearest_city ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $entry->fieldOfficer?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $entry->facility_type ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }}">
                                        {{ $entry->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $entry->submitted_at?->format('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('supplyhead.properties.show', $entry) }}"
                                        class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 text-sm font-semibold group-hover:underline">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($entries as $entry)
                    @php
                        $isUnread = is_null($entry->supply_head_viewed_at);
                        $srNo     = ($entries->currentPage() - 1) * $entries->perPage() + $loop->iteration;
                    @endphp
                    <div class="p-4 {{ $isUnread ? 'bg-amber-50' : '' }} hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs text-gray-400 flex-shrink-0">{{ $srNo }}.</span>
                                <div class="min-w-0">
                                    <span class="text-sm font-semibold text-gray-800">{{ $entry->nearest_city ?? '—' }}</span>
                                    <span class="text-xs font-mono text-zendo-navy ml-1">{{ $entry->code }}</span>
                                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ $entry->fieldOfficer?->name }} · {{ $entry->facility_type ?? '—' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $entry->status_badge_class }} flex-shrink-0 ml-2">
                                {{ $entry->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-400">{{ $entry->submitted_at?->format('d M Y') ?? '—' }}</span>
                            <a href="{{ route('supplyhead.properties.show', $entry) }}"
                                class="text-sm text-blue-600 font-semibold flex items-center gap-1">
                                Review
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Footer: legend + pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        {{-- Legend --}}
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-amber-400"></span>
                Not yet opened
            </div>
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded bg-white border border-gray-300"></span>
                Opened
            </div>
        </div>

        {{-- Pagination --}}
        @if($entries->hasPages())
            <div>{{ $entries->appends(request()->query())->links() }}</div>
        @endif
    </div>

</div>
@endsection
