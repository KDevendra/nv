@extends('layouts.crm')

@section('title', 'Lead — ' . $lead->name)
@section('page-title', $lead->name)

@section('sidebar-links')
    <nav class="space-y-1 text-sm">
        <a href="{{ route('se.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white/80 hover:text-white">
            ← Back to My Leads
        </a>
    </nav>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Left: Lead info + actions --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Info card --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h2>
                    <div class="text-sm text-gray-500 mt-0.5">{{ $lead->phone }} @if($lead->email) · {{ $lead->email }} @endif</div>
                </div>
                <div class="flex gap-2">
                    <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                    @if($lead->side_state)
                        <span class="stage-pill {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div><span class="font-medium">Division:</span> {{ ucfirst($lead->division) }}</div>
                <div><span class="font-medium">Contact attempts:</span> {{ $lead->contact_attempts }}</div>
                <div><span class="font-medium">Last contacted:</span> {{ $lead->last_contacted_at?->diffForHumans() ?? '—' }}</div>
                <div>
                    <span class="font-medium">Contact SLA:</span>
                    @if($lead->sla_contact_breached)
                        <span class="text-red-600 font-semibold">Breached</span>
                    @elseif($lead->sla_contact_due_at)
                        {{ $lead->sla_contact_due_at->diffForHumans() }}
                    @else — @endif
                </div>
            </div>
            @if($lead->qualification_notes)
                <div class="mt-4 bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->qualification_notes }}</div>
            @endif
        </div>

        {{-- Property snapshot --}}
        @if(!empty($propertySnapshot))
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-3">Property (info-gated)</h3>
            <div class="grid grid-cols-2 gap-3 text-sm text-gray-600">
                <div><span class="font-medium">Title:</span> {{ $propertySnapshot['title'] }}</div>
                <div><span class="font-medium">Type:</span> {{ $propertySnapshot['property_type'] ?? '—' }}</div>
                <div><span class="font-medium">City:</span> {{ $propertySnapshot['city'] ?? '—' }}</div>
                <div><span class="font-medium">Location:</span> {{ $propertySnapshot['location'] ?? '—' }}</div>
                <div><span class="font-medium">Price:</span> ₹{{ number_format($propertySnapshot['price']) }}</div>
                <div><span class="font-medium">BHK:</span> {{ $propertySnapshot['bhk'] ?? '—' }}</div>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        @if($lead->is_active)
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
            <h3 class="font-heading text-zendo-navy">Actions</h3>

            {{-- Log contact --}}
            <details class="border border-gray-200 rounded-xl">
                <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700">Log Contact Attempt</summary>
                <form data-ajax-form data-reload="1" action="{{ route('se.leads.log-contact', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <textarea name="notes" rows="2" placeholder="Call notes (optional)..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold/50 outline-none resize-none"></textarea>
                    <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Log Contact</button>
                </form>
            </details>

            {{-- Qualify --}}
            <details class="border border-gray-200 rounded-xl">
                <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700">Save Qualification Notes</summary>
                <form data-ajax-form data-reload="1" action="{{ route('se.leads.qualify', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <textarea name="qualification_notes" rows="4" placeholder="Budget, requirements, timeline..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold/50 outline-none resize-none">{{ $lead->qualification_notes }}</textarea>
                    @if($lead->stage === 'contacted')
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="advance_stage" value="1"> Confirm interest & advance stage
                    </label>
                    @endif
                    <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Save</button>
                </form>
            </details>

            {{-- Share options --}}
            @if($lead->stage === 'interest_confirmed')
            <details class="border border-gray-200 rounded-xl">
                <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700">Share Property Options</summary>
                <form data-ajax-form action="{{ route('se.leads.share-options', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <input name="property_ids[]" placeholder="Property ID 1" type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <input name="property_ids[]" placeholder="Property ID 2 (optional)" type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <button type="submit" class="bg-zendo-gold text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Share Options</button>
                </form>
            </details>
            @endif

            {{-- Handover --}}
            @if($lead->stage === 'interest_confirmed')
            <details class="border border-green-200 rounded-xl bg-green-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-green-800">↑ Handover to Chief Coordinator</summary>
                <form data-ajax-form action="{{ route('se.leads.handover', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <textarea name="handover_note" rows="4" required minlength="20" placeholder="Handover summary — budget confirmed, requirements, etc. (min 20 chars)..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none resize-none focus:ring-2 focus:ring-green-400/50"></textarea>
                    <button type="submit" class="bg-green-700 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Escalate to CC</button>
                </form>
            </details>
            @endif

            {{-- Side-state controls --}}
            <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
                {{-- Hold --}}
                <button onclick="$('#modal-hold').show()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100">Hold</button>
                {{-- Defer --}}
                <button onclick="$('#modal-defer').show()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100">Defer</button>
                {{-- Lost --}}
                <button onclick="$('#modal-lost').show()" class="text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Mark Lost</button>
            </div>
        </div>
        @endif

        {{-- Resume button when on hold/deferred --}}
        @if($lead->is_on_hold || $lead->is_deferred)
        <form data-ajax-form data-reload="1" action="{{ route('se.leads.resume', $lead) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm font-medium hover:opacity-90">Resume Lead</button>
        </form>
        @endif
    </div>

    {{-- Right: history --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-4">Stage History</h3>
            <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                @forelse($history as $h)
                <li class="ml-4">
                    <span class="absolute -left-1.5 mt-1 w-3 h-3 bg-zendo-gold rounded-full border-2 border-white"></span>
                    <div class="text-xs font-semibold text-gray-700">
                        {{ $h->from_stage_label }} → {{ $h->to_stage_label }}
                        @if($h->is_side_state_change)
                            <span class="text-purple-600">({{ $h->from_side_state ?? 'active' }} → {{ $h->to_side_state ?? 'active' }})</span>
                        @endif
                    </div>
                    @if($h->note)
                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 120) }}</p>
                    @endif
                    <time class="text-xs text-gray-400">{{ $h->created_at->diffForHumans() }}
                        @if($h->changedBy) · {{ $h->changedBy->name }} @endif
                    </time>
                </li>
                @empty
                    <li class="ml-4 text-xs text-gray-400">No history yet.</li>
                @endforelse
            </ol>
        </div>
    </div>
</div>

{{-- Modals --}}
{{-- Hold modal --}}
<div id="modal-hold" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Put Lead on Hold</h3>
        <form data-ajax-form data-reload="1" action="{{ route('se.leads.hold', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" placeholder="Reason (optional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="date" name="hold_until" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-hold').hide()" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Confirm Hold</button>
            </div>
        </form>
    </div>
</div>

{{-- Defer modal --}}
<div id="modal-defer" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Defer Follow-up</h3>
        <form data-ajax-form data-reload="1" action="{{ route('se.leads.defer', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="datetime-local" name="defer_until" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="text" name="reason" placeholder="Reason (optional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-defer').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Defer</button>
            </div>
        </form>
    </div>
</div>

{{-- Lost modal --}}
<div id="modal-lost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-red-700 text-lg mb-4">Mark as Lost</h3>
        <form data-ajax-form action="{{ route('se.leads.lost', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" required placeholder="Reason for loss..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-lost').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg">Mark Lost</button>
            </div>
        </form>
    </div>
</div>
@endsection
