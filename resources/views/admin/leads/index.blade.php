@extends('layouts.admin')
@section('title', 'CRM Lead Pipeline')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Lead Management</h2>
            <p class="text-gray-500 text-sm mt-1">Track and manage CRM lead pipeline across all divisions</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-6 gap-4">
        @php
            $statCards = [
                ['label' => 'Total', 'value' => $stats['total'], 'cls' => 'bg-gray-100 text-gray-700', 'b' => 'border-gray-200', 'key' => ''],
                ['label' => 'Active', 'value' => $stats['active'], 'cls' => 'bg-emerald-50 text-emerald-700', 'b' => 'border-emerald-100', 'key' => 'active'],
                ['label' => 'Holding Queue', 'value' => $stats['holding_queue'], 'cls' => 'bg-purple-50 text-purple-700', 'b' => 'border-purple-100', 'key' => 'holding_queue'],
                ['label' => 'Needs Review', 'value' => $stats['needs_review'], 'cls' => 'bg-orange-50 text-orange-700', 'b' => 'border-orange-200', 'key' => 'needs_review'],
                ['label' => 'Deals Closed', 'value' => $stats['deal_closed'], 'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100', 'key' => 'deal_closed'],
                ['label' => 'Lost', 'value' => $stats['lost'], 'cls' => 'bg-red-50 text-red-700', 'b' => 'border-red-100', 'key' => 'lost'],
            ];
        @endphp

        @foreach($statCards as $stat)
            @php
                $isActive = match($stat['key']) {
                    'active' => !request()->filled('holding_queue') && !request()->filled('needs_review') && !request()->filled('side_state') && !request()->filled('stage'),
                    'holding_queue' => request()->boolean('holding_queue'),
                    'needs_review' => request()->boolean('needs_review'),
                    'deal_closed' => request('stage') === 'deal_closed',
                    'lost' => request('side_state') === 'lost',
                    default => !request()->hasAny(['holding_queue', 'needs_review', 'side_state', 'stage', 'division', 'search'])
                };
            @endphp
            <a href="{{ route('admin.leads.index', match($stat['key']) {
                    'holding_queue' => ['holding_queue' => 1],
                    'needs_review' => ['needs_review' => 1],
                    'deal_closed' => ['stage' => 'deal_closed'],
                    'lost' => ['side_state' => 'lost'],
                    default => []
                }) }}"
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

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end" id="filter-form">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs text-gray-500 block mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search name / phone / email…"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-1">Division</label>
                <select name="division" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Divisions</option>
                    @foreach(['warehousing','residential','commercial'] as $d)
                        <option value="{{ $d }}" {{ request('division')===$d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-1">Stage</label>
                <select name="stage" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Stages</option>
                    @foreach(\App\Models\Lead::STAGES as $s)
                        <option value="{{ $s }}" {{ request('stage')===$s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-1">Side State</label>
                <select name="side_state" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Side States</option>
                    @foreach(['on_hold','deferred','lost'] as $ss)
                        <option value="{{ $ss }}" {{ request('side_state')===$ss ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$ss)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="holding_queue" value="1" {{ request()->boolean('holding_queue') ? 'checked' : '' }} 
                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                    <span class="text-sm text-gray-600">Holding Queue</span>
                </label>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="needs_review" value="1" {{ request()->boolean('needs_review') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                    <span class="text-sm text-gray-600">Needs Review</span>
                </label>
            </div>
            <button type="submit" class="bg-zendo-navy hover:bg-opacity-90 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all shadow">
                Search
            </button>
            <a href="{{ route('admin.leads.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium transition-colors">
                Reset
            </a>
        </form>
    </div>

    {{-- Leads Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($leads->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <p class="text-gray-500 font-medium mb-2">No leads found</p>
                <p class="text-gray-400 text-sm">Try adjusting your filters or search criteria</p>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Division</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SE</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CC</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Flags</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($leads as $lead)
                            <tr class="hover:bg-gray-50 transition-colors {{ $lead->trashed() ? 'opacity-60' : '' }}">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                                    @if($lead->email)
                                        <div class="text-xs text-gray-400">{{ $lead->email }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($lead->division) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                        {{ $lead->stage_label }}
                                    </span>
                                    @if($lead->side_state)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }} mt-1">
                                            {{ $lead->side_state_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    @if($lead->assignedSE)
                                        {{ $lead->assignedSE->name }}
                                    @else
                                        <span class="text-red-500 italic text-xs">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-700">
                                    @if($lead->assigned_cc_id)
                                        {{ $lead->assignedCC?->name }}
                                    @else
                                        <span class="text-purple-600 italic font-medium text-xs">Holding Queue</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-col gap-1">
                                        @if($lead->needs_division_review)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-700">
                                                ⚠ Division Review
                                            </span>
                                        @endif
                                        @if($lead->sla_contact_breached)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                SLA Contact
                                            </span>
                                        @endif
                                        @if($lead->sla_feasibility_breached)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                SLA Feasibility
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.leads.show', $lead) }}" 
                                        class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($leads as $lead)
                    <div class="p-4 {{ $lead->trashed() ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 mb-1">{{ $lead->name }}</div>
                                <p class="text-xs text-gray-500">{{ $lead->phone }}</p>
                                @if($lead->email)
                                    <p class="text-xs text-gray-400">{{ $lead->email }}</p>
                                @endif
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($lead->division) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                        {{ $lead->stage_label }}
                                    </span>
                                    @if($lead->side_state)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">
                                            {{ $lead->side_state_label }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 pt-2 mt-2">
                            <div class="text-xs text-gray-500">
                                <div>SE: {{ $lead->assignedSE?->name ?? '—' }}</div>
                                <div>CC: {{ $lead->assignedCC?->name ?? 'Queue' }}</div>
                            </div>
                            <a href="{{ route('admin.leads.show', $lead) }}" 
                                class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                                Manage
                            </a>
                        </div>
                        @if($lead->needs_division_review || $lead->sla_contact_breached || $lead->sla_feasibility_breached)
                            <div class="flex flex-wrap gap-1 mt-2 pt-2 border-t border-gray-100">
                                @if($lead->needs_division_review)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-700">
                                        ⚠ Division Review
                                    </span>
                                @endif
                                @if($lead->sla_contact_breached)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                        SLA Contact
                                    </span>
                                @endif
                                @if($lead->sla_feasibility_breached)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                        SLA Feasibility
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($leads->hasPages())
        <div>{{ $leads->links() }}</div>
    @endif

</div>
@endsection
