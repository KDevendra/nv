@extends('layouts.crm')
@section('title', 'Feasibility Queue — Supply Head')
@section('page-title', 'Feasibility Queue')

@section('content')
<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Feasibility Queue</h2>
        <p class="text-gray-500 text-sm mt-1">Respond to feasibility requests from Chief Coordinators</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-3 gap-4">
        @foreach([
            ['Pending Response', $stats['pending'],      'bg-yellow-50 text-yellow-700', 'border-yellow-100'],
            ['Responded',        $stats['responded'],    'bg-green-50 text-green-700',   'border-green-100'],
            ['SLA Breached',     $stats['sla_breached'], 'bg-red-50 text-red-700',       'border-red-100'],
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

    {{-- Filter --}}
    <form method="GET" class="flex gap-3">
        <select name="status" onchange="this.form.submit()"
            class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <option value="pending"     {{ request('status','pending')==='pending'     ? 'selected':'' }}>Pending</option>
            <option value="feasible"    {{ request('status')==='feasible'              ? 'selected':'' }}>Feasible</option>
            <option value="not_feasible"{{ request('status')==='not_feasible'          ? 'selected':'' }}>Not Feasible</option>
            <option value="conditional" {{ request('status')==='conditional'           ? 'selected':'' }}>Conditional</option>
            <option value="all"         {{ request('status')==='all'                   ? 'selected':'' }}>All</option>
        </select>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($leads->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500 font-medium">No feasibility requests found.</p>
            </div>
        @else
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Requested</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SLA</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">CC</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
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
                                <td class="px-5 py-3 text-xs text-gray-500">
                                    {{ $lead->feasibility_requested_at?->diffForHumans() ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-xs">
                                    @if($lead->sla_feasibility_breached)
                                        <span class="text-red-600 font-semibold">⚠ Breached</span>
                                    @elseif($lead->sla_feasibility_due_at && $lead->feasibility_status === 'pending')
                                        <span class="text-gray-500">Due {{ $lead->sla_feasibility_due_at->diffForHumans() }}</span>
                                    @else <span class="text-gray-400">—</span> @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500">{{ $lead->assignedCC?->name ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $fCls = match($lead->feasibility_status) {
                                            'feasible'     => 'bg-green-100 text-green-700',
                                            'not_feasible' => 'bg-red-100 text-red-700',
                                            'conditional'  => 'bg-orange-100 text-orange-700',
                                            default        => 'bg-yellow-100 text-yellow-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $fCls }}">
                                        {{ ucfirst(str_replace('_',' ', $lead->feasibility_status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('sh.leads.show', $lead) }}"
                                        class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                                        {{ $lead->feasibility_status === 'pending' ? 'Respond →' : 'View →' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($leads as $lead)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-sm font-semibold text-zendo-navy">{{ $lead->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $lead->phone }}</p>
                            </div>
                            @php $fCls = match($lead->feasibility_status) { 'feasible'=>'bg-green-100 text-green-700','not_feasible'=>'bg-red-100 text-red-700','conditional'=>'bg-orange-100 text-orange-700',default=>'bg-yellow-100 text-yellow-700' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $fCls }}">
                                {{ ucfirst(str_replace('_',' ', $lead->feasibility_status ?? 'pending')) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">{{ $lead->feasibility_requested_at?->diffForHumans() ?? '—' }}</span>
                            <a href="{{ route('sh.leads.show', $lead) }}"
                                class="text-sm font-medium text-zendo-navy hover:text-zendo-gold">
                                {{ $lead->feasibility_status === 'pending' ? 'Respond →' : 'View →' }}
                            </a>
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
