@extends('layouts.user')

@section('title', 'Profile Settings - ZendoIndia')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-heading text-zendo-navy mb-2">Profile Settings</h1>
        <p class="text-gray-600">Manage your account information and preferences.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-zendo-gold rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h2 class="text-lg font-heading text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Member since</span>
                        <span class="font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-heading text-zendo-navy mb-6 pb-3 border-b border-gray-100">Personal Information</h2>
                
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" value="{{ $user->email }}" readonly disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">Email cannot be changed. Contact support if you need to update it.</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="Enter your phone number"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full px-6 py-3 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Account Security (Optional - for future) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-heading text-zendo-navy mb-6 pb-3 border-b border-gray-100">Account Security</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Password</p>
                            <p class="text-xs text-gray-500">Last updated {{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                        <button type="button" disabled
                            class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Change Password
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">Password management will be available in future updates. Contact support for password reset.</p>
                </div>
            </div>

            <!-- Delete Account (Optional - for future) -->
            <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
                <h2 class="text-xl font-heading text-red-600 mb-6 pb-3 border-b border-red-100">Danger Zone</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Delete Account</p>
                            <p class="text-xs text-gray-500 mt-1">Permanently delete your account and all associated data. This action cannot be undone.</p>
                        </div>
                        <button type="button" disabled
                            class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed whitespace-nowrap">
                            Delete Account
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">Account deletion will be available in future updates. Contact support if you wish to delete your account.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
