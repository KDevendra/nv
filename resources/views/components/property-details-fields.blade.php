{{--
    Shared rendering ENGINE for the 13 per-type Property Details View pages
    (resources/views/components/property-details/*.blade.php). This file
    holds no field names or values of its own — every one of the 13 pages
    passes in its own $sections (from config/property_entry_sections.php,
    built by parsing that type's actual create.blade.php form), so the
    section order, headings and field labels genuinely mirror that type's
    own form. This file is only the presentation logic: value formatting,
    conditional show/hide, and the photo/document gallery — the same job
    wizard-field-validation.blade.php does for form input, applied once
    instead of duplicated 13 times.
--}}
@props([
    'property' => null,
    'sections' => [],
])

@php
    if (!$property) return;

    $card = 'bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4';
    $isUrl = fn ($v) => is_string($v) && preg_match('#^https?://#i', trim($v));

    $fmtVal = function ($value) use ($isUrl) {
        if (is_null($value) || $value === '' || $value === []) {
            return '<span class="text-gray-400 font-normal">—</span>';
        }

        if (is_array($value)) {
            $items = array_filter($value, fn ($i) => $i !== null && $i !== '');
            $pills = array_map(
                fn ($item) => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-zendo-navy border border-slate-200 mr-1.5 mb-1 shadow-2xs">' . e(is_scalar($item) ? $item : json_encode($item)) . '</span>',
                $items
            );
            return !empty($pills) ? '<div class="flex flex-wrap mt-0.5">' . implode('', $pills) . '</div>' : '<span class="text-gray-400 font-normal">—</span>';
        }

        if ($value instanceof \DateTimeInterface) {
            return '<span class="font-semibold text-gray-900">' . e($value->format('d M Y')) . '</span>';
        }

        if (is_bool($value)) {
            return $value
                ? '<span class="inline-flex items-center gap-1 text-emerald-700 font-semibold"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>Yes</span>'
                : '<span class="inline-flex items-center gap-1 text-gray-400 font-semibold"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>No</span>';
        }

        if ($isUrl($value)) {
            return '<a href="' . e($value) . '" target="_blank" rel="noopener" class="text-zendo-gold hover:underline font-semibold break-all">' . e($value) . ' <span aria-hidden="true">↗</span></a>';
        }

        // Thousands-separator for large numbers — generic (not per-field
        // hardcoded), since the field label already carries any unit
        // (e.g. "Carpet Area (sq ft)"), so the value itself doesn't need to.
        if (is_numeric($value) && abs((float) $value) >= 1000) {
            $formatted = rtrim(rtrim(number_format((float) $value, 2), '0'), '.');
            return '<span class="font-semibold text-gray-900">' . e($formatted) . '</span>';
        }

        return '<span class="font-semibold text-gray-900 whitespace-pre-wrap">' . e($value) . '</span>';
    };

    $dl = function ($label, $value, $fullWidth = false) use ($fmtVal) {
        $colSpan = $fullWidth ? 'col-span-1 sm:col-span-2 md:col-span-3' : '';
        return '<div class="' . $colSpan . '"><dt class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-0.5">' . e($label) . '</dt><dd class="text-sm mt-0.5">' . $fmtVal($value) . '</dd></div>';
    };

    // office_sizes is a repeatable {l, w} row group (warehouse) — render as
    // "L × W ft" per row rather than a raw array dump.
    $formatOfficeSizes = function ($raw) {
        if (!is_array($raw)) {
            return $raw;
        }
        $rows = array_filter($raw, fn ($o) => is_array($o) && (!empty($o['l']) || !empty($o['w'])));
        return implode(', ', array_map(fn ($o) => ($o['l'] ?? 0) . ' × ' . ($o['w'] ?? 0) . ' ft', $rows)) ?: null;
    };

    $otherData = $property->unmappedData();
@endphp

<div class="space-y-6">

    @foreach($sections as $sectionTitle => $fields)
        @continue(!$property->isSectionApplicable($sectionTitle))
        @php
            $rows = [];
            foreach ($fields as $column => $def) {
                if (!$property->isFieldApplicable($column)) {
                    continue;
                }
                $type = is_array($def) ? ($def['type'] ?? 'text') : 'text';
                $label = is_array($def) ? ($def['label'] ?? $column) : $def;
                $wide = is_array($def) ? ($def['wide'] ?? false) : false;

                $raw = $property->fieldValue($column);
                if ($type === 'office_sizes') {
                    $raw = $formatOfficeSizes($raw);
                }

                $rows[] = [$label, $raw, $wide];
            }
        @endphp
        @continue(empty($rows))
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100">
                {{ $sectionTitle }}
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($rows as [$label, $value, $wide])
                    {!! $dl($label, $value, $wide) !!}
                @endforeach
            </dl>
        </div>
    @endforeach

    {{-- Values genuinely submitted but not covered by this type's own
         section map (legacy columns, or custom_fields keys this page's
         form doesn't currently ask for) — shown rather than silently
         dropped, matching the admin detail view's same rule. --}}
    @if(count($otherData))
        <div class="{{ $card }}">
            <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-1 border-b border-gray-100">
                Other Submitted Data
            </h3>
            <p class="text-xs text-gray-400 -mt-1">Additional values on this listing not part of the standard form sections above.</p>
            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($otherData as $field => $value)
                    {!! $dl(\Illuminate\Support\Str::headline($field), $value) !!}
                @endforeach
            </dl>
        </div>
    @endif

    {{-- Photos & Documents — images render as a preview gallery; PDFs and
         other non-image uploads render as a viewable/downloadable file card,
         since the same photo_N upload slots on several forms accept either
         (e.g. warehouse's "Fire NOC document" slot, apartment's "Floor Plan
         / Layout" slot). --}}
    @if($property->photos && $property->photos->count() > 0)
        @php
            $imageExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $images = $property->photos->filter(fn ($p) => in_array(strtolower(pathinfo($p->file_path ?? '', PATHINFO_EXTENSION)), $imageExts));
            $documents = $property->photos->reject(fn ($p) => in_array(strtolower(pathinfo($p->file_path ?? '', PATHINFO_EXTENSION)), $imageExts));
            $resolveUrl = fn ($photo) => $photo->url ?? (str_starts_with($photo->file_path ?? '', 'http') ? $photo->file_path : asset($photo->file_path ?? ''));
        @endphp

        @if($images->count() > 0)
            <div class="{{ $card }}">
                <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Photographs ({{ $images->count() }})
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($images as $photo)
                        <div class="group relative bg-gray-50 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <a href="{{ $resolveUrl($photo) }}" target="_blank" rel="noopener" class="block aspect-square overflow-hidden">
                                <img src="{{ $resolveUrl($photo) }}" alt="{{ $photo->slot_label ?? 'Photo' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            </a>
                            <div class="p-2 text-center bg-white border-t border-gray-100">
                                <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $photo->slot_label }}">{{ $photo->slot_label ?? 'Photo' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($documents->count() > 0)
            <div class="{{ $card }}">
                <h3 class="text-sm font-bold text-zendo-navy uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents ({{ $documents->count() }})
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($documents as $doc)
                        <a href="{{ $resolveUrl($doc) }}" target="_blank" rel="noopener"
                            class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-zendo-gold hover:bg-amber-50/40 transition-colors">
                            <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-zendo-navy truncate">{{ $doc->slot_label ?? 'Document' }}</p>
                                <p class="text-xs text-gray-500">View / Download</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

</div>
