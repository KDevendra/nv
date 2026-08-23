@extends('layouts.app')

@section('title', 'Session Expired - 419')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-pattern-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Error Content -->
        <div class="mb-8">
            <h1 class="text-6xl font-heading text-zendo-navy mb-4">&nbsp;</h1>
            <h2 class="text-3xl font-heading text-zendo-navy mb-4">Your Session Expired</h2>
            <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto mb-8">
                For your security, this page was open long enough that its session timed out.
                Nothing was saved — please log in again and retry.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('login') }}"
               class="px-8 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 btn-anim btn-light-bg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Log In Again
            </a>

            <button onclick="history.back()"
                    class="px-8 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 border border-zendo-navy text-zendo-navy hover:bg-zendo-navy hover:text-white">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </button>
        </div>
    </div>
</div>
@endsection
