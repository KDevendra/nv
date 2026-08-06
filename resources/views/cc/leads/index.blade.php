@extends('layouts.crm')

@section('title', 'CC Pipeline')
@section('page-title', 'Chief Coordinator Pipeline')

@section('sidebar-links')
    <nav class="space-y-1 text-sm">
        <a href="{{ route('cc.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg active text-white font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            Pipeline
        </a>
    </nav>
@endsection

@section('topbar-actions')
    <span class="text-xs {{ $stats['load_current'] >= $stats['load_cap'] ? 'bg-red-600' : 'bg-zendo-navy' }} text-white px-3 py-1 rounded-full">
        Load {{ $stats['load_current'] }}/{{ $stats['load_cap'] }} · {{ ucfirst($cc->division) }}
    </span>
@endsection

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total',              'value'=>$stats['total'],               'colour'=>'bg-blue-50 text-blue-700'],
        ['label'=>'Active',             'value'=>$stats['active'],              'colour'=>'bg-emerald-50 text-emerald-700'],
        ['label'=>'Awaiting Feasibility','value'=>$stats['feasibility_pending'],'colour'=>'bg-yellow-50 text-yellow-700'],
        ['label'=>'SLA Breach',         'value'=>$stats['sla_breached'],        'colour'=>'bg-red-50 text-red-700'],
    ] as $s)
    <div class="{{ $s['colour'] }} rounded-xl p-4">
        <div class="text-2xl font-bold">{{ $s['value'] }}</div>
        <div class="text-xs mt-1 opacity-75">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="flex flex-wrap gap-2 mb-4">
    <select name="stage" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        <option value="">All Stages</option>
        @foreach(\App\Models\Lead::CC_STAGES as $s)
            <option value="{{ $s }}" {{ request('stage')===$s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="side_state" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        <option value="">Active + Held</option>
        <option value="on_hold"  {{ request('side_state')==='on_hold'  ? 'selected' : '' }}>On Hold</option>
        <option value="deferred" {{ request('side_state')==='deferred' ? 'selected' : '' }}>Deferred</option>
        <option value="lost"     {{ request('side_state')==='lost'     ? 'selected' : '' }}>Lost</option>
    </select>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Lead</th>
                <th class="px-4 py-3 text-left">Stage</th>
                <th class="px-4 py-3 text-left">Feasibility</th>
                <th class="px-4 py-3 text-left">SLA</th>
                <th class="px-4 py-3 text-left">SE</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($leads as $lead)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                    <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                    @if($lead->side_state)
                        <span class="stage-pill {{ $lead->side_state_badge }} mt-1">{{ $lead->side_state_label }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs">
                    @if($lead->feasibility_status)
                        <span class="font-medium {{ match($lead->feasibility_status) { 'feasible'=>'text-green-600','not_feasible'=>'text-red-600',default=>'text-yellow-600'} }}">
                            {{ ucfirst(str_replace('_',' ',$lead->feasibility_status)) }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs">
                    @if($lead->sla_feasibility_breached)
                        <span class="text-red-600 font-semibold">⚠ Breached</span>
                    @elseif($lead->sla_feasibility_due_at && $lead->stage === 'feasibility_check')
                        <span class="text-gray-500">Due {{ $lead->sla_feasibility_due_at->diffForHumans() }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $lead->assignedSE?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('cc.leads.show', $lead) }}" class="text-zendo-navy hover:text-zendo-gold text-xs font-medium underline-offset-2 hover:underline">View</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-12 text-gray-400">No leads found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $leads->links() }}</div>
@endsection
