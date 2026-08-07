@extends('layouts.crm')
@section('title', 'Lead — ' . $lead->name)
@section('page-title', $lead->name)

@section('page-actions')
    <a href="{{ route('se.leads.index') }}"
        class="text-sm text-gray-500 hover:text-zendo-navy flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Leads
    </a>
@endsection

@section('content')
<div class="space-y-6">
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

{{-- ── Left col ── --}}
<div class="xl:col-span-2 space-y-5">

    {{-- Lead info card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $lead->phone }}
                    @if($lead->email) · {{ $lead->email }} @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">
                    {{ $lead->stage_label }}
                </span>
                @if($lead->side_state)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">
                        {{ $lead->side_state_label }}
                    </span>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div><span class="font-medium text-gray-700">Division:</span> {{ ucfirst($lead->division) }}</div>
            <div><span class="font-medium text-gray-700">Contact attempts:</span> {{ $lead->contact_attempts }}</div>
            <div><span class="font-medium text-gray-700">Last contacted:</span> {{ $lead->last_contacted_at?->diffForHumans() ?? '—' }}</div>
            <div>
                <span class="font-medium text-gray-700">Contact SLA:</span>
                @if($lead->sla_contact_breached)
                    <span class="text-red-600 font-semibold ml-1">⚠ Breached</span>
                @elseif($lead->sla_contact_due_at)
                    <span class="ml-1">{{ $lead->sla_contact_due_at->diffForHumans() }}</span>
                @else — @endif
            </div>
        </div>
        @if($lead->qualification_notes)
            <div class="mt-4 bg-gray-50 border border-gray-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">
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
            <div><span class="font-medium text-gray-700">BHK:</span> {{ $propertySnapshot['bhk'] ?? '—' }}</div>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    @if($lead->is_active)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4">
        <h4 class="font-heading text-zendo-navy">Actions</h4>

        {{-- Log contact --}}
        <details class="border border-gray-200 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700 hover:bg-gray-50 rounded-xl select-none">
                Log Contact Attempt
            </summary>
            <form data-ajax-form data-reload="1"
                action="{{ route('se.leads.log-contact', $lead) }}" method="POST"
                class="p-4 border-t border-gray-100 space-y-3">
                @csrf
                <textarea name="notes" rows="2" placeholder="Call notes (optional)…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40 resize-none"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                    Log Contact
                </button>
            </form>
        </details>

        {{-- Qualify --}}
        <details class="border border-gray-200 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700 hover:bg-gray-50 rounded-xl select-none">
                Save Qualification Notes
            </summary>
            <form data-ajax-form data-reload="1"
                action="{{ route('se.leads.qualify', $lead) }}" method="POST"
                class="p-4 border-t border-gray-100 space-y-3">
                @csrf
                <textarea name="qualification_notes" rows="4"
                    placeholder="Budget, requirements, timeline…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40 resize-none">{{ $lead->qualification_notes }}</textarea>
                @if($lead->stage === 'contacted')
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="advance_stage" value="1" class="accent-amber-600">
                        Confirm interest &amp; advance to next stage
                    </label>
                @endif
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                    Save Notes
                </button>
            </form>
        </details>

        {{-- Share options --}}
        @if($lead->stage === 'interest_confirmed')
        <details class="border border-gray-200 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-medium text-sm text-gray-700 hover:bg-gray-50 rounded-xl select-none">
                Share Property Options
            </summary>
            <form data-ajax-form action="{{ route('se.leads.share-options', $lead) }}" method="POST"
                class="p-4 border-t border-gray-100 space-y-3">
                @csrf
                <input name="property_ids[]" type="number" placeholder="Property ID 1"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
                <input name="property_ids[]" type="number" placeholder="Property ID 2 (optional)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                    Share Options
                </button>
            </form>
        </details>
        @endif

        {{-- Handover to CC --}}
        @if($lead->stage === 'interest_confirmed')
        <details class="border border-green-200 bg-green-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-green-800 hover:bg-green-100 rounded-xl select-none">
                ↑ Handover to Chief Coordinator
            </summary>
            <form data-ajax-form action="{{ route('se.leads.handover', $lead) }}" method="POST"
                class="p-4 border-t border-green-100 space-y-3">
                @csrf
                <textarea name="handover_note" rows="4" required minlength="20"
                    placeholder="Handover summary — budget confirmed, requirements, timeline… (min 20 chars)"
                    class="w-full border border-green-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400/40 resize-none"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-all shadow">
                    Escalate to CC
                </button>
            </form>
        </details>
        @endif

        {{-- Side-state controls --}}
        <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
            <button onclick="document.getElementById('modal-hold').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                On Hold
            </button>
            <button onclick="document.getElementById('modal-defer').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Defer
            </button>
            <button onclick="document.getElementById('modal-lost').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                Mark Lost
            </button>
        </div>
    </div>
    @endif

    {{-- Resume button --}}
    @if($lead->is_on_hold || $lead->is_deferred)
    <form data-ajax-form data-reload="1" action="{{ route('se.leads.resume', $lead) }}" method="POST">
        @csrf
        <button type="submit"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-all shadow">
            ↺ Resume Lead
        </button>
    </form>
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
                        @if($h->is_side_state_change)
                            <span class="text-purple-600 ml-1">({{ $h->from_side_state ?? 'active' }} → {{ $h->to_side_state ?? 'active' }})</span>
                        @endif
                    </div>
                    @if($h->note)
                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 120) }}</p>
                    @endif
                    <time class="text-xs text-gray-400 block mt-0.5">
                        {{ $h->created_at->diffForHumans() }}
                        @if($h->changedBy) · {{ $h->changedBy->name }} @endif
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

{{-- ── Modals ── --}}
<div id="modal-hold" style="display:none"
    class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Put Lead on Hold</h3>
        <form data-ajax-form data-reload="1" action="{{ route('se.leads.hold', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" placeholder="Reason (optional)"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <input type="date" name="hold_until"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-hold').style.display='none'"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90">Confirm</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-defer" style="display:none"
    class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Defer Follow-up</h3>
        <form data-ajax-form data-reload="1" action="{{ route('se.leads.defer', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="datetime-local" name="defer_until" required
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <input type="text" name="reason" placeholder="Reason (optional)"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-defer').style.display='none'"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90">Defer</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-lost" style="display:none"
    class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-red-700 text-lg mb-4">Mark as Lost</h3>
        <form data-ajax-form action="{{ route('se.leads.lost', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" required placeholder="Reason for loss…"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-lost').style.display='none'"
                    class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">Mark Lost</button>
            </div>
        </form>
    </div>
</div>
@endsection
