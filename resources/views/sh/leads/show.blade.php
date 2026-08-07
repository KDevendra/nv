@extends('layouts.crm')
@section('title', 'Feasibility — ' . $lead->name)
@section('page-title', 'Feasibility Request')

@section('page-actions')
    <a href="{{ route('sh.leads.index') }}"
        class="text-sm text-gray-500 hover:text-zendo-navy flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Queue
    </a>
@endsection

@section('content')
<div class="space-y-6">
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

{{-- ── Left col ── --}}
<div class="xl:col-span-2 space-y-5">

    {{-- Lead summary --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->phone }}
                    @if($lead->email) · {{ $lead->email }} @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                    {{ $lead->stage_label }}
                </span>
                @if($lead->sla_feasibility_breached)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        ⚠ SLA Breached
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div><span class="font-medium text-gray-700">Division:</span> {{ ucfirst($lead->division) }}</div>
            <div><span class="font-medium text-gray-700">CC:</span> {{ $lead->assignedCC?->name ?? '—' }}</div>
            <div><span class="font-medium text-gray-700">Requested:</span>
                {{ $lead->feasibility_requested_at?->format('d M Y, H:i') ?? '—' }}</div>
            <div>
                <span class="font-medium text-gray-700">SLA Due:</span>
                @if($lead->sla_feasibility_due_at)
                    <span class="{{ $lead->sla_feasibility_breached ? 'text-red-600 font-semibold' : 'text-gray-600' }} ml-1">
                        {{ $lead->sla_feasibility_due_at->diffForHumans() }}
                    </span>
                @else <span class="text-gray-400 ml-1">—</span> @endif
            </div>
        </div>

        @if($lead->qualification_notes)
            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-900">
                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">Lead Qualification Notes (from SE)</div>
                {{ $lead->qualification_notes }}
            </div>
        @endif
    </div>

    {{-- Property snapshot --}}
    @if(!empty($propertySnapshot))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-heading text-zendo-navy mb-3">Property Details</h4>
        <div class="grid grid-cols-2 gap-3 text-sm text-gray-600">
            <div><span class="font-medium text-gray-700">Title:</span> {{ $propertySnapshot['title'] }}</div>
            <div><span class="font-medium text-gray-700">Type:</span> {{ $propertySnapshot['property_type'] ?? '—' }}</div>
            <div><span class="font-medium text-gray-700">City:</span> {{ $propertySnapshot['city'] ?? '—' }}</div>
            <div><span class="font-medium text-gray-700">Location:</span> {{ $propertySnapshot['location'] ?? '—' }}</div>
            <div><span class="font-medium text-gray-700">Price:</span> ₹{{ number_format($propertySnapshot['price']) }}</div>
            <div><span class="font-medium text-gray-700">Area:</span> {{ $propertySnapshot['carpet_area'] ?? '—' }} sqft</div>
        </div>
    </div>
    @endif

    {{-- Response form or existing response --}}
    @if($lead->feasibility_status === 'pending')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-heading text-zendo-navy mb-4">Submit Feasibility Response</h4>
        <form data-ajax-form data-redirect="{{ route('sh.leads.index') }}"
            action="{{ route('sh.leads.respond', $lead) }}" method="POST" class="space-y-5">
            @csrf

            {{-- Status radio buttons --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Feasibility Status <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([
                        ['feasible',     'Feasible',     'border-green-300  bg-green-50  text-green-800',  'peer-checked:border-green-500  peer-checked:bg-green-100'],
                        ['not_feasible', 'Not Feasible', 'border-red-300    bg-red-50    text-red-800',    'peer-checked:border-red-500    peer-checked:bg-red-100'],
                        ['conditional',  'Conditional',  'border-orange-300 bg-orange-50 text-orange-800', 'peer-checked:border-orange-500 peer-checked:bg-orange-100'],
                    ] as [$val, $lbl, $activeCls, $peerCls])
                        <label class="relative flex items-center justify-center cursor-pointer">
                            <input type="radio" name="feasibility_status" value="{{ $val }}" required
                                class="peer sr-only">
                            <div class="w-full px-3 py-2.5 border-2 rounded-xl text-center text-sm font-semibold transition-all
                                border-gray-200 bg-white text-gray-600 hover:border-gray-300
                                peer-checked:{{ $activeCls }}">
                                {{ $lbl }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Assessment Notes <span class="text-red-500">*</span>
                </label>
                <textarea name="feasibility_notes" required rows="6" minlength="10"
                    placeholder="Provide a detailed feasibility assessment — availability, pricing, conditions, constraints…"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40 resize-none"></textarea>
                <p class="text-xs text-gray-400 mt-1">Minimum 10 characters required.</p>
            </div>

            <button type="submit"
                class="inline-flex items-center px-5 py-2.5 bg-zendo-navy text-white text-sm font-semibold rounded-xl hover:bg-opacity-90 transition-all shadow">
                Submit Response
            </button>
        </form>
    </div>

    @else
    {{-- Already responded --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-heading text-zendo-navy mb-4">Your Response</h4>
        <div class="flex items-center gap-3 mb-4">
            @php
                $resCls = match($lead->feasibility_status) {
                    'feasible'     => 'bg-green-100 text-green-700 border border-green-200',
                    'not_feasible' => 'bg-red-100 text-red-700 border border-red-200',
                    default        => 'bg-orange-100 text-orange-700 border border-orange-200',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $resCls }}">
                {{ ucfirst(str_replace('_', ' ', $lead->feasibility_status)) }}
            </span>
            <span class="text-xs text-gray-500">
                Responded {{ $lead->feasibility_responded_at?->diffForHumans() ?? '' }}
            </span>
        </div>
        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm text-gray-700 whitespace-pre-line">
            {{ $lead->feasibility_notes }}
        </div>
    </div>
    @endif

</div>{{-- end left col --}}

{{-- ── Right col: Stage History ── --}}
<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-heading text-zendo-navy mb-4">Stage History</h4>
        <ol class="relative border-l-2 border-gray-100 space-y-4 ml-3">
            @forelse($history as $h)
                <li class="ml-5">
                    <span class="absolute -left-2 mt-1 w-4 h-4 bg-zendo-gold rounded-full border-2 border-white shadow"></span>
                    <div class="text-xs font-semibold text-gray-700">
                        {{ $h->from_stage_label }} → {{ $h->to_stage_label }}
                    </div>
                    @if($h->note)
                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 100) }}</p>
                    @endif
                    <time class="text-xs text-gray-400 block mt-0.5">
                        {{ $h->created_at->diffForHumans() }}
                    </time>
                </li>
            @empty
                <li class="ml-5 text-xs text-gray-400">No history yet.</li>
            @endforelse
        </ol>
    </div>
</div>

</div>{{-- end grid --}}
</div>{{-- end space-y-6 --}}
@endsection
