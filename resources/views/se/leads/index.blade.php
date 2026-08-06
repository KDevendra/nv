@extends('layouts.crm')

@section('title', 'My Leads — Sales Executive')
@section('page-title', 'My Leads')

@section('sidebar-links')
    <nav class="space-y-1 text-sm">
        <a href="{{ route('se.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg active text-white font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            My Leads
        </a>
    </nav>
@endsection

@section('topbar-actions')
    <span class="text-xs bg-zendo-navy text-white px-3 py-1 rounded-full">
        {{ $stats['load_cap'] ?? 'SE' }} · {{ ucfirst($se->division) }}
    </span>
@endsection

@section('content')
{{-- Stats row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total',       'value'=>$stats['total'],        'colour'=>'bg-blue-50 text-blue-700'],
        ['label'=>'Active',      'value'=>$stats['active'],       'colour'=>'bg-emerald-50 text-emerald-700'],
        ['label'=>'On Hold',     'value'=>$stats['on_hold'],      'colour'=>'bg-red-50 text-red-700'],
        ['label'=>'SLA Breach',  'value'=>$stats['sla_breached'], 'colour'=>'bg-orange-50 text-orange-700'],
    ] as $s)
    <div class="{{ $s['colour'] }} rounded-xl p-4">
        <div class="text-2xl font-bold">{{ $s['value'] }}</div>
        <div class="text-xs mt-1 opacity-75">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="flex flex-wrap gap-2 mb-4 text-sm">
    <select name="stage" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        <option value="">All Stages</option>
        @foreach(\App\Models\Lead::SE_STAGES as $s)
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

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Lead</th>
                <th class="px-4 py-3 text-left">Stage</th>
                <th class="px-4 py-3 text-left">Side State</th>
                <th class="px-4 py-3 text-left">Contacts</th>
                <th class="px-4 py-3 text-left">SLA</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($leads as $lead)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                    <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                    @if($lead->email)
                        <div class="text-xs text-gray-400">{{ $lead->email }}</div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="stage-pill {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
                </td>
                <td class="px-4 py-3 text-center font-semibold">{{ $lead->contact_attempts }}</td>
                <td class="px-4 py-3">
                    @if($lead->sla_contact_breached)
                        <span class="text-xs text-red-600 font-semibold">⚠ Breached</span>
                    @elseif($lead->sla_contact_due_at)
                        <span class="text-xs text-gray-500">Due {{ $lead->sla_contact_due_at->diffForHumans() }}</span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('se.leads.show', $lead) }}" class="text-zendo-navy hover:text-zendo-gold font-medium text-xs underline-offset-2 hover:underline">View</a>
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
