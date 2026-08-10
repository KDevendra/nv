@extends('layouts.field')
@section('title', 'Add Property')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Add Property</h2>
            <p class="text-gray-500 text-sm mt-1">Fill all sections — this entry goes straight to admin for approval</p>
        </div>
        <a href="{{ route('supplyhead.properties.index') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @include('field.properties._error-stepper')

    <form method="POST" action="{{ route('supplyhead.properties.store') }}" enctype="multipart/form-data" x-data="{ isDraft: false }">
        @csrf
        @php $entry = null; @endphp
        @include('field.properties._form')

        {{-- Buttons are in the wizard nav bar inside _form.blade.php --}}
    </form>

</div>
@endsection
