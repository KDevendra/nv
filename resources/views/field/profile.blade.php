@extends('layouts.field')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">My Profile</h2>
            <p class="text-gray-500 text-sm mt-1">Manage your account details and security settings</p>
        </div>
        <a href="{{ route('field.dashboard') }}"
            class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left column: account details + reporting info -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Account Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-heading font-semibold text-zendo-navy mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Account Details
                </h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Full Name</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Email</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-900 break-all">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Role</dt>
                        <dd class="mt-0.5">
                            @if($user->role === 'supply_head')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Supply Head
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Field Officer
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Status</dt>
                        <dd class="mt-0.5">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Member Since</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Last Updated</dt>
                        <dd class="mt-0.5 text-sm text-gray-600">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Reporting To (Field Officer only) -->
            @if($user->isFieldOfficer())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-base font-heading font-semibold text-zendo-navy mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Reporting To
                    </h3>
                    @if($user->supplyHead)
                        <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="w-11 h-11 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-base">{{ substr($user->supplyHead->name, 0, 1) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->supplyHead->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->supplyHead->email }}</p>
                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Supply Head
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p class="text-sm text-gray-400">No supply head assigned yet.</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- My Team (Supply Head only) -->
            @if($user->isSupplyHead())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-heading font-semibold text-zendo-navy flex items-center">
                            <svg class="w-4 h-4 mr-2 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            My Team
                        </h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            {{ $user->fieldOfficers->count() }}
                        </span>
                    </div>
                    @if($user->fieldOfficers->count() > 0)
                        <div class="divide-y divide-gray-50">
                            @foreach($user->fieldOfficers as $officer)
                                <div class="flex items-center justify-between px-5 py-3">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-semibold">{{ substr($officer->name, 0, 1) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $officer->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $officer->email }}</p>
                                        </div>
                                    </div>
                                    @if($officer->is_active)
                                        <span class="ml-2 flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="ml-2 flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-6 text-center">
                            <p class="text-sm text-gray-400">No field officers assigned yet.</p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Right column: account management info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Profile Management Notice -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-base font-heading font-semibold text-zendo-navy">Profile Management</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Information about your account settings and access.</p>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Profile Changes Info -->
                        <div class="flex items-start space-x-3 text-sm bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-blue-900 mb-1">Profile Changes</p>
                                <p class="text-sm text-blue-700">To update your profile information or change your password, please contact your administrator.</p>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="flex items-start space-x-3 text-sm bg-amber-50 border border-amber-100 rounded-lg p-4">
                            <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-amber-900 mb-1">Password Security</p>
                                <p class="text-sm text-amber-700">If you need to reset your password or suspect unauthorized access, immediately contact your administrator.</p>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Actions</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <a href="{{ route('field.dashboard') }}" 
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-zendo-gold hover:bg-gray-50 transition-all group">
                                    <div class="w-10 h-10 bg-zendo-gold bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-all">
                                        <svg class="w-5 h-5 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Dashboard</p>
                                        <p class="text-xs text-gray-500">View overview</p>
                                    </div>
                                </a>

                                @if($user->isFieldOfficer())
                                <a href="{{ route('field.properties.index') }}" 
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-zendo-gold hover:bg-gray-50 transition-all group">
                                    <div class="w-10 h-10 bg-blue-500 bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-all">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">My Entries</p>
                                        <p class="text-xs text-gray-500">View submissions</p>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
