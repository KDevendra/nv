@extends('layouts.field')
@section('title', 'Continue Draft — ' . $property->code)

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Continue Draft</h2>
            <p class="text-gray-500 text-sm mt-1">
                Code: <span class="font-mono font-medium">{{ $property->code }}</span>
                — finish the sections and submit when ready
            </p>
        </div>
        <a href="{{ route('supplyhead.properties.index') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @include('field.properties._error-stepper')

    <form method="POST" action="{{ route('supplyhead.properties.update', $property) }}" enctype="multipart/form-data" x-data="{ isDraft: false }">
        @csrf
        @method('PUT')
        @php $entry = $property; @endphp
        @include('field.properties._form')

        {{-- Buttons are in the wizard nav bar inside _form.blade.php --}}
    </form>

</div>
@endsection
