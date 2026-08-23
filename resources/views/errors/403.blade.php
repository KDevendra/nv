@auth
    @if(request()->is('admin/*') || request()->is('admin'))
        @extends('layouts.admin')
        @section('title', '403 - Access Forbidden')
        @section('content')
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="text-center max-w-md mx-auto">
                <h1 class="text-6xl font-heading text-zendo-navy font-bold mb-2">403</h1>
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Access Forbidden</h2>
                <p class="text-gray-500 mb-8">You don't have permission to access this page. Contact your administrator if you think this is a mistake.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ auth()->user() ? auth()->user()->getDashboardUrl() : route('home') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go to Dashboard
                    </a>
                    <button onclick="history.back()"
                        class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </button>
                </div>
            </div>
        </div>
        @endsection
    @else
        @extends('layouts.app')
        @section('title', '403 - Forbidden')
        @section('content')
        <div class="min-h-screen flex items-center justify-center">
            <div class="text-center max-w-md mx-auto px-4">
                <h1 class="text-6xl font-heading text-zendo-navy font-bold mb-4">403</h1>
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">Access Forbidden</h2>
                <p class="text-gray-500 mb-8">You don't have permission to view this page.</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                    Back to Home
                </a>
            </div>
        </div>
        @endsection
    @endif
@else
    @extends('layouts.app')
    @section('title', '403 - Forbidden')
    @section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center max-w-md mx-auto px-4">
            <h1 class="text-6xl font-heading text-zendo-navy font-bold mb-4">403</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Access Forbidden</h2>
            <p class="text-gray-500 mb-8">You don't have permission to view this page.</p>
            <a href="{{ route('home') }}"
                class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all">
                Back to Home
            </a>
        </div>
    </div>
    @endsection
@endauth
