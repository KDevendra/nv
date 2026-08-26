@extends('layouts.owner')
@section('title', 'Property Details — ' . ($property->code ?? 'Property #' . $property->id))

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h2 class="text-2xl font-heading text-zendo-navy font-bold">{{ $property->code ?? ('ID: #' . $property->id) }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $property->status_badge_class }}">
                    {{ $property->status_label }}
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium">
                {{ ucwords(str_replace('_', ' ', $property->property_type ?? 'Property')) }}
                @if($property->unit_property_type) &bull; {{ $property->unit_property_type }} @endif
                @if($property->nearest_city) &bull; {{ $property->nearest_city }} @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.properties.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-zendo-navy transition-colors px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
            @if($property->isEditable())
                <a href="{{ $property->owner_edit_url }}"
                    class="inline-flex items-center px-4 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Property
                </a>
            @endif
        </div>
    </div>

    {{-- Recheck note --}}
    @if($property->status === 'recheck' && $property->supply_head_note)
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 shadow-sm">
            <p class="text-sm font-bold text-amber-900 mb-1">⚠ Action Required — Note from Supply Head:</p>
            <p class="text-sm text-amber-800">{{ $property->supply_head_note }}</p>
        </div>
    @endif

    {{-- Rejection note --}}
    @if($property->status === 'rejected' && $property->supply_head_note)
        <div class="bg-red-50 border border-red-300 rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <p class="text-sm font-bold text-red-900 mb-1">✕ Rejected — Reason:</p>
                    <p class="text-sm text-red-800">{{ $property->supply_head_note }}</p>
                </div>
                @if($property->allow_resubmit)
                    <a href="{{ $property->owner_edit_url }}"
                        class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all shadow-sm">
                        Re-edit &amp; Resubmit
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- Property Details Component --}}
    <x-property-details-view :property="$property" />

</div>
@endsection
