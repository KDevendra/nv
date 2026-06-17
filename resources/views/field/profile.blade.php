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

        <!-- Right column: edit forms -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Update Profile Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-base font-heading font-semibold text-zendo-navy">Profile Information</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Update your name and email address.</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('field.profile.update') }}" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('name') border-red-500 @enderror"
                                required autofocus autocomplete="name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email (Username)</label>
                            <input type="text"
                                value="{{ $user->email }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                                disabled>
                            <p class="mt-1 text-xs text-gray-400">Email is your username and cannot be changed.</p>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                            @if(session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                    x-init="setTimeout(() => show = false, 3000)"
                                    class="text-sm text-green-600 font-medium">
                                    Profile updated successfully!
                                </p>
                            @endif
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-zendo-gold text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-base font-heading font-semibold text-zendo-navy">Change Password</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Use a strong, unique password to keep your account secure.</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('field.profile.password') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current Password *</label>
                            <input type="password" name="current_password" id="current_password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('current_password', 'updatePassword') border-red-500 @enderror"
                                autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password *</label>
                            <input type="password" name="password" id="new_password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('password', 'updatePassword') border-red-500 @enderror"
                                autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password *</label>
                            <input type="password" name="password_confirmation" id="new_password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                                autocomplete="new-password">
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                            @if(session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                    x-init="setTimeout(() => show = false, 3000)"
                                    class="text-sm text-green-600 font-medium">
                                    Password updated successfully!
                                </p>
                            @endif
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-zendo-navy text-white text-sm font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
