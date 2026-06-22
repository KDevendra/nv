@extends('layouts.admin')

@section('title', 'Dashboard - ZendoIndia Admin')

@section('page-title', 'Dashboard')
@section('page-description')
Welcome back, {{ Auth::user()->name }}! Here's what's happening with your real estate business today.
@endsection

@section('content')

@php $isAdmin = in_array(Auth::user()->role, ['super_admin', 'admin']); @endphp

@if(!$isAdmin)
{{-- ── STAFF: Restricted Dashboard ─────────────────────────────────────── --}}
<div class="space-y-6">
    {{-- Welcome --}}
    <div class="bg-gradient-to-r from-zendo-navy to-gray-800 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-2xl font-bold font-heading">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold font-heading">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-300 text-sm mt-0.5">{{ date('l, F j, Y') }}</p>
                <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                    Staff
                </span>
            </div>
        </div>
    </div>


    {{-- Info notice --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-amber-700">Your account has limited access. Contact an administrator to request additional permissions.</p>
    </div>
</div>

@else
{{-- ── ADMIN / SUPER ADMIN: Full Dashboard ─────────────────────────────── --}}

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-zendo-navy to-gray-800 rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-heading mb-2">Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-gray-200">Today is {{ date('l, F j, Y') }}. Here's your business overview.</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="text-center">
                    <p class="text-sm text-gray-200">Current Time</p>
                    <p class="text-xl font-bold" id="current-time">{{ date('H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-zendo-navy">{{ App\Models\User::count() }}</p>
                <p class="text-sm text-gray-600 font-medium">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Registered users
                    </span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Inquiries -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Inquiries</p>
                <p class="text-3xl font-bold text-zendo-navy">{{ App\Models\Inquiry::count() }}</p>
                <p class="text-sm text-yellow-600 font-medium">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                        </svg>
                        {{ App\Models\Inquiry::pending()->count() }} pending
                    </span>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Consultations -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Consultations</p>
                <p class="text-3xl font-bold text-zendo-navy">{{ App\Models\Consultation::count() }}</p>
                <p class="text-sm text-blue-600 font-medium">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        {{ App\Models\Consultation::pending()->count() }} pending
                    </span>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Cities -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Active Cities</p>
                <p class="text-3xl font-bold text-zendo-navy">{{ App\Models\City::where('status', true)->count() }}</p>
                <p class="text-sm text-purple-600 font-medium">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Service locations
                    </span>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Property Entry Quick Stats -->
@php
    $peTotal     = App\Models\PropertyEntry::count();
    $pePending   = App\Models\PropertyEntry::where('status', 'submitted')->count();
    $peVerified  = App\Models\PropertyEntry::where('status', 'verified')->count();
    $peRecheck   = App\Models\PropertyEntry::where('status', 'recheck')->count();
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-zendo-navy font-heading">Property Entry Report</h3>
            <p class="text-sm text-gray-500 mt-0.5">Field officer submissions overview</p>
        </div>
        <a href="{{ route('admin.property-entry-report.index') }}"
            class="inline-flex items-center px-4 py-2 bg-zendo-navy text-white text-xs font-semibold rounded-lg hover:bg-opacity-90 transition-all shadow">
            View Full Report
            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100">
        <div class="p-5 text-center">
            <p class="text-2xl font-bold text-zendo-navy">{{ $peTotal }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">Total Entries</p>
        </div>
        <div class="p-5 text-center">
            <a href="{{ route('admin.property-entry-report.index', ['status' => 'submitted']) }}" class="block hover:bg-blue-50 rounded-lg transition-colors -m-2 p-2">
                <p class="text-2xl font-bold text-blue-600">{{ $pePending }}</p>
                <p class="text-xs font-medium text-gray-500 mt-1">Under Review</p>
                @if($pePending > 0)
                    <span class="inline-block mt-1 text-xs text-blue-600 font-medium">Review now →</span>
                @endif
            </a>
        </div>
        <div class="p-5 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $peVerified }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">Verified</p>
        </div>
        <div class="p-5 text-center">
            <p class="text-2xl font-bold text-orange-500">{{ $peRecheck }}</p>
            <p class="text-xs font-medium text-gray-500 mt-1">Needs Recheck</p>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Consultations -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zendo-navy font-heading">Recent Consultations</h3>
                <span class="text-sm text-gray-500">Latest requests</span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $recentConsultations = App\Models\Consultation::latest()->take(3)->get();
                @endphp
                @forelse($recentConsultations as $consultation)
                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-semibold text-sm">{{ $consultation->initials }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $consultation->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $consultation->property_type ? $consultation->property_type . ' consultation' : 'General consultation' }}</p>
                        <p class="text-xs text-gray-400">{{ $consultation->phone }} • {{ $consultation->formatted_inquiry_type }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $consultation->created_at->diffForHumans() }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $consultation->status_badge }}">
                            {{ $consultation->formatted_status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500">No consultations yet</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.consultations.index') }}" class="inline-flex items-center text-zendo-navy hover:text-zendo-gold font-medium text-sm transition-colors">
                    View all consultations
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zendo-navy font-heading">Recent Inquiries</h3>
                <span class="text-sm text-gray-500">Latest leads</span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $recentInquiries = App\Models\Inquiry::latest()->take(3)->get();
                @endphp
                @forelse($recentInquiries as $inquiry)
                <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">{{ $inquiry->initials }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $inquiry->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $inquiry->property_type ? 'Interested in ' . $inquiry->property_type : 'General inquiry' }}</p>
                        <p class="text-xs text-gray-400">{{ $inquiry->phone }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $inquiry->created_at->diffForHumans() }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inquiry->status_badge }}">
                            {{ $inquiry->formatted_status }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                    </svg>
                    <p class="text-gray-500">No inquiries yet</p>
                </div>
                @endforelse
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center text-zendo-navy hover:text-zendo-gold font-medium text-sm transition-colors">
                    View all inquiries
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Analytics - AJAX Powered -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8" id="visitor-analytics">
    <!-- Header with Filters -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-zendo-navy font-heading">Visitor Analytics</h3>
                <p class="text-sm text-gray-500 mt-1">Track and analyze your website traffic</p>
            </div>
            <div class="flex gap-2">
                <button onclick="loadAnalytics('today')" id="btn-today" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Today
                </button>
                <button onclick="loadAnalytics('week')" id="btn-week" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Week
                </button>
                <button onclick="loadAnalytics('month')" id="btn-month" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Month
                </button>
                <button onclick="loadAnalytics('all')" id="btn-all" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-zendo-navy text-white">
                    All Time
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="analytics-loading" class="p-12 text-center hidden">
        <svg class="animate-spin h-8 w-8 mx-auto text-zendo-navy" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-500 mt-2">Loading analytics...</p>
    </div>

    <!-- Analytics Content -->
    <div id="analytics-content">
        <!-- Stats Overview -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Visits -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-blue-900">Total Visits</span>
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-blue-900" id="stat-visits">0</p>
                </div>

                <!-- Unique Visitors -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-green-900">Unique Visitors</span>
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-green-900" id="stat-unique">0</p>
                </div>

                <!-- Desktop -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-orange-900">Desktop</span>
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-orange-900" id="stat-desktop">0</p>
                    <p class="text-xs text-orange-700 mt-1" id="stat-desktop-pct">0% of traffic</p>
                </div>

                <!-- Mobile -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-purple-900">Mobile</span>
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-purple-900" id="stat-mobile">0</p>
                    <p class="text-xs text-purple-700 mt-1" id="stat-mobile-pct">0% of traffic</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daily Visits Trend -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Daily Visits & Inquiry Submissions (Last 7 Days)</h4>
                    <div style="height: 300px; position: relative;">
                        <canvas id="dailyVisitsChart"></canvas>
                    </div>
                </div>

                <!-- Device Distribution -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Device Distribution</h4>
                    <div style="height: 300px; position: relative;">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Most Visited Pages -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Most Visited Pages</h4>
                    <div class="space-y-2" id="most-visited-pages">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Top Browsers -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Top Browsers</h4>
                    <div class="space-y-2" id="top-browsers">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Recent Activity -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Recent Activity</h4>
                    <div class="space-y-2" id="recent-activity">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
{{-- ── End role check ───────────────────────────────────────────────────── --}}

@endsection

@section('scripts')
@if($isAdmin)
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time every second
    setInterval(updateTime, 1000);
    updateTime();

    // Chart instances
    let dailyVisitsChart = null;
    let deviceChart = null;
    let currentPeriod = 'all';

    // Load analytics data
    function loadAnalytics(period) {
        currentPeriod = period;
        
        // Update button states
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('bg-zendo-navy', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        document.getElementById(`btn-${period}`).classList.remove('bg-gray-100', 'text-gray-700');
        document.getElementById(`btn-${period}`).classList.add('bg-zendo-navy', 'text-white');

        // Show loading
        document.getElementById('analytics-loading').classList.remove('hidden');
        document.getElementById('analytics-content').style.opacity = '0.5';

        // Fetch data
        fetch(`{{ route('dashboard.analytics') }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                // Update stats
                document.getElementById('stat-visits').textContent = data.stats.visits;
                document.getElementById('stat-unique').textContent = data.stats.unique;
                document.getElementById('stat-desktop').textContent = data.devices.desktop.count;
                document.getElementById('stat-desktop-pct').textContent = data.devices.desktop.percentage + '% of traffic';
                document.getElementById('stat-mobile').textContent = data.devices.mobile.count;
                document.getElementById('stat-mobile-pct').textContent = data.devices.mobile.percentage + '% of traffic';

                // Update charts
                updateDailyVisitsChart(data.charts.daily);
                updateDeviceChart(data.charts.device);

                // Update details
                updateMostVisitedPages(data.details.most_visited);
                updateTopBrowsers(data.details.top_browsers);
                updateRecentActivity(data.details.recent_activity);

                // Hide loading
                document.getElementById('analytics-loading').classList.add('hidden');
                document.getElementById('analytics-content').style.opacity = '1';
            })
            .catch(error => {
                console.error('Error loading analytics:', error);
                document.getElementById('analytics-loading').classList.add('hidden');
                document.getElementById('analytics-content').style.opacity = '1';
            });
    }

    // Update Daily Visits Chart
    function updateDailyVisitsChart(chartData) {
        const ctx = document.getElementById('dailyVisitsChart');
        
        if (dailyVisitsChart) {
            dailyVisitsChart.destroy();
        }

        dailyVisitsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Page Visits',
                        data: chartData.visits,
                        borderColor: '#B39359',
                        backgroundColor: 'rgba(179, 147, 89, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#B39359',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Inquiry Submissions',
                        data: chartData.inquiries,
                        borderColor: '#0B2C3D',
                        backgroundColor: 'rgba(11, 44, 61, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0B2C3D',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0B2C3D',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6B7280'
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Update Device Chart
    function updateDeviceChart(chartData) {
        const ctx = document.getElementById('deviceChart');
        
        if (deviceChart) {
            deviceChart.destroy();
        }

        deviceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels.length > 0 ? chartData.labels : ['No Data'],
                datasets: [{
                    data: chartData.data.length > 0 ? chartData.data : [1],
                    backgroundColor: chartData.data.length > 0 ? [
                        '#3B82F6', // Blue for Desktop
                        '#10B981', // Green for Mobile
                        '#F59E0B', // Orange for Tablet
                    ] : ['#E5E7EB'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13
                            },
                            color: '#374151',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0B2C3D',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Update Most Visited Pages
    function updateMostVisitedPages(pages) {
        const container = document.getElementById('most-visited-pages');
        
        if (pages.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No data yet</p>
                </div>
            `;
            return;
        }

        container.innerHTML = pages.map(page => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${page.name}</p>
                </div>
                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                    ${page.visits}
                </span>
            </div>
        `).join('');
    }

    // Update Top Browsers
    function updateTopBrowsers(browsers) {
        const container = document.getElementById('top-browsers');
        
        if (browsers.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No data yet</p>
                </div>
            `;
            return;
        }

        container.innerHTML = browsers.map(browser => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${browser.name}</p>
                </div>
                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                    ${browser.count}
                </span>
            </div>
        `).join('');
    }

    // Update Recent Activity
    function updateRecentActivity(activities) {
        const container = document.getElementById('recent-activity');
        
        if (activities.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No activity yet</p>
                </div>
            `;
            return;
        }

        container.innerHTML = activities.map(visit => `
            <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <p class="text-sm font-medium text-gray-900 truncate">${visit.page_name}</p>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xs text-gray-500 capitalize">${visit.device_type}</span>
                    <span class="text-xs text-gray-400">${visit.time_ago}</span>
                </div>
            </div>
        `).join('');
    }

    // Load initial data on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAnalytics('all');
    });
</script>
@endif
@endsection
