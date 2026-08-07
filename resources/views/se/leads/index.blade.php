@extends('layouts.crm')
@section('title', 'My Leads — Sales Executive')
@section('page-title', 'My Leads')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">My Leads</h2>
            <p class="text-gray-500 text-sm mt-1">Track and manage your assigned leads</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    @php
        $statCards = [
            ['label' => 'Total',      'value' => $stats['total'],        'cls' => 'bg-blue-50 text-blue-700',   'b' => 'border-blue-100',   'filter' => ''],
            ['label' => 'Active',     'value' => $stats['active'],       'cls' => 'bg-green-50 text-green-700', 'b' => 'border-green-100',  'filter' => ''],
            ['label' => 'On Hold',    'value' => $stats['on_hold'],      'cls' => 'bg-red-50 text-red-700',     'b' => 'border-red-100',    'filter' => 'on_hold'],
            ['label' => 'SLA Breach', 'value' => $stats['sla_breached'], 'cls' => 'bg-orange-50 text-orange-700','b' => 'border-orange-200','filter' => ''],
        ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($statCards as $s)
            <div class="bg-white rounded-xl border {{ $s['b'] }} p-4 shadow-sm">
                <div class="text-2xl font-heading font-bold {{ $s['cls'] }} rounded-lg py-1 text-center">
                    {{ $s['value'] }}
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium text-center">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <select name="stage" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <option value="">All Stages</option>
            @foreach(\App\Models\Lead::SE_STAGES as $s)
                <option value="{{ $s }}" {{ request('stage') === $s ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $s)) }}
                </option>
            @endforeach
        </select>
        <select name="side_state" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <option value="">Active + Held</option>
            <option value="on_hold"  {{ request('side_state') === 'on_hold'  ? 'selected' : '' }}>On Hold</option>
            <option value="deferred" {{ request('side_state') === 'deferred' ? 'selected' : '' }}>Deferred</option>
            <option value="lost"     {{ request('side_state') === 'lost'     ? 'selected' : '' }}>Lost</option>
        </select>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($leads->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-500 font-medium">No leads found.</p>
                <p class="text-gray-400 text-sm mt-1">New leads will appear here once assigned by your admin.</p>
            </div>
        @else
            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacts</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SLA</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($leads as $lead)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-sm text-zendo-navy">{{ $lead->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                                    @if($lead->email)<div class="text-xs text-gray-400">{{ $lead->email }}</div>@endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                        {{ $lead->stage_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">
                                        {{ $lead->side_state_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center text-sm font-semibold text-gray-700">
                                    {{ $lead->contact_attempts }}
                                </td>
                                <td class="px-5 py-3 text-sm">
                                    @if($lead->sla_contact_breached)
                                        <span class="text-red-600 font-semibold text-xs">⚠ Breached</span>
                                    @elseif($lead->sla_contact_due_at)
                                        <span class="text-gray-500 text-xs">Due {{ $lead->sla_contact_due_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('se.leads.show', $lead) }}"
                                        class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                                        View →
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
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-zendo-navy">{{ $lead->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $lead->phone }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                {{ $lead->stage_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">
                                    {{ $lead->side_state_label }}
                                </span>
                                @if($lead->sla_contact_breached)
                                    <span class="text-xs text-red-600 font-semibold">⚠ SLA</span>
                                @endif
                            </div>
                            <a href="{{ route('se.leads.show', $lead) }}"
                                class="text-sm font-medium text-zendo-navy hover:text-zendo-gold">View →</a>
                        </div>
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
