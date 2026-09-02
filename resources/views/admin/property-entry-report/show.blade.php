@extends('layouts.admin')
@section('title', 'Entry — ' . $entry->code . ' - ZendoIndia Admin')
@section('page-title', 'Property Entry Detail')
@section('page-description', 'Complete read-only view of field officer property entry ' . $entry->code)

@section('content')
@php
    $card = 'bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-4';

    // Section/field layout for THIS row's property_type — see
    // config/property_entry_sections.php. Legacy rows with a NULL
    // property_type resolve to warehouse.
    $sections = $entry->sectionMap();
    $otherData = $entry->unmappedData();

    $tierBadge = [
        'INTERNAL' => 'bg-gray-100 text-gray-600 border-gray-200',
        'VERIFIED' => 'bg-amber-50 text-amber-700 border-amber-200',
        'WEBSITE'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];

    // Renders one field's stored value per its declared type. Booleans are
    // compared loosely against the cast value (the model casts these columns
    // to real booleans, so a strict === 1 check silently rendered every
    // "Yes" answer as "—" before this was config-driven).
    $renderValue = function ($value, string $type) {
        if ($type === 'bool') {
            if ($value === null || $value === '') return null;
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'Yes' : 'No';
        }

        if ($type === 'date') {
            if (!$value) return null;
            return $value instanceof \Carbon\Carbon ? $value->format('d M Y') : $value;
        }

        if ($type === 'office_sizes') {
            if (!is_array($value)) return $value ?: null;
            $sizes = array_filter($value, fn($o) => !empty($o['l']) || !empty($o['w']));
            return implode(', ', array_map(
                fn($o) => ($o['l'] ?? 0) . ' × ' . ($o['w'] ?? 0) . ' ft (' . (($o['l'] ?? 0) * ($o['w'] ?? 0)) . ' sq ft)',
                $sizes
            )) ?: null;
        }

        return $value;
    };

    // Multi-value fields render as tag pills rather than a raw JSON blob.
    $asTags = function ($value) {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) $value = $decoded;
        }
        return is_array($value) ? array_filter(array_map(fn($v) => is_scalar($v) ? (string) $v : json_encode($v), $value), fn($v) => $v !== '') : null;
    };

@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center flex-wrap gap-2 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">{{ $entry->code }}</h2>
                @php
                    $badge = match($entry->status) {
                        'draft'     => 'bg-gray-100 text-gray-700',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'verified'  => 'bg-green-100 text-green-800',
                        'recheck'   => 'bg-orange-100 text-orange-700',
                        'rejected'  => 'bg-red-100 text-red-800',
                        default     => 'bg-gray-100 text-gray-600',
                    };
                    $label = match($entry->status) {
                        'draft'     => 'Draft',
                        'submitted' => 'Under Review',
                        'verified'  => 'Verified',
                        'recheck'   => 'Needs Recheck',
                        'rejected'  => 'Rejected',
                        default     => ucfirst($entry->status),
                    };
                @endphp
                {{-- Supply-head status badge --}}
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $label }}</span>

                @if($entry->is_expired)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">Expired</span>
                @endif

                {{-- Admin status badge (only once verified by supply head) --}}
                @if($entry->status === 'verified')
                    @if($entry->admin_status === 'approved')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Admin Approved
                        </span>
                    @elseif($entry->admin_status === 'rejected')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Admin Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pending Admin Review
                        </span>
                    @endif
                @endif

                {{-- Show on website toggle — only after admin approves --}}
                @if($entry->admin_status === 'approved')
                    <button
                        x-data="{
                            showOnWebsite: {{ $entry->show_on_website ? 'true' : 'false' }},
                            loading: false,
                            toggle() {
                                if (this.loading) return;
                                this.loading = true;
                                fetch('{{ route('admin.property-entry-report.toggle-website', $entry) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        this.showOnWebsite = data.show_on_website;
                                    } else {
                                        alert(data.message || 'Failed to update.');
                                    }
                                })
                                .catch(() => alert('Network error. Please try again.'))
                                .finally(() => { this.loading = false; });
                            }
                        }"
                        @click="toggle()"
                        :class="showOnWebsite ? 'bg-green-100 text-green-700 border-green-300' : 'bg-gray-100 text-gray-500 border-gray-300'"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border transition-colors hover:opacity-80 cursor-pointer"
                        :disabled="loading"
                    >
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span x-text="showOnWebsite ? 'Shown on Website' : 'Hidden from Website'"></span>
                    </button>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                Supply Head: <span class="font-medium">{{ $entry->supplyHead?->name ?? '—' }}</span>
                &bull; Officer: <span class="font-medium">{{ $entry->fieldOfficer?->name ?? '—' }}</span>
                &bull; {{ $entry->facility_type ?? '—' }} &bull; {{ $entry->nearest_city ?? '—' }}
            </p>
            @if($entry->submitted_at)
                <p class="text-xs text-gray-400 mt-0.5">Submitted: {{ $entry->submitted_at->format('d M Y, g:i A') }}</p>
            @endif
            @if($entry->admin_actioned_at)
                <p class="text-xs text-gray-400 mt-0.5">
                    Admin actioned by <span class="font-medium">{{ $entry->adminActioner?->name ?? '—' }}</span>
                    on {{ $entry->admin_actioned_at->format('d M Y, g:i A') }}
                </p>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.property-entry-report.edit', $entry) }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-zendo-gold text-zendo-navy hover:bg-amber-400 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Entry
            </a>
            <a href="{{ route('admin.property-entry-report.index', request()->query()) }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors px-2 py-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Report
            </a>
        </div>
    </div>

    {{-- Submitted Location — captured from the field officer's device at submit time --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-zendo-navy mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-zendo-navy">Field Officer Current Location</h3>
                    @if($entry->form_submited_address)
                        <p class="text-sm text-gray-600 mt-0.5">{{ $entry->form_submited_address }}</p>
                    @elseif($entry->form_submited_location)
                        <p class="text-sm text-gray-600 font-mono mt-0.5">{{ $entry->form_submited_location }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-0.5">Not captured — the field officer's device didn't provide a location for this entry.</p>
                    @endif
                </div>
            </div>
            @if($entry->form_submited_maps_url)
                <a href="{{ $entry->form_submited_maps_url }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-zendo-navy text-white text-xs font-semibold hover:opacity-90 transition-opacity self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View on Google Maps
                </a>
            @endif
        </div>
    </div>

    {{-- Recheck / Rejection note (supply head) --}}
    @if($entry->status === 'recheck' && $entry->supply_head_note)
        <div class="bg-orange-50 border border-orange-300 rounded-xl p-4">
            <p class="text-sm font-semibold text-orange-800 mb-1">&#9888; Recheck Note from Supply Head:</p>
            <p class="text-sm text-orange-700">{{ $entry->supply_head_note }}</p>
        </div>
    @endif
    @if($entry->status === 'rejected' && $entry->supply_head_note)
        <div class="bg-red-50 border border-red-300 rounded-xl p-4">
            <p class="text-sm font-semibold text-red-800 mb-1">&#10007; Rejected — Reason:</p>
            <p class="text-sm text-red-700">{{ $entry->supply_head_note }}</p>
            @if($entry->allow_resubmit)
                <p class="text-xs text-red-600 mt-1">Field officer can re-edit and resubmit.</p>
            @else
                <p class="text-xs text-red-600 mt-1">Permanently rejected (cannot resubmit).</p>
            @endif
        </div>
    @endif

    {{-- ── Admin Approve / Reject Panel ─────────────────────────────────────── --}}
    @if(true)
        <div
            x-data="{
                adminStatus: '{{ $entry->admin_status ?? '' }}',
                adminNote: '{{ addslashes($entry->admin_note ?? '') }}',
                actionedBy: '{{ addslashes($entry->adminActioner?->name ?? '') }}',
                actionedAt: '{{ $entry->admin_actioned_at?->format('d M Y, g:i A') ?? '' }}',
                showApproveModal: false,
                approveNote: '',
                showRejectModal: false,
                rejectNote: '',
                rejectError: '',
                loading: false,

                submitApprove() {
                    if (this.loading) return;
                    this.loading = true;
                    fetch('{{ route('admin.property-entry-report.admin-approve', $entry) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ note: this.approveNote })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            this.adminStatus     = 'approved';
                            this.adminNote       = this.approveNote;
                            this.actionedBy      = d.actioned_by;
                            this.actionedAt      = d.actioned_at;
                            this.showApproveModal = false;
                            window.location.reload();
                        } else { alert(d.message || 'Failed.'); }
                    })
                    .catch(() => alert('Network error.'))
                    .finally(() => { this.loading = false; });
                },

                submitReject() {
                    if (!this.rejectNote.trim()) { this.rejectError = 'Rejection reason is required.'; return; }
                    if (this.loading) return;
                    this.loading = true;
                    this.rejectError = '';
                    fetch('{{ route('admin.property-entry-report.admin-reject', $entry) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ note: this.rejectNote })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            this.adminStatus     = 'rejected';
                            this.adminNote       = this.rejectNote;
                            this.actionedBy      = d.actioned_by;
                            this.actionedAt      = d.actioned_at;
                            this.showRejectModal = false;
                            window.location.reload();
                        } else { alert(d.message || 'Failed.'); }
                    })
                    .catch(() => alert('Network error.'))
                    .finally(() => { this.loading = false; });
                }
            }"
        >
            {{-- Pending state: show Approve / Reject buttons --}}
            <template x-if="adminStatus === ''">
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Admin Review Required</p>
                        <p class="text-xs text-yellow-700 mt-0.5">This entry has been verified by the supply head. Approve it to enable website publishing controls.</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <button @click="showApproveModal = true" :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                        <button @click="showRejectModal = true" :disabled="loading"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </div>
                </div>
            </template>

            {{-- Approved state --}}
            <template x-if="adminStatus === 'approved'">
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Admin Approved</p>
                            <p class="text-xs text-emerald-700" x-text="'By ' + actionedBy + (actionedAt ? '  ·  ' + actionedAt : '')"></p>
                            <p class="text-sm text-emerald-700 mt-1" x-show="adminNote" x-text="adminNote"></p>
                        </div>
                    </div>
                    <button @click="adminStatus = ''; adminNote = ''; actionedBy = ''; actionedAt = ''; approveNote = ''" :disabled="loading"
                        class="text-xs text-emerald-600 hover:text-emerald-800 underline underline-offset-2 flex-shrink-0">
                        Change decision
                    </button>
                </div>
            </template>

            {{-- Rejected state --}}
            <template x-if="adminStatus === 'rejected'">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Admin Rejected</p>
                            <p class="text-xs text-red-700" x-text="'By ' + actionedBy + (actionedAt ? '  ·  ' + actionedAt : '')"></p>
                            <p class="text-sm text-red-700 mt-1" x-text="adminNote"></p>
                        </div>
                    </div>
                    <button @click="adminStatus = ''; adminNote = ''; actionedBy = ''; actionedAt = ''; rejectNote = ''" :disabled="loading"
                        class="text-xs text-red-600 hover:text-red-800 underline underline-offset-2 flex-shrink-0">
                        Change decision
                    </button>
                </div>
            </template>

            {{-- Approve Modal --}}
            <template x-teleport="body">
                <div x-show="showApproveModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    style="background: rgba(0,0,0,0.5);">
                    <div @click.outside="showApproveModal = false; approveNote = ''"
                        class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Approve Entry</h3>
                                <p class="text-xs text-gray-500">Website visibility controls will become available after approval.</p>
                            </div>
                        </div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">
                            Remark <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea
                            x-model="approveNote"
                            rows="3"
                            placeholder="Add a note about this approval..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 resize-none"
                        ></textarea>
                        <div class="flex justify-end gap-3 mt-4">
                            <button @click="showApproveModal = false; approveNote = ''"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button @click="submitApprove()" :disabled="loading"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors disabled:opacity-60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Confirm Approve
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Reject Modal --}}
            <template x-teleport="body">
                <div x-show="showRejectModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    style="background: rgba(0,0,0,0.5);">
                    <div @click.outside="showRejectModal = false; rejectNote = ''; rejectError = ''"
                        class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Reject Entry</h3>
                                <p class="text-xs text-gray-500">Provide a reason — this will be recorded in the activity log.</p>
                            </div>
                        </div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">
                            Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            x-model="rejectNote"
                            rows="4"
                            placeholder="Enter rejection reason..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"
                            :class="rejectError ? 'border-red-400' : ''"
                        ></textarea>
                        <p x-show="rejectError" x-text="rejectError" class="text-xs text-red-600 mt-1"></p>
                        <div class="flex justify-end gap-3 mt-4">
                            <button @click="showRejectModal = false; rejectNote = ''; rejectError = ''"
                                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 rounded-lg border border-gray-200 hover:border-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button @click="submitReject()" :disabled="loading"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Confirm Reject
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif

    {{-- All Data Fields by Section — driven by this row's property_type --}}
    @foreach($sections as $sectionTitle => $fields)
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">{{ $sectionTitle }}</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($fields as $fieldName => $field)
                    @php
                        $raw = $entry->fieldValue($fieldName);
                        $tags = $field['type'] === 'json' ? $asTags($raw) : null;
                        $value = $field['type'] === 'json' ? null : $renderValue($raw, $field['type']);
                    @endphp
                    <div @class(['sm:col-span-2 lg:col-span-3' => $field['wide']])>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium flex items-center gap-1.5">
                            <span>{{ $field['label'] }}</span>
                            @if($field['tier'])
                                <span class="inline-flex items-center px-1.5 py-px rounded border text-[9px] font-semibold tracking-normal {{ $tierBadge[$field['tier']] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}"
                                    title="{{ $field['tier'] === 'WEBSITE' ? 'Visible to public visitors' : ($field['tier'] === 'VERIFIED' ? 'Visible to verified/logged-in users only' : 'Internal only — never published') }}">
                                    {{ $field['tier'] }}
                                </span>
                            @endif
                        </dt>
                        @if($field['type'] === 'json')
                            <dd class="mt-1">
                                @if($tags && count($tags))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($tags as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm font-medium text-gray-900">—</span>
                                @endif
                            </dd>
                        @else
                            <dd @class(['text-sm font-medium text-gray-900 mt-0.5', 'whitespace-pre-wrap' => $field['wide']])>{{ ($value === null || $value === '') ? '—' : $value }}</dd>
                        @endif
                    </div>
                @endforeach
            </dl>
        </div>
    @endforeach

    {{-- Other Data — submitted values this type's section map doesn't cover
         (legacy columns, or custom_fields from the dedicated wizard forms).
         Shown rather than dropped so nothing an officer entered disappears. --}}
    @if(count($otherData))
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-1 pb-2 border-b border-gray-100">Other Data</h3>
            <p class="text-xs text-gray-400 mb-4">Submitted values not covered by this property type's standard sections.</p>
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($otherData as $fieldName => $rawValue)
                    @php $tags = $asTags($rawValue); @endphp
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide font-medium">{{ Str::headline($fieldName) }}</dt>
                        @if($tags !== null && count($tags))
                            <dd class="mt-1 flex flex-wrap gap-1">
                                @foreach($tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">{{ $tag }}</span>
                                @endforeach
                            </dd>
                        @else
                            <dd class="text-sm font-medium text-gray-900 mt-0.5 whitespace-pre-wrap">{{ is_scalar($rawValue) ? $rawValue : json_encode($rawValue) }}</dd>
                        @endif
                    </div>
                @endforeach
            </dl>
        </div>
    @endif

    {{-- J. Photographs (8 slots) --}}
    <div class="{{ $card }}">
        <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">J. Photographs ({{ $entry->photos->count() }}/8 uploaded)</h3>
        @if($entry->photos->count())
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($entry->photos as $photo)
                    <div>
                        <a href="{{ $photo->url }}" target="_blank" class="block group">
                            <img src="{{ $photo->url }}" alt="{{ $photo->slot_label }}"
                                class="w-full aspect-square object-cover rounded-lg border-2 border-gray-200 group-hover:border-zendo-gold transition-colors">
                        </a>
                        <p class="text-xs text-gray-600 mt-1.5 text-center leading-tight font-medium">{{ $photo->slot_label }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">No photos uploaded.</p>
        @endif
    </div>

    {{-- Activity Log --}}
    @if($entry->logs && $entry->logs->count())
        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-zendo-navy mb-4 pb-2 border-b border-gray-100">Activity Log</h3>
            <ol class="relative border-l-2 border-gray-200 ml-3 space-y-5">
                @foreach($entry->logs as $log)
                    <li class="ml-5">
                        <div class="absolute w-3 h-3 bg-zendo-gold rounded-full mt-1.5 -left-[7px] border-2 border-white"></div>
                        <p class="text-xs text-gray-500">{{ $log->created_at->format('d M Y, g:i A') }}</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ ucwords(str_replace('_', ' ', $log->action)) }}</p>
                        @if($log->user)
                            <p class="text-xs text-gray-600 mt-0.5">by <span class="font-medium">{{ $log->user->name }}</span> ({{ $log->user->role }})</p>
                        @endif
                        @if($log->note)
                            <p class="text-xs text-gray-700 italic mt-1 bg-gray-50 rounded px-2 py-1 border border-gray-100">{{ $log->note }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    @endif

</div>
@endsection
