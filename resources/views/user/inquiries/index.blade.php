@extends('layouts.user')

@section('title', 'My Inquiries - ZendoIndia')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-heading text-zendo-navy mb-2">My Inquiries</h1>
        <p class="text-gray-600">View and manage all your property inquiries in one place.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('user.inquiries') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search inquiries..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="interested" {{ request('status') === 'interested' ? 'selected' : '' }}>Interested</option>
                    <option value="not_interested" {{ request('status') === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="call_back" {{ request('type') === 'call_back' ? 'selected' : '' }}>Call Back</option>
                    <option value="site_visit" {{ request('type') === 'site_visit' ? 'selected' : '' }}>Site Visit</option>
                    <option value="email_info" {{ request('type') === 'email_info' ? 'selected' : '' }}>Email Info</option>
                    <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                    Apply Filters
                </button>
                <a href="{{ route('user.inquiries') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Inquiries List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        @if($inquiries->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($inquiries as $inquiry)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            @if($inquiry->property_entry_code)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <span class="font-medium">Code:</span>&nbsp;{{ $inquiry->property_entry_code }}
                            </div>
                            @endif

                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $inquiry->created_at->format('M d, Y h:i A') }}
                            </div>

                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $inquiry->formatted_inquiry_type }}
                            </div>

                            @if($inquiry->propertyEntry && $inquiry->propertyEntry->nearest_city)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $inquiry->propertyEntry->nearest_city }}
                            </div>
                            @endif
                        </div>

                        @if($inquiry->message)
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($inquiry->message, 150) }}</p>
                        @endif
                    </div>
                    
                    <a href="{{ route('user.inquiries.show', $inquiry) }}" 
                        class="ml-4 px-4 py-2 text-sm font-medium text-zendo-navy bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors whitespace-nowrap">
                        View Details →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($inquiries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $inquiries->links() }}
        </div>
        @endif

        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inquiries Found</h3>
            <p class="text-gray-600 mb-6">You haven't submitted any property inquiries yet, or your filters returned no results.</p>
            <a href="{{ route('properties') }}" class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                Browse Properties
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
