@extends('layouts.app')

@section('title', 'Page Not Found - 404')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-pattern-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Error Content -->
        <div class="mb-8">
            <h1 class="text-6xl font-heading text-zendo-navy mb-4">&nbsp;</h1>
            <h2 class="text-3xl font-heading text-zendo-navy mb-4">Page Not Found</h2>
            <p class="text-lg text-gray-600 font-body max-w-2xl mx-auto mb-8">
                Sorry, we couldn't find the page you're looking for. The page might have been moved, deleted, or you entered the wrong URL.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="px-8 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 btn-anim btn-light-bg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Back to Home
            </a>
            
            <button onclick="history.back()" 
                    class="px-8 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all transform hover:scale-105 border border-zendo-navy text-zendo-navy hover:bg-zendo-navy hover:text-white">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </button>
        </div>

        <!-- Help Section -->
        <div class="mt-12 bg-zendo-light-bg rounded-lg p-6 border border-zendo-gold/20">
            <h3 class="text-lg font-semibold text-zendo-navy font-heading mb-4">Need Help Finding What You're Looking For?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div class="text-center">
                    <div class="w-12 h-12 bg-zendo-gold rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-zendo-navy font-highlight mb-2">Call Us</h4>
                    <p class="text-gray-600 font-body">
                        <a href="tel:+917494010101" class="hover:text-zendo-gold transition-colors">+91 74-94-01-01-01</a>
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-zendo-gold rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-zendo-navy font-highlight mb-2">Email Us</h4>
                    <p class="text-gray-600 font-body">
                        <a href="mailto:info@zendoindia.com" class="hover:text-zendo-gold transition-colors">info@zendoindia.com</a>
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-12 h-12 bg-zendo-gold rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-zendo-navy font-highlight mb-2">Search Properties</h4>
                    <p class="text-gray-600 font-body">
                        <a href="{{ route('home') }}#search" class="hover:text-zendo-gold transition-colors">Browse our listings</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection