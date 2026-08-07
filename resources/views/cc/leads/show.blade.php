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
        <h4 class="font-heading text-zendo-navy">Actions</h4>

        {{-- Request feasibility --}}
        @if($lead->stage === 'escalated_to_cc')
        <details class="border border-yellow-200 bg-yellow-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-yellow-800 hover:bg-yellow-100 rounded-xl select-none">Request Feasibility from SH</summary>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.request-feasibility', $lead) }}" method="POST"
                class="p-4 border-t border-yellow-100 space-y-3">
                @csrf
                <select name="feasibility_sh_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zendo-gold/40">
                    <option value="">— Select Supply Head —</option>
                    @foreach($supplyHeads as $sh)
                        <option value="{{ $sh->id }}">{{ $sh->name }}</option>
                    @endforeach
                </select>
                <textarea name="notes" rows="2" placeholder="Notes for SH (optional)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-zendo-gold/40"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-semibold rounded-lg hover:bg-yellow-700 transition-all shadow">
                    Send Request
                </button>
            </form>
        </details>
        @endif

        {{-- Generate site-visit link --}}
        @if(in_array($lead->stage, ['options_shared','site_visit_scheduled']))
        <details class="border border-cyan-200 bg-cyan-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-cyan-800 hover:bg-cyan-100 rounded-xl select-none">Generate Site-Visit Link</summary>
            <form id="form-sitevisit" data-ajax-form action="{{ route('cc.leads.generate-site-visit-link', $lead) }}" method="POST"
                class="p-4 border-t border-cyan-100 space-y-3">
                @csrf
                <p class="text-xs text-cyan-700">Single-use · expires after 24 h · invalidated on first open.</p>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-semibold rounded-lg hover:bg-cyan-700 transition-all shadow">
                    Generate Link
                </button>
            </form>
            <div id="sitevisit-url" class="hidden px-4 pb-4 text-xs text-cyan-900 break-all"></div>
        </details>
        @endif

        {{-- Site-visit feedback --}}
        @if($lead->stage === 'site_visit_scheduled')
        <details class="border border-teal-200 bg-teal-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-teal-800 hover:bg-teal-100 rounded-xl select-none">Log Site-Visit Feedback</summary>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.site-visit-feedback', $lead) }}" method="POST"
                class="p-4 border-t border-teal-100 space-y-3">
                @csrf
                <textarea name="feedback" required rows="4" placeholder="Client reaction, preferences observed…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-teal-400/40"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-teal-700 text-white text-sm font-semibold rounded-lg hover:bg-teal-800 transition-all shadow">
                    Save Feedback
                </button>
            </form>
        </details>
        @endif

        {{-- Negotiate --}}
        @if(in_array($lead->stage, ['site_visit_done','negotiation']))
        <details class="border border-amber-200 bg-amber-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-amber-800 hover:bg-amber-100 rounded-xl select-none">Negotiation Notes</summary>
            <form data-ajax-form data-reload="1" action="{{ route('cc.leads.negotiate', $lead) }}" method="POST"
                class="p-4 border-t border-amber-100 space-y-3">
                @csrf
                <textarea name="negotiation_notes" rows="4" placeholder="Price counter-offers, conditions…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-amber-400/40">{{ $lead->negotiation_notes }}</textarea>
                @if($lead->stage === 'site_visit_done')
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="advance_stage" value="1" class="accent-amber-600">
                        Move to Negotiation stage
                    </label>
                @endif
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-all shadow">
                    Save Notes
                </button>
            </form>
        </details>
        @endif

        {{-- Close deal --}}
        @if($lead->stage === 'negotiation')
        <details class="border border-green-200 bg-green-50 rounded-xl">
            <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-green-800 hover:bg-green-100 rounded-xl select-none">✓ Close Deal</summary>
            <form data-ajax-form action="{{ route('cc.leads.close-deal', $lead) }}" method="POST"
                class="p-4 border-t border-green-100 space-y-3">
                @csrf
                <input type="number" name="deal_value" required min="1" step="1" placeholder="Deal value (₹)"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400/40">
                <textarea name="deal_notes" rows="2" placeholder="Final notes (optional)…"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-green-400/40"></textarea>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white text-sm font-semibold rounded-lg hover:bg-green-800 transition-all shadow">
                    Close Deal
                </button>
            </form>
        </details>
        @endif

        {{-- Side-state controls --}}
        <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
            <button onclick="document.getElementById('modal-hold').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">On Hold</button>
            <button onclick="document.getElementById('modal-defer').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Defer</button>
            <button onclick="document.getElementById('modal-lost').style.display='flex'"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors">Mark Lost</button>
        </div>
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
