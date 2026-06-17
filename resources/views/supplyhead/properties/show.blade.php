@extends('layouts.field')
@section('title', 'Review — ' . $property->code)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">{{ $property->code }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->status_badge_class }}">{{ $property->status_label }}</span>
                @if($property->is_expired)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">Expired</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                Officer: <span class="font-medium">{{ $property->fieldOfficer?->name }}</span>
                &bull; {{ $property->facility_type ?? '—' }} &bull; {{ $property->nearest_city ?? '—' }}
            </p>
        </div>
        <a href="{{ route('supplyhead.properties.index') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

    {{-- Action Form --}}
    @if(in_array($property->status, ['submitted', 'recheck', 'verified', 'rejected']))
    @php
        $allFieldsCorrect = isset($fields) && $fields->count() > 0 && $fields->every(fn($f) => $f['is_correct'] === true);
        $reviewedCount    = isset($fields) ? $fields->filter(fn($f) => $f['is_correct'] !== null)->count() : 0;
        $totalCount       = isset($fields) ? $fields->count() : 0;
    @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ showForm: {{ in_array($property->status, ['submitted','recheck']) ? 'true' : 'false' }} }">
            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-zendo-navy">Take Action</h3>
                @if(in_array($property->status, ['verified','rejected']))
                    <button @click="showForm = !showForm" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        <span x-text="showForm ? 'Hide' : 'Change Status'"></span>
                    </button>
                @endif
            </div>
            <div x-show="showForm" class="p-5" x-data="{ selectedAction: '{{ $property->status }}' }">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                @endif
                @if($errors->has('action'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <strong>Error:</strong> {{ $errors->first('action') }}
                    </div>
                @endif

                @if(!$allFieldsCorrect && in_array($property->status, ['submitted', 'recheck']))
                    <div class="mb-4 border border-amber-200 bg-amber-50 rounded-lg p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-amber-800">Field Review Incomplete</h4>
                            <p class="text-sm text-amber-700 mt-0.5">
                                Review all fields below before verifying. Progress: <strong>{{ $reviewedCount }} / {{ $totalCount }}</strong> fields reviewed.
                            </p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('supplyhead.properties.action', $property) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Decision <span class="text-red-500">*</span></label>
                        <select name="action" required x-model="selectedAction"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            <option value="">— Select Action —</option>
                            <option value="verified" {{ !$allFieldsCorrect ? 'disabled' : '' }}>
                                &#10003; Verified — Approve this entry{{ !$allFieldsCorrect ? ' (complete field review first)' : '' }}
                            </option>
                            <option value="rejected">&#10007; Rejected — Permanently reject</option>
                            <option value="recheck">&#9888; Recheck — Send back to officer</option>
                        </select>
                        @error('action')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Note to Field Officer <span class="text-gray-400 text-xs">(required for Recheck / Reject)</span></label>
                        <textarea name="note" rows="3" placeholder="Explain what needs to be corrected or why it is rejected..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('note', $property->supply_head_note) }}</textarea>
                        @error('note')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div x-show="selectedAction === 'rejected'" x-transition class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="allow_resubmit" value="1"
                                {{ old('allow_resubmit', $property->allow_resubmit) ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-zendo-navy border-gray-300 rounded focus:ring-zendo-gold">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Allow field officer to re-edit and resubmit</span>
                                <p class="text-xs text-gray-600 mt-1">If unchecked, the entry will be permanently rejected with no option to re-edit.</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow hover:shadow-md">
                            Submit Decision
                        </button>
                    </div>
                </form>
            </div>
            @if(!in_array($property->status, ['submitted','recheck']))
                <div x-show="!showForm" class="px-5 py-4 text-sm text-gray-500">
                    Current status: <span class="font-semibold {{ $property->status_badge_class }} px-2 py-0.5 rounded-full text-xs">{{ $property->status_label }}</span>
                    @if($property->status === 'rejected')
                        @if($property->allow_resubmit)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 ml-2">Re-edit Allowed</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-2">Re-edit Not Allowed</span>
                        @endif
                    @endif
                    @if($property->supply_head_note)
                        <br><span class="italic mt-1 block">Note: {{ $property->supply_head_note }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Field Validation Table — only for submitted/recheck --}}
    @if(in_array($property->status, ['submitted', 'recheck']) && isset($fields))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="fieldReview()">

            {{-- Card Header --}}
            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-zendo-navy">Field Validation</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Review each field section-by-section and mark as correct or incorrect</p>
                </div>
                <button @click="markAllCorrect" type="button"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-all shadow">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark All Correct
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="px-5 py-3 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="text-gray-700 font-medium">Review Progress</span>
                    <span class="text-gray-600 font-medium" x-text="reviewStats.reviewed + ' / ' + reviewStats.total + ' fields reviewed'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500"
                        :style="'width:' + reviewStats.percentage + '%'"></div>
                </div>
                <div class="flex gap-4 mt-2 text-xs">
                    <span class="text-green-700"><span class="font-bold" x-text="reviewStats.correct"></span> correct</span>
                    <span class="text-red-700"><span class="font-bold" x-text="reviewStats.incorrect"></span> incorrect</span>
                    <span class="text-gray-500"><span class="font-bold" x-text="reviewStats.pending"></span> pending</span>
                </div>
            </div>

            @php
                $fieldsByName = $fields->keyBy('name');
                $sections = [
                    ['key' => 'A', 'title' => 'Location & Identification',   'fields' => ['facility_type','nearest_city','village_town_district','postal_address_pin','nearest_highway','nearest_railway_station','nearest_airport','name_full_address']],
                    ['key' => 'B', 'title' => 'Legal & Statutory',           'fields' => ['tenure','approved_land_use','fire_noc','clu_conversion_status','occupancy_certificate']],
                    ['key' => 'C', 'title' => 'Property Dimensions',         'fields' => ['plot_area','built_up_area','clear_height_highest','clear_height_side','number_of_floors','fsi_far']],
                    ['key' => 'D', 'title' => 'Loading & Docking',           'fields' => ['dock_door_count','dock_type','dock_height','truck_movement']],
                    ['key' => 'E', 'title' => 'Environment & Utilities',     'fields' => ['flooring_type','office_cabin_area','washrooms','ventilation_lighting','power_sanctioned_kva','discom_name','water_source','fire_fighting_system']],
                    ['key' => 'G', 'title' => 'Financial & Lease Terms',     'fields' => ['deal_type','expected_rent','expected_sale_price','security_deposit_months','lock_in_years','available_from']],
                    ['key' => 'H', 'title' => 'Surroundings & Emergency',    'fields' => ['approach_road_width','top_neighbouring_companies','flood_risk','nearest_hospital_km','nearest_fire_station_km','nearest_police_station_km']],
                    ['key' => 'K', 'title' => 'Remarks & Contact',           'fields' => ['owner_contact_name','owner_contact_phone','remarks']],
                ];
            @endphp

            {{-- Collapsible Sections --}}
            @foreach($sections as $section)
                <div x-data="{ open: true }" class="border-b border-gray-100 last:border-0">
                    {{-- Section Toggle Header --}}
                    <button @click="open = !open" type="button"
                        class="w-full px-5 py-3 bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between text-left">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-zendo-navy text-white text-xs font-bold flex-shrink-0">{{ $section['key'] }}</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $section['title'] }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Section Table --}}
                    <div x-show="open" x-collapse>
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/4">Field</th>
                                    <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Value</th>
                                    <th class="px-5 py-2 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-52">Review</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($section['fields'] as $fn)
                                    @php $field = $fieldsByName->get($fn); @endphp
                                    @if($field)
                                        <tr x-data="fieldRow(@js($field))" x-init="status = field.is_correct; remarking = false"
                                            :class="status === false ? 'bg-red-50' : (status === true ? 'bg-green-50/30' : '')"
                                            class="transition-colors">
                                            {{-- Field Label --}}
                                            <td class="px-5 py-3 text-sm font-medium text-gray-700 w-1/4">{{ $field['label'] }}</td>

                                            {{-- Value --}}
                                            <td class="px-5 py-3 text-sm text-gray-900 w-1/3">
                                                <span class="break-words">{{ $field['value'] ?: '—' }}</span>
                                                {{-- Show remark inline when incorrect --}}
                                                <template x-if="status === false && field.remark">
                                                    <p class="mt-1 text-xs text-red-600 italic" x-text="'Remark: ' + field.remark"></p>
                                                </template>
                                            </td>

                                            {{-- Status badge (reactive) --}}
                                            <td class="px-5 py-3 w-28">
                                                <template x-if="status === null">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Pending</span>
                                                </template>
                                                <template x-if="status === true && !remarking">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">✓ Correct</span>
                                                </template>
                                                <template x-if="status === false && !remarking">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">✗ Incorrect</span>
                                                </template>
                                                <template x-if="remarking">
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Reviewing…</span>
                                                </template>
                                            </td>

                                            {{-- Action: toggle checkboxes + inline remark --}}
                                            <td class="px-5 py-3">
                                                {{-- When NOT in remark mode: show inline toggle buttons --}}
                                                <div x-show="!remarking" class="flex items-center gap-2">
                                                    {{-- Correct toggle --}}
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none"
                                                        @click.prevent="markCorrect">
                                                        <span class="relative inline-flex">
                                                            <input type="checkbox" class="sr-only" :checked="status === true" readonly>
                                                            <span :class="status === true ? 'bg-green-600 border-green-600' : 'bg-white border-gray-300'"
                                                                class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                                <svg x-show="status === true" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                        <span :class="status === true ? 'text-green-700 font-semibold' : 'text-gray-500'"
                                                            class="text-xs transition-colors">Correct</span>
                                                    </label>

                                                    <span class="text-gray-300 text-xs">|</span>

                                                    {{-- Incorrect toggle --}}
                                                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none"
                                                        @click.prevent="startRemark">
                                                        <span class="relative inline-flex">
                                                            <input type="checkbox" class="sr-only" :checked="status === false" readonly>
                                                            <span :class="status === false ? 'bg-red-600 border-red-600' : 'bg-white border-gray-300'"
                                                                class="w-4 h-4 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0">
                                                                <svg x-show="status === false" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                        <span :class="status === false ? 'text-red-700 font-semibold' : 'text-gray-500'"
                                                            class="text-xs transition-colors">Incorrect</span>
                                                    </label>
                                                </div>

                                                {{-- Remark input inline --}}
                                                <div x-show="remarking" class="flex items-center gap-1.5">
                                                    <input type="text" x-model="remark"
                                                        placeholder="Remark (required)…"
                                                        @keydown.enter="saveIncorrect"
                                                        @keydown.escape="cancelRemark"
                                                        x-ref="remarkInput"
                                                        class="w-40 px-2 py-1 text-xs border border-red-300 rounded focus:ring-1 focus:ring-red-400 focus:border-transparent">
                                                    <button @click="saveIncorrect" type="button"
                                                        class="px-2.5 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 flex-shrink-0">
                                                        Save
                                                    </button>
                                                    <button @click="cancelRemark" type="button"
                                                        class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded hover:bg-gray-200 flex-shrink-0">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            {{-- Section J — Photographs --}}
            @if($property->photos->count())
                <div x-data="{ open: true }" class="border-b border-gray-100 last:border-0">
                    <button @click="open = !open" type="button"
                        class="w-full px-5 py-3 bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between text-left">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-zendo-navy text-white text-xs font-bold flex-shrink-0">J</span>
                            <span class="text-sm font-semibold text-gray-800">Photographs <span class="text-gray-400 font-normal text-xs">({{ $property->photos->count() }} photos)</span></span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 py-5">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($property->photos as $photo)
                                <div class="space-y-1.5">
                                    <a href="{{ $photo->url }}" target="_blank" class="block group">
                                        <img src="{{ $photo->url }}" alt="{{ $photo->slot_label }}"
                                            class="w-full aspect-square object-cover rounded-lg border-2 border-gray-200 group-hover:border-zendo-gold transition-colors">
                                    </a>
                                    <p class="text-xs text-gray-500 text-center font-medium leading-tight">{{ $photo->slot_label }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Alpine.js scripts --}}
        <script>
        function fieldReview() {
            return {
                fields: @js($fields->values()),
                reviewStats: { total: 0, reviewed: 0, correct: 0, incorrect: 0, pending: 0, percentage: 0 },
                init() { this.updateStats(); },
                updateStats() {
                    const c = this.fields.filter(f => f.is_correct === true).length;
                    const i = this.fields.filter(f => f.is_correct === false).length;
                    const t = this.fields.length;
                    this.reviewStats = { total: t, correct: c, incorrect: i, reviewed: c + i, pending: t - c - i, percentage: t ? Math.round((c + i) / t * 100) : 0 };
                },
                async markAllCorrect() {
                    if (!confirm('Mark all fields as correct?')) return;
                    const r = await fetch('{{ route('supplyhead.properties.mark-all-correct', $property) }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if (r.ok) location.reload();
                }
            };
        }
        function fieldRow(field) {
            return {
                field, status: field.is_correct, remark: field.remark || '', remarking: false,
                startRemark() {
                    this.remarking = true;
                    this.$nextTick(() => this.$refs.remarkInput?.focus());
                },
                cancelRemark() {
                    this.remarking = false;
                    this.remark = this.field.remark || '';
                    // Revert status badge back to saved state
                    this.status = this.field.is_correct;
                },
                async markCorrect() {
                    await this.saveReview(true, null);
                },
                async saveIncorrect() {
                    if (!this.remark.trim()) { alert('Remark is required for incorrect fields'); return; }
                    await this.saveReview(false, this.remark);
                },
                async saveReview(isCorrect, remark) {
                    const r = await fetch('{{ route('supplyhead.properties.review-field', $property) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ field_name: this.field.name, field_label: this.field.label, field_value: this.field.value, is_correct: isCorrect, remark })
                    });
                    if (r.ok) {
                        this.status = isCorrect;
                        this.field.is_correct = isCorrect;
                        this.field.remark = remark;
                        this.remarking = false;
                        // Update parent progress bar
                        const p = this.$el.closest('[x-data*="fieldReview"]');
                        if (p && p._x_dataStack) p._x_dataStack[0].updateStats();
                    }
                }
            };
        }
        </script>

    @endif

</div>
@endsection
