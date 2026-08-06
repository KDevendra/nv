@extends('layouts.crm')

@section('title', 'Lead — ' . $lead->name)
@section('page-title', $lead->name)

@section('sidebar-links')
    <nav class="text-sm">
        <a href="{{ route('cc.leads.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white/80 hover:text-white">
            ← Back to Pipeline
        </a>
    </nav>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Left --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Info card --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-heading text-zendo-navy">{{ $lead->name }}</h2>
                    <div class="text-sm text-gray-500 mt-0.5">{{ $lead->phone }} @if($lead->email) · {{ $lead->email }} @endif</div>
                </div>
                <div class="flex gap-2 flex-wrap justify-end">
                    <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
                    @if($lead->side_state)
                        <span class="stage-pill {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div><span class="font-medium">Division:</span> {{ ucfirst($lead->division) }}</div>
                <div><span class="font-medium">SE:</span> {{ $lead->assignedSE?->name ?? '—' }}</div>
                <div><span class="font-medium">CC Assigned:</span> {{ $lead->cc_assigned_at?->diffForHumans() ?? '—' }}</div>
                <div><span class="font-medium">Feasibility:</span>
                    <span class="{{ match($lead->feasibility_status) { 'feasible'=>'text-green-600','not_feasible'=>'text-red-600',default=>'text-yellow-600'} }}">
                        {{ $lead->feasibility_status ? ucfirst(str_replace('_',' ',$lead->feasibility_status)) : '—' }}
                    </span>
                </div>
            </div>
            @if($lead->handover_note)
                <div class="mt-4 bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-800">
                    <div class="text-xs font-semibold text-indigo-500 mb-1">SE Handover Note</div>
                    {{ $lead->handover_note }}
                </div>
            @endif
            @if($lead->feasibility_notes)
                <div class="mt-3 bg-yellow-50 border border-yellow-100 rounded-lg p-3 text-sm text-yellow-800">
                    <div class="text-xs font-semibold text-yellow-600 mb-1">SH Feasibility Notes</div>
                    {{ $lead->feasibility_notes }}
                </div>
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

            {{-- Request feasibility --}}
            @if($lead->stage === 'escalated_to_cc')
            <details class="border border-yellow-200 rounded-xl bg-yellow-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-yellow-800">Request Feasibility from SH</summary>
                <form data-ajax-form data-reload="1" action="{{ route('cc.leads.request-feasibility', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <select name="feasibility_sh_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select Supply Head —</option>
                        @foreach($supplyHeads as $sh)
                            <option value="{{ $sh->id }}">{{ $sh->name }}</option>
                        @endforeach
                    </select>
                    <textarea name="notes" rows="2" placeholder="Notes for SH (optional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                    <button type="submit" class="bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Send Feasibility Request</button>
                </form>
            </details>
            @endif

            {{-- Generate site-visit link --}}
            @if(in_array($lead->stage, ['options_shared', 'site_visit_scheduled']))
            <details class="border border-cyan-200 rounded-xl bg-cyan-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-cyan-800">Generate Site-Visit Link</summary>
                <form id="form-sitevisit" data-ajax-form action="{{ route('cc.leads.generate-site-visit-link', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <p class="text-xs text-cyan-700">A single-use 24-hour link will be generated. It expires or becomes invalid after first open.</p>
                    <button type="submit" class="bg-cyan-600 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Generate Link</button>
                </form>
                <div id="sitevisit-url" class="hidden px-4 pb-4 text-xs text-cyan-900 break-all"></div>
            </details>
            @endif

            {{-- Site-visit feedback --}}
            @if($lead->stage === 'site_visit_scheduled')
            <details class="border border-teal-200 rounded-xl bg-teal-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-teal-800">Log Site-Visit Feedback</summary>
                <form data-ajax-form data-reload="1" action="{{ route('cc.leads.site-visit-feedback', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <textarea name="feedback" required rows="4" placeholder="Client reaction, preferences observed..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                    <button type="submit" class="bg-teal-700 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Save Feedback</button>
                </form>
            </details>
            @endif

            {{-- Negotiate --}}
            @if(in_array($lead->stage, ['site_visit_done', 'negotiation']))
            <details class="border border-amber-200 rounded-xl bg-amber-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-amber-800">Negotiation Notes</summary>
                <form data-ajax-form data-reload="1" action="{{ route('cc.leads.negotiate', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <textarea name="negotiation_notes" rows="4" placeholder="Price negotiation, counter-offers..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none">{{ $lead->negotiation_notes }}</textarea>
                    @if($lead->stage === 'site_visit_done')
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="advance_stage" value="1"> Move to Negotiation stage
                    </label>
                    @endif
                    <button type="submit" class="bg-amber-600 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Save</button>
                </form>
            </details>
            @endif

            {{-- Close deal --}}
            @if($lead->stage === 'negotiation')
            <details class="border border-green-200 rounded-xl bg-green-50">
                <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-green-800">✓ Close Deal</summary>
                <form data-ajax-form action="{{ route('cc.leads.close-deal', $lead) }}" method="POST" class="p-4 space-y-3">
                    @csrf
                    <input type="number" name="deal_value" required min="1" step="1" placeholder="Deal value (₹)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <textarea name="deal_notes" rows="2" placeholder="Final notes..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                    <button type="submit" class="bg-green-700 text-white text-sm px-4 py-2 rounded-lg hover:opacity-90">Close Deal</button>
                </form>
            </details>
            @endif

            {{-- Side-state controls --}}
            <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
                <button onclick="$('#modal-hold').show()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100">Hold</button>
                <button onclick="$('#modal-defer').show()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100">Defer</button>
                <button onclick="$('#modal-lost').show()" class="text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Mark Lost</button>
            </div>
        </div>
        @endif

        @if($lead->is_on_hold || $lead->is_deferred)
        <form data-ajax-form data-reload="1" action="{{ route('cc.leads.resume', $lead) }}" method="POST">
            @csrf
            <button class="w-full bg-blue-600 text-white py-2 rounded-xl text-sm font-medium hover:opacity-90">Resume Lead</button>
        </form>
        @endif
    </div>

    {{-- Right: history --}}
    <div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-heading text-zendo-navy mb-4">Stage History</h3>
            <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                @forelse($history as $h)
                <li class="ml-4">
                    <span class="absolute -left-1.5 mt-1 w-3 h-3 bg-zendo-gold rounded-full border-2 border-white"></span>
                    <div class="text-xs font-semibold text-gray-700">{{ $h->from_stage_label }} → {{ $h->to_stage_label }}</div>
                    @if($h->note)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 120) }}</p>@endif
                    <time class="text-xs text-gray-400">{{ $h->created_at->diffForHumans() }} @if($h->changedBy) · {{ $h->changedBy->name }} @endif</time>
                </li>
                @empty
                    <li class="ml-4 text-xs text-gray-400">No history yet.</li>
                @endforelse
            </ol>
        </div>
    </div>
</div>

{{-- Modals --}}
<div id="modal-hold" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Put Lead on Hold</h3>
        <form data-ajax-form data-reload="1" action="{{ route('cc.leads.hold', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" placeholder="Reason (optional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="date" name="hold_until" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-hold').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Confirm Hold</button>
            </div>
        </form>
    </div>
</div>
<div id="modal-defer" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Defer Follow-up</h3>
        <form data-ajax-form data-reload="1" action="{{ route('cc.leads.defer', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="datetime-local" name="defer_until" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="text" name="reason" placeholder="Reason" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-defer').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Defer</button>
            </div>
        </form>
    </div>
</div>
<div id="modal-lost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-red-700 text-lg mb-4">Mark as Lost</h3>
        <form data-ajax-form action="{{ route('cc.leads.lost', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" required placeholder="Reason..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#modal-lost').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg">Mark Lost</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Intercept site-visit form to show the generated URL inline
$(document).on('submit', '#form-sitevisit', function (e) {
    e.preventDefault();
    const $btn = $(this).find('[type=submit]');
    $btn.prop('disabled', true);
    $.post($(this).attr('action'), $(this).serialize(), function (res) {
        if (res.success) {
            $('#sitevisit-url').removeClass('hidden').html(
                '<strong>Link:</strong> <a href="' + res.url + '" target="_blank" class="underline break-all">' + res.url + '</a>' +
                '<br><span class="text-gray-500">Expires: ' + res.expires_at + '</span>'
            );
            showToast('Site-visit link generated.', 'success');
        } else {
            showToast(res.message, 'error');
        }
    }).fail(function (xhr) {
        showToast(xhr.responseJSON?.message || 'Failed.', 'error');
    }).always(function () { $btn.prop('disabled', false); });
});
</script>
@endpush
@endsection
