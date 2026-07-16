@extends('layouts.user')

@section('title', 'My Dashboard - ZendoIndia')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-heading text-zendo-navy mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Here's an overview of your property inquiries and activity.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Inquiries -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Inquiries</p>
                    <p class="text-3xl font-bold text-zendo-navy">{{ $totalInquiries }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingInquiries }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contacted -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Contacted</p>
                    <p class="text-3xl font-bold text-green-600">{{ $contactedInquiries }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-heading text-zendo-navy">Recent Inquiries</h2>
            <a href="{{ route('user.inquiries') }}" class="text-sm font-medium text-zendo-gold hover:text-zendo-navy transition-colors">
                View All →
            </a>
        </div>

        @if($recentInquiries->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($recentInquiries as $inquiry)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-base font-semibold text-gray-900">
                                @if($inquiry->property)
                                    {{ $inquiry->property->name ?? 'Property' }}
                                @elseif($inquiry->property_entry_code && $inquiry->propertyEntry)
                                    {{ $inquiry->propertyEntry->property_name ?? $inquiry->propertyEntry->facility_type ?? 'Property Entry' }}
                                @else
                                    General Inquiry
                                @endif
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inquiry->status_badge }}">
                                {{ $inquiry->formatted_status }}
                            </span>
                        </div>
                        
                        @if($inquiry->property_entry_code)
                        <p class="text-sm text-gray-500 mb-1">
                            <span class="font-medium">Code:</span> {{ $inquiry->property_entry_code }}
                        </p>
                        @endif
                        
                        @if($inquiry->message)
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($inquiry->message, 100) }}</p>
                        @endif
                        
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $inquiry->created_at->format('M d, Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $inquiry->formatted_inquiry_type }}
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.inquiries.show', $inquiry) }}" 
                        class="ml-4 px-4 py-2 text-sm font-medium text-zendo-navy bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inquiries Yet</h3>
            <p class="text-gray-600 mb-6">Start browsing properties and submit inquiries to track them here.</p>
            <a href="{{ route('properties') }}" class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                Browse Properties
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
