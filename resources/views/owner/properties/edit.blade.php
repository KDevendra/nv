@extends('layouts.owner')
@section('title', 'Edit Property — ' . $property->code)

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Edit &amp; Resubmit</h2>
            <p class="text-gray-500 text-sm mt-1">Code: <span class="font-mono font-medium">{{ $property->code }}</span></p>
        </div>
        <a href="{{ route('owner.dashboard') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    {{-- Recheck note --}}
    @if($property->status === 'recheck' && $property->supply_head_note)
        <div class="mb-4 bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-lg text-sm">
            <p class="font-semibold mb-1">&#9888; Note from Supply Head:</p>
            <p>{{ $property->supply_head_note }}</p>
        </div>
    @endif

    {{-- Rejection note --}}
    @if($property->status === 'rejected' && $property->supply_head_note)
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            <p class="font-semibold mb-1">&#10007; Rejected — Reason from Supply Head:</p>
            <p>{{ $property->supply_head_note }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('owner.properties.update', $property) }}" enctype="multipart/form-data" x-data="{ isDraft: false }">
        @csrf
        @method('PUT')
        @php $entry = $property; @endphp
        @include('field.properties._form')
    </form>

</div>
@endsection
