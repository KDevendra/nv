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
        <div class="flex flex-col items-end gap-2">
            <a href="{{ route('field.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('supplyhead.properties.create') }}"
                class="inline-flex items-center px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Property
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        @php
            $stats = [
                ['label' => 'Total',          'value' => $counters['total'],      'cls' => 'bg-gray-100 text-gray-700',    'b' => 'border-gray-200',   'status' => ''],
                ['label' => 'Under Review', 'value' => $counters['pending'],    'cls' => 'bg-blue-50 text-blue-700',     'b' => 'border-blue-100',   'status' => 'submitted'],
                ['label' => 'Verified',       'value' => $counters['verified'],   'cls' => 'bg-green-50 text-green-700',   'b' => 'border-green-100',  'status' => 'verified'],
                ['label' => 'Rejected',       'value' => $counters['rejected'],   'cls' => 'bg-red-50 text-red-700',       'b' => 'border-red-100',    'status' => 'rejected'],
                ['label' => 'Recheck',        'value' => $counters['recheck'],    'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'status' => 'recheck'],
                ['label' => 'Not Opened',     'value' => $counters['not_opened'], 'cls' => 'bg-purple-50 text-purple-700', 'b' => 'border-purple-200', 'status' => ''],
            ];
        @endphp
        @foreach($stats as $stat)
            @php
                $params = $stat['status'] ? ['status' => $stat['status']] : [];
                $isActive = $stat['status']
                    ? request('status') === $stat['status']
                    : (empty($stat['status']) && $loop->first && !request('status'));
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

    {{-- ═══════════════════════════════════════════════════════════════════════
         MY DRAFTS — entries this supply head started but hasn't submitted yet.
         Kept out of the two tables below, which only list submitted entries.
    ══════════════════════════════════════════════════════════════════════════ --}}
    @if($drafts->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                    <h3 class="text-sm font-semibold text-gray-800">My Drafts</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-700">
                        {{ $drafts->count() }}
                    </span>
                </div>
                <p class="text-xs text-gray-500">Not submitted yet — pick up where you left off</p>
            </div>

            <ul class="divide-y divide-gray-100">
                @foreach($drafts as $draft)
                    <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-zendo-navy truncate">
                                {{ $draft->property_name ?: 'Untitled property' }}
                                <span class="font-mono text-xs text-gray-400 ml-1">{{ $draft->code }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $draft->nearest_city ?: '—' }}
                                · Last saved {{ $draft->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('supplyhead.properties.edit', $draft) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-zendo-navy text-white text-xs font-semibold rounded-lg hover:bg-opacity-90 transition-all whitespace-nowrap">
                            Continue
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-100 shadow-sm p-3">
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
                @foreach(['submitted' => 'Under Review', 'verified' => 'Verified', 'rejected' => 'Rejected', 'recheck' => 'Recheck'] as $val => $lbl)
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

    {{-- ═══════════════════════════════════════════════════════════════════════
         TABLE 1 — NOT OPENED (shown above, always visible)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-purple-200 overflow-hidden">
        {{-- Section header --}}
        <div class="flex items-center justify-between px-5 py-3 bg-purple-50 border-b border-purple-200">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <h3 class="text-sm font-semibold text-purple-900">Not Yet Opened</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                    {{ $notOpenedEntries->count() }}
                </span>
            </div>
            <p class="text-xs text-purple-600">These entries have not been reviewed yet</p>
        </div>

        @if($notOpenedEntries->isEmpty())
            <div class="py-8 text-center">
                <svg class="mx-auto w-10 h-10 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-gray-500 font-medium">All caught up! No unread entries.</p>
            </div>
        @else
            {{-- Desktop table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-purple-50/60 border-b border-purple-100">
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">City</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Officer</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Facility Type</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notOpenedEntries as $i => $entry)
                            <tr class="bg-amber-50 hover:bg-amber-100 transition-colors border-b border-amber-100/80 group">
                                <td class="px-4 py-3 text-xs text-gray-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $entry->nearest_city ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
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
                                        class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-900 text-sm font-semibold group-hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="md:hidden divide-y divide-amber-100">
                @foreach($notOpenedEntries as $entry)
                    <div class="p-4 bg-amber-50 hover:bg-amber-100 transition-colors">
                        <div class="flex items-start justify-between mb-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="inline-block w-2 h-2 rounded-full bg-amber-400 flex-shrink-0 mt-1.5"></span>
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
                                class="text-sm text-purple-600 font-semibold flex items-center gap-1">
                                Open
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         TABLE 2 — ALL ENTRIES (paginated, with filters)
    ══════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Section header --}}
        <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-700">All Submissions</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-600">
                    {{ $entries->total() }}
                </span>
            </div>
            @if(request()->hasAny(['search', 'status', 'field_officer']))
                <span class="text-xs text-zendo-gold font-medium">Filtered results</span>
            @endif
        </div>

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
                        @foreach($entries as $entry)
                            @php
                                $isUnread = is_null($entry->supply_head_viewed_at);
                                $srNo     = ($entries->currentPage() - 1) * $entries->perPage() + $loop->iteration;
                                $rowBase  = $isUnread ? 'bg-amber-50' : ($loop->odd ? 'bg-white' : 'bg-gray-50/50');
                            @endphp
                            <tr class="{{ $rowBase }} hover:bg-blue-50 transition-colors duration-100 border-b border-gray-100/80 group">
                                <td class="px-4 py-3 text-xs text-gray-400 font-medium">{{ $srNo }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                    {{ $entry->nearest_city ?? '—' }}
                                    @if($isUnread)
                                        <span class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-amber-400 align-middle" title="Not yet opened"></span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-mono font-semibold text-zendo-navy">{{ $entry->code }}</span>
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
                                    @if($isUnread)
                                        <span class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-amber-400 align-middle"></span>
                                    @endif
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
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Footer: legend + pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
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
        @if($entries->hasPages())
            <div>{{ $entries->appends(request()->query())->links() }}</div>
        @endif
    </div>

</div>
@endsection
