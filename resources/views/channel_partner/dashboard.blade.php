@extends('layouts.channel_partner')

@section('title', 'Channel Partner Dashboard - ZendoIndia')

@section('content')
<div class="space-y-6">
    <!-- Header banner -->
    <div class="bg-gradient-to-r from-zendo-navy to-slate-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-zendo-gold/20 text-zendo-gold text-xs font-semibold rounded-full uppercase tracking-wider mb-2">
                    Channel Partner Portal
                </span>
                <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="text-gray-300 text-sm mt-1">
                    Manage your partner portfolio, property listings, and deals seamlessly.
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-zendo-navy">Channel Partner</p>
            <p class="text-xs text-gray-400 mt-1">Registered & Active</p>
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
            <p class="text-xs text-gray-400 mt-1">Partner access enabled</p>
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
