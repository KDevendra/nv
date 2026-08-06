@extends('layouts.admin')

@section('title', 'Lead Management')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-heading text-zendo-navy">Lead Management</h2>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['label'=>'Total',        'value'=>$stats['total'],         'colour'=>'bg-blue-50 text-blue-700'],
            ['label'=>'Active',       'value'=>$stats['active'],        'colour'=>'bg-emerald-50 text-emerald-700'],
            ['label'=>'Holding Queue','value'=>$stats['holding_queue'], 'colour'=>'bg-purple-50 text-purple-700'],
            ['label'=>'Needs Review', 'value'=>$stats['needs_review'],  'colour'=>'bg-orange-50 text-orange-700'],
            ['label'=>'Deals Closed', 'value'=>$stats['deal_closed'],   'colour'=>'bg-green-50 text-green-700'],
            ['label'=>'Lost',         'value'=>$stats['lost'],          'colour'=>'bg-red-50 text-red-700'],
        ] as $s)
        <div class="{{ $s['colour'] }} rounded-xl p-3">
            <div class="text-xl font-bold">{{ $s['value'] }}</div>
            <div class="text-xs mt-0.5 opacity-70">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-4 text-sm" id="filter-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name / phone / email…"
            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-52">
        <select name="division" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5">
            <option value="">All Divisions</option>
            @foreach(['warehousing','residential','commercial'] as $d)
                <option value="{{ $d }}" {{ request('division')===$d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
            @endforeach
        </select>
        <select name="stage" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5">
            <option value="">All Stages</option>
            @foreach(\App\Models\Lead::STAGES as $s)
                <option value="{{ $s }}" {{ request('stage')===$s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="side_state" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-1.5">
            <option value="">All Side States</option>
            @foreach(['on_hold','deferred','lost'] as $ss)
                <option value="{{ $ss }}" {{ request('side_state')===$ss ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$ss)) }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-1.5 border border-gray-300 rounded-lg px-3 py-1.5 cursor-pointer">
            <input type="checkbox" name="holding_queue" value="1" {{ request()->boolean('holding_queue') ? 'checked' : '' }} onchange="this.form.submit()">
            <span>Holding Queue</span>
        </label>
        <label class="flex items-center gap-1.5 border border-orange-200 bg-orange-50 rounded-lg px-3 py-1.5 cursor-pointer text-orange-700">
            <input type="checkbox" name="needs_review" value="1" {{ request()->boolean('needs_review') ? 'checked' : '' }} onchange="this.form.submit()">
            <span>Needs Review</span>
        </label>
        <button type="submit" class="bg-zendo-navy text-white px-4 py-1.5 rounded-lg text-sm">Search</button>
        <a href="{{ route('admin.leads.index') }}" class="text-gray-500 hover:text-gray-700 px-3 py-1.5 text-sm">Reset</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm min-w-[900px]">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Lead</th>
                    <th class="px-4 py-3 text-left">Division</th>
                    <th class="px-4 py-3 text-left">Stage</th>
                    <th class="px-4 py-3 text-left">SE</th>
                    <th class="px-4 py-3 text-left">CC</th>
                    <th class="px-4 py-3 text-left">Flags</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 {{ $lead->trashed() ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $lead->name }}</div>
                        <div class="text-xs text-gray-500">{{ $lead->phone }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="stage-pill bg-slate-100 text-slate-600">{{ ucfirst($lead->division) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                        @if($lead->side_state)
                            <span class="stage-pill {{ $lead->side_state_badge }} mt-1">{{ $lead->side_state_label }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $lead->assignedSE?->name ?? '<span class="text-red-500 italic">Unassigned</span>' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600">
                        @if($lead->assigned_cc_id)
                            {{ $lead->assignedCC?->name }}
                        @else
                            <span class="text-purple-600 italic font-medium">Queue</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs space-y-0.5">
                        @if($lead->needs_division_review)
                            <span class="block text-orange-600 font-semibold">⚠ Division Review</span>
                        @endif
                        @if($lead->sla_contact_breached)
                            <span class="block text-red-600">SLA Contact</span>
                        @endif
                        @if($lead->sla_feasibility_breached)
                            <span class="block text-red-600">SLA Feasibility</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="text-zendo-navy hover:text-zendo-gold text-xs font-medium hover:underline">Manage</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-12 text-gray-400">No leads found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $leads->links() }}</div>
</div>
@endsection
