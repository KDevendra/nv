@extends('layouts.owner')

@section('title', 'Property Owner Dashboard - ZendoIndia')

@section('content')
<div class="space-y-6">
    <!-- Header banner -->
    <div class="bg-gradient-to-r from-zendo-navy to-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-zendo-gold/20 text-zendo-gold text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                    Property Owner Portal
                </span>
                <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-gray-300 text-sm mt-1">
                    Manage your commercial and warehousing property listings and inquiries.
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-500">Account Type</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-zendo-gold flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-zendo-navy">Property Owner</p>
            <p class="text-xs text-gray-400 mt-1">Registered Owner</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-500">Status</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-emerald-600">Active</p>
            <p class="text-xs text-gray-400 mt-1">Owner access enabled</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-500">Email</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-zendo-navy truncate">{{ auth()->user()->email }}</p>
            <p class="text-xs text-gray-400 mt-1">Primary email address</p>
        </div>
    </div>
</div>
@endsection
