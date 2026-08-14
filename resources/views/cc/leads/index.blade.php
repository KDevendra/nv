@extends('layouts.crm')
@section('title', 'CC Pipeline')
@section('page-title', 'Chief Coordinator Pipeline')

@section('page-actions')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
        {{ $stats['load_current'] >= $stats['load_cap'] ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' }}">
        Load {{ $stats['load_current'] }}/{{ $stats['load_cap'] }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Lead Pipeline</h2>
        <p class="text-gray-500 text-sm mt-1">Manage your assigned leads from escalation to deal close</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['Total',              $stats['total'],               'bg-blue-50 text-blue-700',   'border-blue-100'],
            ['Active',             $stats['active'],              'bg-green-50 text-green-700', 'border-green-100'],
            ['Awaiting Feasibility',$stats['feasibility_pending'],'bg-yellow-50 text-yellow-700','border-yellow-100'],
            ['SLA Breach',         $stats['sla_breached'],        'bg-red-50 text-red-700',     'border-red-100'],
        ] as [$label, $val, $cls, $b])
            <div class="bg-white rounded-xl border {{ $b }} p-4 shadow-sm">
                <div class="text-2xl font-heading font-bold {{ $cls }} rounded-lg py-1 text-center">{{ $val }}</div>
                <div class="text-xs text-gray-500 mt-1 font-medium text-center">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="stage" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <option value="">All Stages</option>
            @foreach(\App\Models\Lead::CC_STAGES as $s)
                <option value="{{ $s }}" {{ request('stage') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="side_state" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <option value="">Active + Held</option>
            <option value="on_hold"  {{ request('side_state')==='on_hold'  ? 'selected':'' }}>On Hold</option>
            <option value="deferred" {{ request('side_state')==='deferred' ? 'selected':'' }}>Deferred</option>
            <option value="lost"     {{ request('side_state')==='lost'     ? 'selected':'' }}>Lost</option>
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
                <p class="text-gray-500 font-medium">No leads in your pipeline.</p>
            </div>
        @else
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Zone</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Feasibility</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SLA</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SE</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($leads as $lead)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3 text-center text-xs text-gray-500 font-mono">{{ $leads->firstItem() + $loop->index }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-sm text-zendo-navy">{{ $lead->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                        {{ $lead->stage_label }}
                                    </span>
                                    @if($lead->side_state)
                                        <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">
                                            {{ $lead->side_state_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @if($lead->zone)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-purple-100 text-purple-800">
                                            {{ $lead->zone->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @if($lead->feasibility_status)
                                        <span class="font-semibold {{ match($lead->feasibility_status) { 'feasible'=>'text-green-600','not_feasible'=>'text-red-600',default=>'text-yellow-600'} }}">
                                            {{ ucfirst(str_replace('_',' ',$lead->feasibility_status)) }}
                                        </span>
                                    @else <span class="text-gray-400">—</span> @endif
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @if($lead->sla_feasibility_breached)
                                        <span class="text-red-600 font-semibold">⚠ Breached</span>
                                    @elseif($lead->sla_feasibility_due_at && $lead->stage === 'feasibility_check')
                                        <span class="text-gray-500">Due {{ $lead->sla_feasibility_due_at->diffForHumans() }}</span>
                                    @else <span class="text-gray-400">—</span> @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500">{{ $lead->assignedSE?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('cc.leads.show', $lead) }}"
                                        class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">View →</a>
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
                            <div>
                                <p class="text-sm font-semibold text-zendo-navy">{{ $lead->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $lead->phone }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                                {{ $lead->stage_label }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">SE: {{ $lead->assignedSE?->name ?? '—' }}</span>
                            <a href="{{ route('cc.leads.show', $lead) }}"
                                class="text-sm font-medium text-zendo-navy hover:text-zendo-gold">View →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($leads->hasPages())
        <div>{{ $leads->links() }}</div>
    @endif
</div>
@endsection
