@extends('layouts.admin')

@section('title', 'Profile Settings - ZendoIndia Admin')
@section('page-title', 'Profile Settings')
@section('page-description', 'Manage your account information and security settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Profile Information Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-heading text-zendo-navy font-semibold">Profile Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
                </div>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
        <div class="p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Update Password Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-heading text-zendo-navy font-semibold">Update Password</h2>
            <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div class="p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account Card -->
    {{-- <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-red-500">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200">
            <h2 class="text-xl font-heading text-red-700 font-semibold">Delete Account</h2>
            <p class="text-sm text-red-600 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
        <div class="p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div> --}}
</div>
@endsection
