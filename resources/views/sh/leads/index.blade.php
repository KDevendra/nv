@extends('layouts.crm')

@section('title', 'Feasibility Queue — Supply Head')
@section('page-title', 'Feasibility Queue')

@section('sidebar-links')
    <nav class="space-y-1 text-sm">
        <a href="{{ route('sh.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg active text-white font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Feasibility Queue
        </a>
    </nav>
@endsection

@section('topbar-actions')
    <span class="text-xs bg-zendo-navy text-white px-3 py-1 rounded-full">{{ ucfirst($sh->division) }}</span>
@endsection

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @foreach([
        ['label'=>'Pending Response',  'value'=>$stats['pending'],      'colour'=>'bg-yellow-50 text-yellow-700'],
        ['label'=>'Responded',         'value'=>$stats['responded'],    'colour'=>'bg-emerald-50 text-emerald-700'],
        ['label'=>'SLA Breached',      'value'=>$stats['sla_breached'], 'colour'=>'bg-red-50 text-red-700'],
    ] as $s)
    <div class="{{ $s['colour'] }} rounded-xl p-4">
        <div class="text-2xl font-bold">{{ $s['value'] }}</div>
        <div class="text-xs mt-1 opacity-75">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" class="flex gap-2 mb-4">
    <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
        <option value="pending"  {{ request('status','pending')==='pending'  ? 'selected' : '' }}>Pending</option>
        <option value="feasible" {{ request('status')==='feasible'           ? 'selected' : '' }}>Feasible</option>
        <option value="not_feasible" {{ request('status')==='not_feasible'   ? 'selected' : '' }}>Not Feasible</option>
        <option value="conditional"  {{ request('status')==='conditional'    ? 'selected' : '' }}>Conditional</option>
        <option value="all"          {{ request('status')==='all'            ? 'selected' : '' }}>All</option>
    </select>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Lead</th>
                <th class="px-4 py-3 text-left">Requested</th>
                <th class="px-4 py-3 text-left">SLA</th>
                <th class="px-4 py-3 text-left">CC</th>
                <th class="px-4 py-3 text-left">Status</th>
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
                <td class="px-4 py-3 text-xs text-gray-500">{{ $lead->feasibility_requested_at?->diffForHumans() ?? '—' }}</td>
                <td class="px-4 py-3 text-xs">
                    @if($lead->sla_feasibility_breached)
                        <span class="text-red-600 font-semibold">⚠ Breached</span>
                    @elseif($lead->sla_feasibility_due_at && $lead->feasibility_status === 'pending')
                        <span class="text-gray-500">Due {{ $lead->sla_feasibility_due_at->diffForHumans() }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $lead->assignedCC?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($lead->feasibility_status === 'pending')
                        <span class="stage-pill bg-yellow-100 text-yellow-700">Pending</span>
                    @elseif($lead->feasibility_status === 'feasible')
                        <span class="stage-pill bg-green-100 text-green-700">Feasible</span>
                    @elseif($lead->feasibility_status === 'not_feasible')
                        <span class="stage-pill bg-red-100 text-red-700">Not Feasible</span>
                    @else
                        <span class="stage-pill bg-orange-100 text-orange-700">Conditional</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('sh.leads.show', $lead) }}" class="text-zendo-navy hover:text-zendo-gold text-xs font-medium hover:underline">
                        {{ $lead->feasibility_status === 'pending' ? 'Respond' : 'View' }}
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center py-12 text-gray-400">No feasibility requests found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $leads->links() }}</div>
@endsection
