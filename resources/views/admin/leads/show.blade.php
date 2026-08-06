@extends('layouts.admin')

@section('title', 'Lead — ' . $lead->name)

@section('content')
<div class="p-6 space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.leads.index') }}" class="text-sm text-gray-500 hover:text-zendo-navy">← Back to Leads</a>
        <h2 class="text-2xl font-heading text-zendo-navy">{{ $lead->name }}</h2>
        <span class="stage-pill {{ $lead->stage_badge }}">{{ $lead->stage_label }}</span>
        @if($lead->side_state)
            <span class="stage-pill {{ $lead->side_state_badge }}">{{ $lead->side_state_label }}</span>
        @endif
        @if($lead->needs_division_review)
            <span class="stage-pill bg-orange-100 text-orange-700">⚠ Division Review</span>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Info + actions --}}
        <div class="xl:col-span-2 space-y-5">

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h3 class="font-heading text-zendo-navy mb-4 text-lg">Lead Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div><span class="font-medium">Phone:</span> {{ $lead->phone }}</div>
                    @if($lead->email)<div><span class="font-medium">Email:</span> {{ $lead->email }}</div>@endif
                    <div><span class="font-medium">Division:</span> {{ ucfirst($lead->division) }}</div>
                    <div><span class="font-medium">Created:</span> {{ $lead->created_at->format('d M Y, H:i') }}</div>
                    <div><span class="font-medium">Origin:</span> {{ $lead->origin_table ?? '—' }} #{{ $lead->origin_id ?? '—' }}</div>
                    <div><span class="font-medium">SE Assigned:</span> {{ $lead->assignedSE?->name ?? 'None' }}</div>
                    <div><span class="font-medium">CC Assigned:</span> {{ $lead->assignedCC?->name ?? 'Holding Queue' }}</div>
                    <div><span class="font-medium">Feasibility SH:</span> {{ $lead->feasibilitySH?->name ?? '—' }}</div>
                </div>
            </div>

            {{-- Admin actions --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
                <h3 class="font-heading text-zendo-navy text-lg">Admin Actions</h3>

                {{-- Assign SE --}}
                <details class="border border-gray-200 rounded-xl">
                    <summary class="px-4 py-3 cursor-pointer font-medium text-sm">Assign / Change Sales Executive</summary>
                    <form data-ajax-form data-reload="1" action="{{ route('admin.leads.assign-se', $lead) }}" method="POST" class="p-4 space-y-3">
                        @csrf
                        <select name="se_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— Select SE —</option>
                            @foreach($salesExecs as $u)
                                <option value="{{ $u->id }}" {{ $lead->assigned_se_id==$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Assign SE</button>
                    </form>
                </details>

                {{-- Assign CC --}}
                <details class="border border-gray-200 rounded-xl">
                    <summary class="px-4 py-3 cursor-pointer font-medium text-sm">Assign / Change Chief Coordinator</summary>
                    <form data-ajax-form data-reload="1" action="{{ route('admin.leads.assign-cc', $lead) }}" method="POST" class="p-4 space-y-3">
                        @csrf
                        <select name="cc_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— Select CC —</option>
                            @foreach($chiefCoords as $u)
                                <option value="{{ $u->id }}" {{ $lead->assigned_cc_id==$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-purple-700 text-white text-sm px-4 py-2 rounded-lg">Assign CC</button>
                    </form>
                </details>

                {{-- Override stage --}}
                <details class="border border-amber-200 rounded-xl bg-amber-50">
                    <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-amber-800">⚙ Override Pipeline Stage</summary>
                    <form data-ajax-form data-reload="1" action="{{ route('admin.leads.override-stage', $lead) }}" method="POST" class="p-4 space-y-3">
                        @csrf
                        <select name="stage" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @foreach(\App\Models\Lead::STAGES as $s)
                                <option value="{{ $s }}" {{ $lead->stage===$s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="reason" required placeholder="Reason for override…" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <button type="submit" class="bg-amber-600 text-white text-sm px-4 py-2 rounded-lg">Override Stage</button>
                    </form>
                </details>

                {{-- Resolve division --}}
                @if($lead->needs_division_review)
                <details class="border border-orange-200 rounded-xl bg-orange-50">
                    <summary class="px-4 py-3 cursor-pointer font-semibold text-sm text-orange-800">⚠ Resolve Division</summary>
                    <form data-ajax-form data-reload="1" action="{{ route('admin.leads.resolve-division', $lead) }}" method="POST" class="p-4 space-y-3">
                        @csrf
                        <select name="division" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">— Select Division —</option>
                            @foreach(['warehousing','residential','commercial'] as $d)
                                <option value="{{ $d }}">{{ ucfirst($d) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-orange-600 text-white text-sm px-4 py-2 rounded-lg">Confirm Division</button>
                    </form>
                </details>
                @endif

                {{-- Side-state controls --}}
                <div class="flex flex-wrap gap-2 pt-2 border-t border-gray-100">
                    @if(!$lead->is_on_hold && !$lead->is_lost)
                        <button onclick="$('#admin-modal-hold').show()" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100">Hold</button>
                    @endif
                    @if($lead->is_on_hold || $lead->is_deferred)
                        <form data-ajax-form data-reload="1" action="{{ route('admin.leads.resume', $lead) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50">Resume</button>
                        </form>
                    @endif
                    @if(!$lead->is_lost)
                        <button onclick="$('#admin-modal-lost').show()" class="text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Mark Lost</button>
                    @endif
                    <form data-ajax-form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                          onsubmit="return confirm('Soft-delete this lead?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: history --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h3 class="font-heading text-zendo-navy mb-4">Full Stage History</h3>
                <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                    @forelse($history as $h)
                    <li class="ml-4">
                        <span class="absolute -left-1.5 mt-1 w-3 h-3 bg-zendo-gold rounded-full border-2 border-white"></span>
                        <div class="text-xs font-semibold text-gray-700">{{ $h->from_stage_label }} → {{ $h->to_stage_label }}</div>
                        @if($h->is_side_state_change)
                            <div class="text-xs text-purple-600">({{ $h->from_side_state ?? 'active' }} → {{ $h->to_side_state ?? 'active' }})</div>
                        @endif
                        @if($h->note)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($h->note, 140) }}</p>@endif
                        <time class="text-xs text-gray-400">{{ $h->created_at->format('d M y, H:i') }}
                            @if($h->changedBy) · {{ $h->changedBy->name }} @endif
                        </time>
                    </li>
                    @empty
                        <li class="ml-4 text-xs text-gray-400">No history.</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Admin Hold Modal --}}
<div id="admin-modal-hold" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-zendo-navy text-lg mb-4">Put Lead on Hold</h3>
        <form data-ajax-form data-reload="1" action="{{ route('admin.leads.hold', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" placeholder="Reason (optional)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="date" name="hold_until" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#admin-modal-hold').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-zendo-navy text-white text-sm px-4 py-2 rounded-lg">Confirm Hold</button>
            </div>
        </form>
    </div>
</div>

{{-- Admin Lost Modal --}}
<div id="admin-modal-lost" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h3 class="font-heading text-red-700 text-lg mb-4">Mark as Lost</h3>
        <form data-ajax-form action="{{ route('admin.leads.lost', $lead) }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="reason" required placeholder="Reason..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="$('#admin-modal-lost').hide()" class="text-sm text-gray-500">Cancel</button>
                <button type="submit" class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg">Mark Lost</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // jQuery for admin view (include if not already in admin layout)
    if (typeof $ === 'undefined') {
        const s = document.createElement('script');
        s.src = 'https://code.jquery.com/jquery-3.7.1.min.js';
        document.head.appendChild(s);
        s.onload = () => initAdminLeadAjax();
    } else {
        initAdminLeadAjax();
    }

    function initAdminLeadAjax() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $(document).on('submit', 'form[data-ajax-form]', function (e) {
            e.preventDefault();
            const $form = $(this), $btn = $form.find('[type=submit]');
            $btn.prop('disabled', true);
            $.ajax({
                type: $form.attr('method') || 'POST', url: $form.attr('action'), data: $form.serialize(),
                success(res) {
                    if (res.success) { if ($form.data('reload')) location.reload(); else showAdminToast(res.message, 'success'); }
                    else showAdminToast(res.message || 'Error.', 'error');
                },
                error(xhr) { showAdminToast(xhr.responseJSON?.message || 'Failed.', 'error'); },
                complete() { $btn.prop('disabled', false); }
            });
        });
    }

    function showAdminToast(msg, type) {
        const cls = type === 'success' ? 'bg-green-600' : 'bg-red-600';
        const $t = $(`<div class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white ${cls}">${msg}</div>`);
        $('body').append($t);
        setTimeout(() => $t.fadeOut(400, () => $t.remove()), 3500);
    }
</script>
@endpush
@endsection
