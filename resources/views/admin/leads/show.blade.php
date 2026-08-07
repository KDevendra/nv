@extends('layouts.admin')
@section('title', 'Lead Report — ' . $lead->name)

@section('content')
<div class="p-6 space-y-6">

{{-- ── Top bar ── --}}
<div class="flex flex-wrap items-center gap-3">
    <a href="{{ route('admin.leads.index') }}"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-zendo-navy transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Leads
    </a>
    <span class="text-gray-300">|</span>
    <h2 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h2>
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $lead->stage_badge }}">
        {{ $lead->stage_label }}
    </span>
    @if($lead->side_state)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $lead->side_state_badge }}">
            {{ $lead->side_state_label }}
        </span>
    @endif
    @if($lead->needs_division_review)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
            ⚠ Division Review
        </span>
    @endif
    @if($lead->sla_contact_breached || $lead->sla_feasibility_breached)
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
            ⚠ SLA Breach
        </span>
    @endif
</div>

{{-- ── Pipeline progress bar ── --}}
@php
    $stages = \App\Models\Lead::STAGES;
    $currentIdx = \App\Models\Lead::stageIndex($lead->stage);
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Pipeline Progress</h3>
    <div class="flex items-center gap-0 overflow-x-auto pb-1">
        @foreach($stages as $i => $stage)
            @php
                $done    = $i < $currentIdx;
                $current = $i === $currentIdx;
                $pending = $i > $currentIdx;
                $cls     = $done    ? 'bg-zendo-navy text-white'
                         : ($current ? 'bg-zendo-gold text-white ring-2 ring-zendo-gold ring-offset-2'
                         : 'bg-gray-100 text-gray-400');
            @endphp
            <div class="flex items-center flex-shrink-0">
                <div class="flex flex-col items-center gap-1">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $cls }}">
                        @if($done) ✓ @else {{ $i + 1 }} @endif
                    </div>
                    <span class="text-xs {{ $current ? 'text-zendo-navy font-semibold' : 'text-gray-400' }} whitespace-nowrap text-center" style="max-width:72px">
                        {{ ucwords(str_replace('_',' ',$stage)) }}
                    </span>
                </div>
                @if($i < count($stages) - 1)
                    <div class="w-6 h-0.5 mx-0.5 flex-shrink-0 {{ $done ? 'bg-zendo-navy' : 'bg-gray-200' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- ── Main grid ── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
<div class="xl:col-span-2 space-y-5">

{{-- ── 1. Lead Identity ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-zendo-navy text-white text-xs flex items-center justify-center font-bold">1</span>
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Lead Identity</h3>
    </div>
    <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
        <div><span class="text-xs text-gray-400 block mb-0.5">Name</span><span class="font-medium text-gray-800">{{ $lead->name }}</span></div>
        <div><span class="text-xs text-gray-400 block mb-0.5">Phone</span><span class="font-medium text-gray-800">{{ $lead->phone }}</span></div>
        <div><span class="text-xs text-gray-400 block mb-0.5">Email</span><span class="font-medium text-gray-800">{{ $lead->email ?? '—' }}</span></div>
        <div><span class="text-xs text-gray-400 block mb-0.5">Division</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst($lead->division) }}</span>
        </div>
        <div><span class="text-xs text-gray-400 block mb-0.5">Origin</span>
            <span class="font-medium text-gray-800">{{ $lead->origin_table ? ucwords(str_replace('_',' ',$lead->origin_table)).' #'.$lead->origin_id : '—' }}</span>
        </div>
        <div><span class="text-xs text-gray-400 block mb-0.5">Created</span><span class="font-medium text-gray-800">{{ $lead->created_at->format('d M Y, H:i') }}</span></div>
        @if($lead->property)
        <div class="col-span-2 md:col-span-3">
            <span class="text-xs text-gray-400 block mb-0.5">Property</span>
            <span class="font-medium text-gray-800">{{ $lead->property->title }}</span>
            <span class="text-xs text-gray-500 ml-2">· {{ $lead->property->city?->name }} · {{ $lead->property->location?->name }}</span>
        </div>
        @endif
    </div>
</div>

{{-- ── 2. Assignment & SLA ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-zendo-navy text-white text-xs flex items-center justify-center font-bold">2</span>
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Assignment &amp; SLA</h3>
    </div>
    <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Sales Executive</span>
            <span class="font-medium text-gray-800">{{ $lead->assignedSE?->name ?? '—' }}</span>
            @if($lead->se_assigned_at)<span class="text-xs text-gray-400 block">Assigned {{ $lead->se_assigned_at->diffForHumans() }}</span>@endif
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Chief Coordinator</span>
            @if($lead->assignedCC)
                <span class="font-medium text-gray-800">{{ $lead->assignedCC->name }}</span>
                @if($lead->cc_assigned_at)<span class="text-xs text-gray-400 block">Assigned {{ $lead->cc_assigned_at->diffForHumans() }}</span>@endif
            @else
                <span class="text-xs font-semibold text-purple-600">Holding Queue</span>
            @endif
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">CC Load at Assignment</span>
            <span class="font-medium text-gray-800">{{ $lead->cc_load_at_assignment }} / {{ \App\Models\Lead::CC_MAX_ACTIVE_LEADS }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Contact SLA</span>
            @if($lead->sla_contact_breached)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">⚠ Breached</span>
            @elseif($lead->sla_contact_due_at)
                <span class="text-gray-700">{{ $lead->sla_contact_due_at->format('d M Y, H:i') }}</span>
                <span class="text-xs text-gray-400 block">{{ $lead->sla_contact_due_at->diffForHumans() }}</span>
            @else <span class="text-gray-400">—</span> @endif
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Feasibility SLA</span>
            @if($lead->sla_feasibility_breached)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">⚠ Breached</span>
            @elseif($lead->sla_feasibility_due_at)
                <span class="text-gray-700">{{ $lead->sla_feasibility_due_at->format('d M Y, H:i') }}</span>
                <span class="text-xs text-gray-400 block">{{ $lead->sla_feasibility_due_at->diffForHumans() }}</span>
            @else <span class="text-gray-400">—</span> @endif
        </div>
        @if($lead->side_state)
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Side State</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
            @if($lead->hold_started_at)<span class="text-xs text-gray-400 block">Since {{ $lead->hold_started_at->diffForHumans() }}</span>@endif
            @if($lead->lost_at)<span class="text-xs text-gray-400 block">{{ $lead->lost_at->diffForHumans() }} — {{ $lead->lost_reason }}</span>@endif
        </div>
        @endif
    </div>
</div>

{{-- ── 3. SE Panel: new_lead → interest_confirmed ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">3</span>
            <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Panel 1 — Sales Executive</h3>
        </div>
        <span class="text-xs text-gray-400">new_lead → interest_confirmed</span>
    </div>
    <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Contact Attempts</span>
            <span class="text-2xl font-heading font-bold text-blue-700">{{ $lead->contact_attempts }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Last Contacted</span>
            <span class="font-medium text-gray-800">{{ $lead->last_contacted_at?->format('d M Y, H:i') ?? '—' }}</span>
            @if($lead->last_contacted_at)<span class="text-xs text-gray-400 block">{{ $lead->last_contacted_at->diffForHumans() }}</span>@endif
        </div>
        <div>
            <span class="text-xs text-gray-400 block mb-0.5">Options Shared</span>
            @if($lead->options_shared_at)
                <span class="font-medium text-gray-800">{{ count($lead->options_shared_property_ids ?? []) }} properties</span>
                <span class="text-xs text-gray-400 block">{{ $lead->options_shared_at->diffForHumans() }}</span>
            @else <span class="text-gray-400">—</span> @endif
        </div>
        @if($lead->qualification_notes)
        <div class="col-span-2 md:col-span-3">
            <span class="text-xs text-gray-400 block mb-1">Qualification Notes</span>
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->qualification_notes }}</div>
        </div>
        @endif
        @if($lead->handover_note)
        <div class="col-span-2 md:col-span-3">
            <span class="text-xs text-gray-400 block mb-1">Handover Note to CC</span>
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-900 whitespace-pre-line">{{ $lead->handover_note }}</div>
            @if($lead->handover_completed_at)<span class="text-xs text-gray-400">Completed {{ $lead->handover_completed_at->diffForHumans() }}</span>@endif
        </div>
        @endif
    </div>
</div>

{{-- ── 4. CC Panel: interest_confirmed → escalated_to_cc ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-purple-600 text-white text-xs flex items-center justify-center font-bold">4</span>
            <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Panel 2 — Chief Coordinator</h3>
        </div>
        <span class="text-xs text-gray-400">interest_confirmed → escalated_to_cc</span>
    </div>
    <div class="p-5 space-y-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Feasibility Requested</span>
                @if($lead->feasibility_requested_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Yes</span>
                    <span class="text-xs text-gray-400 block">{{ $lead->feasibility_requested_at->diffForHumans() }}</span>
                @else <span class="text-gray-400">—</span> @endif
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Assigned to SH</span>
                <span class="font-medium text-gray-800">{{ $lead->feasibilitySH?->name ?? '—' }}</span>
                @if($lead->feasibility_assigned_at)<span class="text-xs text-gray-400 block">{{ $lead->feasibility_assigned_at->diffForHumans() }}</span>@endif
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Feasibility Status</span>
                @if($lead->feasibility_response_received_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Received</span>
                @elseif($lead->feasibility_requested_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">⏳ Pending</span>
                @else <span class="text-gray-400">—</span> @endif
            </div>
        </div>
        @if($lead->feasibility_notes)
        <div>
            <span class="text-xs text-gray-400 block mb-1">Feasibility Request Notes</span>
            <div class="bg-purple-50 border border-purple-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->feasibility_notes }}</div>
        </div>
        @endif
    </div>
</div>

{{-- ── 5. SH Panel: Feasibility Response ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">5</span>
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Panel 3 — Supply Head Response</h3>
    </div>
    <div class="p-5 space-y-4">
        @if($lead->feasibility_is_feasible !== null)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Feasibility Result</span>
                @if($lead->feasibility_is_feasible)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Feasible</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">✗ Not Feasible</span>
                @endif
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Response Received</span>
                <span class="font-medium text-gray-800">{{ $lead->feasibility_response_received_at?->format('d M Y, H:i') ?? '—' }}</span>
                @if($lead->feasibility_response_received_at)<span class="text-xs text-gray-400 block">{{ $lead->feasibility_response_received_at->diffForHumans() }}</span>@endif
            </div>
        </div>
        @if($lead->feasibility_response_notes)
        <div>
            <span class="text-xs text-gray-400 block mb-1">SH Response Notes</span>
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->feasibility_response_notes }}</div>
        </div>
        @endif
        @else
        <p class="text-sm text-gray-400 italic">No feasibility response yet.</p>
        @endif
    </div>
</div>

{{-- ── 6. Site Visit Panel ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-amber-600 text-white text-xs flex items-center justify-center font-bold">6</span>
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Site Visit</h3>
    </div>
    <div class="p-5 space-y-4">
        @if($lead->site_visit_token)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Token Generated</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Yes</span>
                @if($lead->site_visit_token_generated_at)<span class="text-xs text-gray-400 block">{{ $lead->site_visit_token_generated_at->diffForHumans() }}</span>@endif
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Visit Completed</span>
                @if($lead->site_visit_completed_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Yes</span>
                    <span class="text-xs text-gray-400 block">{{ $lead->site_visit_completed_at->diffForHumans() }}</span>
                @else
                    <span class="text-xs text-gray-500">Pending</span>
                @endif
            </div>
            <div class="col-span-2 md:col-span-3">
                <span class="text-xs text-gray-400 block mb-1">Token Link</span>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 text-xs font-mono text-gray-700 break-all select-all">
                    {{ route('leads.site-visit.public', $lead->site_visit_token) }}
                </div>
            </div>
        </div>
        @if($lead->site_visit_feedback)
        <div>
            <span class="text-xs text-gray-400 block mb-1">Client Feedback</span>
            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->site_visit_feedback }}</div>
        </div>
        @endif
        @else
        <p class="text-sm text-gray-400 italic">No site visit token generated yet.</p>
        @endif
    </div>
</div>

{{-- ── 7. Deal Closure Panel ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-green-600 text-white text-xs flex items-center justify-center font-bold">7</span>
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Deal Closure</h3>
    </div>
    <div class="p-5 space-y-4">
        @if($lead->deal_closed_at)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Status</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Deal Closed</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Closed At</span>
                <span class="font-medium text-gray-800">{{ $lead->deal_closed_at->format('d M Y, H:i') }}</span>
                <span class="text-xs text-gray-400 block">{{ $lead->deal_closed_at->diffForHumans() }}</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block mb-0.5">Deal Value</span>
                <span class="text-xl font-heading font-bold text-green-700">{{ $lead->deal_value ? '₹ '.number_format($lead->deal_value) : '—' }}</span>
            </div>
        </div>
        @if($lead->deal_closure_notes)
        <div>
            <span class="text-xs text-gray-400 block mb-1">Closure Notes</span>
            <div class="bg-green-50 border border-green-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line">{{ $lead->deal_closure_notes }}</div>
        </div>
        @endif
        @else
        <p class="text-sm text-gray-400 italic">Deal not closed yet.</p>
        @endif
    </div>
</div>

</div>
{{-- End left column --}}

{{-- ── Right column: Stage History + Admin Actions ── --}}
<div class="space-y-5">

{{-- ── Full Stage History Timeline ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-100 px-5 py-3">
        <h3 class="font-semibold text-zendo-navy text-sm uppercase tracking-wide">Stage History</h3>
    </div>
    <div class="p-5">
        @if($history->count())
        <ol class="relative border-l-2 border-gray-200 space-y-5 ml-2">
            @foreach($history as $h)
            <li class="ml-5">
                <span class="absolute -left-2 mt-1.5 w-4 h-4 bg-zendo-gold rounded-full border-2 border-white shadow"></span>
                <div class="mb-1">
                    <div class="text-xs font-bold text-gray-800">{{ $h->from_stage_label }} → {{ $h->to_stage_label }}</div>
                    @if($h->is_side_state_change)
                        <div class="text-xs text-purple-600 mt-0.5">({{ $h->from_side_state ?? 'active' }} → {{ $h->to_side_state ?? 'active' }})</div>
                    @endif
                </div>
                @if($h->note)
                <p class="text-xs text-gray-600 bg-gray-50 rounded p-2 border border-gray-100 mb-1 whitespace-pre-line">{{ Str::limit($h->note, 200) }}</p>
                @endif
                <time class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $h->created_at->format('d M Y, H:i') }}
                    @if($h->changedBy)
                        <span class="text-gray-300">·</span>
                        <span>{{ $h->changedBy->name }}</span>
                    @endif
                </time>
            </li>
            @endforeach
        </ol>
        @else
        <p class="text-sm text-gray-400 italic text-center py-6">No stage history recorded.</p>
        @endif
    </div>
</div>

{{-- ── Admin Actions ── --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-amber-50 border-b border-amber-100 px-5 py-3">
        <h3 class="font-semibold text-amber-900 text-sm uppercase tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            Admin Actions
        </h3>
    </div>
    <div class="p-5 space-y-3">

        {{-- Assign SE --}}
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="px-4 py-2.5 cursor-pointer font-medium text-sm bg-gray-50 hover:bg-gray-100 transition">
                Assign / Change Sales Executive
            </summary>
            <form data-ajax-form data-reload="1" action="{{ route('admin.leads.assign-se', $lead) }}" method="POST" class="p-4 space-y-3 bg-white">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Sales Executive</label>
                    <select name="se_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">— Select SE —</option>
                        @foreach($salesExecs as $u)
                            <option value="{{ $u->id }}" {{ $lead->assigned_se_id==$u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->division) }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Assign SE
                </button>
            </form>
        </details>

        {{-- Assign CC --}}
        <details class="border border-gray-200 rounded-lg overflow-hidden">
            <summary class="px-4 py-2.5 cursor-pointer font-medium text-sm bg-gray-50 hover:bg-gray-100 transition">
                Assign / Change Chief Coordinator
            </summary>
            <form data-ajax-form data-reload="1" action="{{ route('admin.leads.assign-cc', $lead) }}" method="POST" class="p-4 space-y-3 bg-white">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Chief Coordinator</label>
                    <select name="cc_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">— Select CC —</option>
                        @foreach($chiefCoords as $u)
                            <option value="{{ $u->id }}" {{ $lead->assigned_cc_id==$u->id ? 'selected' : '' }}>{{ $u->name }} ({{ ucfirst($u->division) }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Assign CC
                </button>
            </form>
        </details>

        {{-- Override Stage --}}
        <details class="border border-amber-300 rounded-lg overflow-hidden bg-amber-50">
            <summary class="px-4 py-2.5 cursor-pointer font-semibold text-sm text-amber-900 hover:bg-amber-100 transition">
                ⚙ Override Pipeline Stage
            </summary>
            <form data-ajax-form data-reload="1" action="{{ route('admin.leads.override-stage', $lead) }}" method="POST" class="p-4 space-y-3 bg-white">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 block mb-1">New Stage</label>
                    <select name="stage" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        @foreach(\App\Models\Lead::STAGES as $s)
                            <option value="{{ $s }}" {{ $lead->stage===$s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Reason for Override</label>
                    <input type="text" name="reason" required placeholder="e.g., Skip qualification due to existing relationship" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                </div>
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Override Stage
                </button>
            </form>
        </details>

        {{-- Resolve Division Conflict --}}
        @if($lead->needs_division_review)
        <details class="border border-orange-300 rounded-lg overflow-hidden bg-orange-50" open>
            <summary class="px-4 py-2.5 cursor-pointer font-semibold text-sm text-orange-900 hover:bg-orange-100 transition">
                ⚠ Resolve Division Conflict
            </summary>
            <form data-ajax-form data-reload="1" action="{{ route('admin.leads.resolve-division', $lead) }}" method="POST" class="p-4 space-y-3 bg-white">
                @csrf
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Correct Division</label>
                    <select name="division" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        <option value="">— Select Division —</option>
                        @foreach(['warehousing','residential','commercial'] as $d)
                            <option value="{{ $d }}" {{ $lead->division===$d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Confirm Division
                </button>
            </form>
        </details>
        @endif

        {{-- Side-state quick actions --}}
        <div class="border-t border-gray-200 pt-3 mt-3">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Quick Actions</p>
            <div class="flex flex-wrap gap-2">
                @if(!$lead->is_on_hold && !$lead->is_lost)
                    <button onclick="document.getElementById('admin-modal-hold').classList.remove('hidden')"
                            class="flex-1 text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100 transition font-medium">
                        Hold
                    </button>
                @endif
                @if($lead->is_on_hold || $lead->is_deferred)
                    <form data-ajax-form data-reload="1" action="{{ route('admin.leads.resume', $lead) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full text-xs px-3 py-1.5 rounded-lg border border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100 transition font-medium">
                            Resume
                        </button>
                    </form>
                @endif
                @if(!$lead->is_lost)
                    <button onclick="document.getElementById('admin-modal-lost').classList.remove('hidden')"
                            class="flex-1 text-xs px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition font-medium">
                        Mark Lost
                    </button>
                @endif
                <form data-ajax-form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                      onsubmit="return confirm('Soft-delete this lead? This can be restored later.')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 transition font-medium">
                        Delete
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

</div>
{{-- End right column --}}

</div>
{{-- End main grid --}}

</div>
{{-- End container --}}

{{-- ──────────────────────────────────────────────────────────────────── --}}
{{-- Modals --}}
{{-- ──────────────────────────────────────────────────────────────────── --}}

{{-- Admin Hold Modal --}}
<div id="admin-modal-hold" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-heading text-zendo-navy text-lg font-semibold">Put Lead on Hold</h3>
            <button onclick="document.getElementById('admin-modal-hold').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form data-ajax-form data-reload="1" action="{{ route('admin.leads.hold', $lead) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-xs text-gray-500 block mb-1">Reason (optional)</label>
                <input type="text" name="reason" placeholder="e.g., Waiting for client budget approval"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-1">Hold Until (optional)</label>
                <input type="date" name="hold_until"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="document.getElementById('admin-modal-hold').classList.add('hidden')"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    Cancel
                </button>
                <button type="submit" class="bg-zendo-navy hover:bg-zendo-navy/90 text-white text-sm px-5 py-2 rounded-lg font-medium transition">
                    Confirm Hold
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Admin Lost Modal --}}
<div id="admin-modal-lost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <div class="bg-red-50 border-b border-red-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-heading text-red-900 text-lg font-semibold">Mark Lead as Lost</h3>
            <button onclick="document.getElementById('admin-modal-lost').classList.add('hidden')"
                    class="text-red-400 hover:text-red-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form data-ajax-form action="{{ route('admin.leads.lost', $lead) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-xs text-gray-500 block mb-1">Reason for Loss <span class="text-red-500">*</span></label>
                <input type="text" name="reason" required placeholder="e.g., Client went with competitor"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 focus:border-transparent">
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="document.getElementById('admin-modal-lost').classList.add('hidden')"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm px-5 py-2 rounded-lg font-medium transition">
                    Mark as Lost
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ──────────────────────────────────────────────────────────────────── --}}
{{-- Scripts --}}
{{-- ──────────────────────────────────────────────────────────────────── --}}

@push('scripts')
<script>
// Ensure jQuery is loaded for AJAX
if (typeof $ === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.7.1.min.js';
    script.integrity = 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=';
    script.crossOrigin = 'anonymous';
    document.head.appendChild(script);
    script.onload = () => initAdminLeadAjax();
} else {
    initAdminLeadAjax();
}

function initAdminLeadAjax() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle all AJAX forms
    $(document).on('submit', 'form[data-ajax-form]', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('[type=submit]');
        const shouldReload = $form.data('reload');

        $btn.prop('disabled', true).css('opacity', '0.6');

        $.ajax({
            type: $form.attr('method') || 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            success(response) {
                if (response.success) {
                    showAdminToast(response.message || 'Action completed successfully.', 'success');
                    if (shouldReload) {
                        setTimeout(() => location.reload(), 800);
                    } else {
                        $form[0].reset();
                    }
                } else {
                    showAdminToast(response.message || 'Action failed.', 'error');
                }
            },
            error(xhr) {
                const message = xhr.responseJSON?.message || 'Request failed. Please try again.';
                showAdminToast(message, 'error');
            },
            complete() {
                $btn.prop('disabled', false).css('opacity', '1');
            }
        });
    });
}

function showAdminToast(message, type) {
    const bgClass = type === 'success' ? 'bg-green-600' : 'bg-red-600';
    const icon = type === 'success'
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';

    const $toast = $(`
        <div class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl shadow-2xl text-sm font-medium text-white ${bgClass} flex items-center gap-3 animate-slide-in">
            ${icon}
            <span>${message}</span>
        </div>
    `);

    $('body').append($toast);
    setTimeout(() => {
        $toast.fadeOut(400, () => $toast.remove());
    }, 4000);
}
</script>
@endpush

@endsection
