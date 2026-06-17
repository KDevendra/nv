@extends('layouts.field')
@section('title', 'Submit New Property')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Submit New Property</h2>
            <p class="text-gray-500 text-sm mt-1">Fill all sections and submit for review</p>
        </div>
        <a href="{{ route('field.dashboard') }}"
            class="inline-flex items-center text-sm text-gray-500 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('field.properties.store') }}" enctype="multipart/form-data">
        @csrf
        @php $entry = null; @endphp
        @include('field.properties._form')

        <div class="mt-6 flex items-center justify-end gap-4">
            <button type="submit" name="action" value="submit" onclick="return confirmSubmission()"
                class="inline-flex items-center px-8 py-3 bg-zendo-gold text-white font-semibold rounded-lg shadow-lg hover:bg-opacity-90 transition-all hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Submit to Office
            </button>
        </div>

        <script>
            function confirmSubmission() {
                return confirm('Are you sure you want to submit this property entry to the office? Once submitted, you cannot edit it until it is reviewed.');
            }
        </script>
    </form>

</div>
@endsection
