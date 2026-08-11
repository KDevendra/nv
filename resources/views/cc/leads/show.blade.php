@extends('layouts.crm')
@section('title', 'Lead — ' . $lead->name)
@section('page-title', $lead->name)

@section('page-actions')
    <a href="{{ route('cc.leads.index') }}"
        class="text-sm text-gray-500 hover:text-zendo-navy flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Pipeline
    </a>
@endsection

@section('content')
<div class="space-y-6">
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

{{-- ── Left col ── --}}
<div class="xl:col-span-2 space-y-5">

    {{-- Lead info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->phone }} @if($lead->email) · {{ $lead->email }} @endif</p>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                @if($lead->side_state)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div><span class="font-medium text-gray-700">Division:</span> {{ ucfirst($lead->division) }}</div>
            <div><span class="font-medium text-gray-700">SE:</span> {{ $lead->assignedSE?->name ?? '—' }}</div>
            <div><span class="font-medium text-gray-700">CC Assigned:</span> {{ $lead->cc_assigned_at?->diffForHumans() ?? '—' }}</div>
            <div>
                <span class="font-medium text-gray-700">Feasibility:</span>
                @if($lead->feasibility_status)
                    <span class="ml-1 {{ match($lead->feasibility_status) { 'feasible'=>'text-green-600','not_feasible'=>'text-red-600',default=>'text-yellow-600' } }} font-semibold">
                        {{ ucfirst(str_replace('_',' ',$lead->feasibility_status)) }}
                    </span>
                @else <span class="text-gray-400">—</span> @endif
            </div>
        </div>
        @if($lead->handover_note)
            <div class="mt-4 bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-900">
                <div class="text-xs font-semibold text-indigo-500 mb-1 uppercase tracking-wide">SE Handover Note</div>
                {{ $lead->handover_note }}
            </div>
        @endif
        @if($lead->feasibility_notes)
            <div class="mt-3 bg-yellow-50 border border-yellow-100 rounded-lg p-3 text-sm text-yellow-900">
                <div class="text-xs font-semibold text-yellow-600 mb-1 uppercase tracking-wide">SH Feasibility Notes</div>
                {{ $lead->feasibility_notes }}
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
        <h4 class="font-heading text-zendo-navy">Pipeline Action</h4>

        {{-- Stage 6: Escalated to CC --}}
        @if($lead->stage === 'escalated_to_cc')
        <div class="border border-yellow-200 bg-yellow-50/50 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold text-sm text-yellow-900">Stage 6: Request Feasibility from Supply Head</h5>
                <span class="text-xs text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded-full font-medium">SH Relay Gate</span>
            </div>
            <p class="text-xs text-gray-600">Select Supply Head for this division to check property feasibility and owner confirmation (24h SLA).</p>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.request-feasibility', $lead) }}" method="POST"
                class="space-y-3">
                @csrf
                <select name="feasibility_sh_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40 bg-white">
                    <option value="">— Select Supply Head —</option>
                    @foreach($supplyHeads as $sh)
                        <option value="{{ $sh->id }}" {{ $lead->feasibility_sh_id == $sh->id ? 'selected' : '' }}>{{ $sh->name }}</option>
                    @endforeach
                </select>
                <textarea name="notes" rows="2" placeholder="Notes for Supply Head (optional)…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-zendo-gold/40 bg-white"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-semibold rounded-lg hover:bg-yellow-700 transition-all shadow">
                    Send Feasibility Check Request
                </button>
            </form>
        </div>

        {{-- Stage 7: Inventory Check Done --}}
        @elseif($lead->stage === 'inventory_check_done')
        <div class="border border-cyan-200 bg-cyan-50/50 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold text-sm text-cyan-900">Stage 7: Feasibility &amp; Site Visit Schedule</h5>
                <span class="text-xs text-cyan-800 bg-cyan-100 px-2 py-0.5 rounded-full font-medium">Site Visit Prep</span>
            </div>
            @if(!$lead->feasibility_responded_at)
                <div class="bg-amber-100/80 border border-amber-200 rounded-lg p-2.5 text-xs text-amber-900 flex items-center justify-between">
                    <span>Awaiting Feasibility Check Response from Supply Head</span>
                    <span class="font-mono text-amber-800">SLA: 24 Hours</span>
                </div>
            @else
                <div class="bg-green-100/80 border border-green-200 rounded-lg p-2.5 text-xs text-green-900 flex items-center justify-between">
                    <span>Supply Head Feasibility Check Completed</span>
                </div>
            @endif
            <p class="text-xs text-gray-600">Schedule site visit and send single-use 24-hour SMS link to visitor (address hidden until visit day).</p>
            <form id="form-sitevisit" data-ajax-form data-reload="1" action="{{ route('cc.leads.generate-site-visit-link', $lead) }}" method="POST"
                class="space-y-3">
                @csrf
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-semibold rounded-lg hover:bg-cyan-700 transition-all shadow">
                    Schedule Site Visit &amp; Generate Link
                </button>
            </form>
            <div id="sitevisit-url" class="hidden text-xs text-cyan-900 break-all bg-white p-2.5 rounded-lg border border-cyan-200"></div>
        </div>

        {{-- Stage 8: Site Visit Scheduled --}}
        @elseif($lead->stage === 'site_visit_scheduled')
        <div class="border border-teal-200 bg-teal-50/50 rounded-xl p-4 space-y-4">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold text-sm text-teal-900">Stage 8: Site Visit &amp; Structured Feedback</h5>
                <span class="text-xs text-teal-800 bg-teal-100 px-2 py-0.5 rounded-full font-medium">Visit Scheduled</span>
            </div>
            <div class="bg-white border border-teal-100 rounded-lg p-3 text-xs text-gray-700 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Expiring SMS Visit Link:</span>
                    <form id="form-sitevisit" data-ajax-form action="{{ route('cc.leads.generate-site-visit-link', $lead) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-teal-700 hover:underline font-semibold text-xs">Resend Link Token</button>
                    </form>
                </div>
                <div id="sitevisit-url" class="text-xs text-teal-900 break-all">
                    @if($lead->visit_link_token)
                        Link URL: <a href="{{ route('leads.visit_link', ['token' => $lead->visit_link_token]) }}" target="_blank" class="underline font-mono text-teal-700">{{ route('leads.visit_link', ['token' => $lead->visit_link_token]) }}</a>
                    @else
                        No active token generated yet.
                    @endif
                </div>
            </div>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.site-visit-feedback', $lead) }}" method="POST"
                class="space-y-3">
                @csrf
                <input type="date" name="site_visit_date" value="{{ $lead->site_visit_date?->format('Y-m-d') ?: date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400/40 bg-white">
                <textarea name="feedback" required rows="4" placeholder="Log structured feedback — client reactions, preferences, decisions…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-teal-400/40 bg-white">{{ $lead->site_visit_feedback }}</textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-teal-700 text-white text-sm font-semibold rounded-lg hover:bg-teal-800 transition-all shadow">
                    Save Visit Feedback &amp; Mark Completed
                </button>
            </form>
        </div>

        {{-- Stage 9: Site Visit Completed --}}
        @elseif($lead->stage === 'site_visit_completed')
        <div class="border border-amber-200 bg-amber-50/50 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold text-sm text-amber-900">Stage 9: Initiate Price &amp; Term Negotiation</h5>
                <span class="text-xs text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full font-medium">Sole Negotiator</span>
            </div>
            <p class="text-xs text-gray-600">Chief Coordinator acts as sole negotiator (lead and owner never interact directly).</p>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.negotiate', $lead) }}" method="POST"
                class="space-y-3">
                @csrf
                <textarea name="negotiation_notes" rows="4" placeholder="Price counter-offers, conditions, terms discussed…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-amber-400/40 bg-white">{{ $lead->negotiation_notes }}</textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-all shadow">
                    Save Notes &amp; Move to Negotiation Stage
                </button>
            </form>
        </div>

        {{-- Stage 10: Negotiation --}}
        @elseif($lead->stage === 'negotiation')
        <div class="border border-green-200 bg-green-50/50 rounded-xl p-4 space-y-4">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold text-sm text-green-900">Stage 10: Finalize Off-Market Deal</h5>
                <span class="text-xs text-green-800 bg-green-100 px-2 py-0.5 rounded-full font-medium">Deal Closure Gate</span>
            </div>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.close-deal', $lead) }}" method="POST"
                class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Commission Amount (₹)</label>
                    <input type="number" name="commission_amount" required min="1" step="0.01" placeholder="Commission amount (₹)"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400/40 bg-white">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-all shadow">
                    ✓ Finalize &amp; Close Deal
                </button>
            </form>
        </div>

        {{-- Stage 11: Deal Closed --}}
        @elseif($lead->stage === 'deal_closed')
        <div class="border border-green-300 bg-green-100/60 rounded-xl p-4 text-xs text-green-950 space-y-2">
            <div class="font-bold flex items-center gap-2 text-sm text-green-900">
                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Deal Closed Off-Market
            </div>
            <div class="grid grid-cols-2 gap-2 text-gray-700 pt-1">
                <div><span class="font-semibold text-gray-800">Closed At:</span> {{ $lead->deal_closed_at?->format('d M Y, H:i') ?? '—' }}</div>
                <div><span class="font-semibold text-gray-800">Commission:</span> ₹{{ number_format($lead->commission_amount ?? 0, 2) }}</div>
                <div><span class="font-semibold text-gray-800">Owner Notified:</span> {{ $lead->owner_notified_at?->format('d M Y') ?? '—' }}</div>
                <div><span class="font-semibold text-gray-800">6-Mo Followup:</span> {{ $lead->reminder_6mo_at?->format('d M Y') ?? '—' }}</div>
            </div>
        </div>
        @endif

        {{-- Side-state controls --}}
        @if($lead->stage !== 'deal_closed')
        <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
            <button onclick="document.getElementById('modal-hold').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">On Hold</button>
            <button onclick="document.getElementById('modal-defer').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Defer</button>
            <button onclick="document.getElementById('modal-lost').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors">Mark Lost</button>
        </div>
        @endif
    </div>
    @endif

    @if($lead->is_on_hold || $lead->is_deferred)
    <form data-ajax-form data-reload="1" action="{{ route('cc.leads.resume', $lead) }}" method="POST">
        @csrf
        <button type="submit"
            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-all shadow">
            ↺ Resume Lead
        </button>
    </form>
    @endif
</div>

{{-- ── Right col: Stage History ── --}}
<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-heading text-zendo-navy mb-4">Stage History</h4>
        <ol class="relative border-l-2 border-gray-100 space-y-4 ml-3">
            @forelse($history as $h)
                <li class="ml-5">
                    <span class="absolute -left-2 mt-1 w-4 h-4 bg-zendo-gold rounded-full border-2 border-white shadow"></span>
                    <div class="text-xs font-semibold text-gray-700">{{ $h->from_stage_label }} → {{ $h->to_stage_label }}</div>
                    @if($h->note)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 120) }}</p>@endif
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
</div>
</div>

{{-- Modals --}}
<div id="modal-hold" style="display:none" class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Put Lead on Hold</h3>
        <form data-ajax-form data-reload="1" action="{{ route('cc.leads.hold', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" placeholder="Reason (optional)" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <input type="date" name="hold_until" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-hold').style.display='none'" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90">Confirm</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-defer" style="display:none" class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Defer Follow-up</h3>
        <form data-ajax-form data-reload="1" action="{{ route('cc.leads.defer', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="datetime-local" name="defer_until" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <input type="text" name="reason" placeholder="Reason" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-defer').style.display='none'" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90">Defer</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-lost" style="display:none" class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl mx-4">
        <h3 class="font-heading text-red-700 text-lg mb-4">Mark as Lost</h3>
        <form data-ajax-form action="{{ route('cc.leads.lost', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" required placeholder="Reason…" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-lost').style.display='none'" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700">Mark Lost</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).on('submit','#form-sitevisit',function(e){
    e.preventDefault();
    const $btn=$(this).find('[type=submit]'); $btn.prop('disabled',true);
    $.post($(this).attr('action'),$(this).serialize(),function(res){
        if(res.success){
            $('#sitevisit-url').removeClass('hidden').html('<strong>Link:</strong> <a href="'+res.url+'" target="_blank" class="underline break-all">'+res.url+'</a><br><span class="text-gray-500">Expires: '+res.expires_at+'</span>');
            showToast('Site-visit link generated.','success');
        } else { showToast(res.message,'error'); }
    }).fail(function(xhr){ showToast(xhr.responseJSON?.message||'Failed.','error'); })
    .always(function(){ $btn.prop('disabled',false); });
});
</script>
@endpush
@endsection
