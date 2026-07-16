@extends('layouts.user')

@section('title', 'Inquiry Details - ZendoIndia')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.inquiries') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-zendo-navy transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Inquiries
        </a>
    </div>

    <!-- Inquiry Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-2xl font-heading text-zendo-navy mb-2">
                    @if($inquiry->property)
                        {{ $inquiry->property->name ?? 'Property Inquiry' }}
                    @elseif($inquiry->property_entry_code && $inquiry->propertyEntry)
                        {{ $inquiry->propertyEntry->property_name ?? $inquiry->propertyEntry->facility_type ?? 'Property Entry Inquiry' }}
                    @else
                        General Inquiry
                    @endif
                </h1>
                <p class="text-sm text-gray-500">Submitted on {{ $inquiry->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $inquiry->status_badge }}">
                {{ $inquiry->formatted_status }}
            </span>
        </div>

        @if($inquiry->property_entry_code)
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-1">Property Code</p>
            <p class="text-lg font-mono font-bold text-zendo-navy">{{ $inquiry->property_entry_code }}</p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-heading text-zendo-navy mb-4 pb-3 border-b border-gray-100">Your Contact Information</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Phone</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->phone }}</p>
                </div>

                @if($inquiry->email)
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Email</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->email }}</p>
                </div>
                @endif

                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Inquiry Type</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->formatted_inquiry_type }}</p>
                </div>
            </div>
        </div>

        <!-- Property Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-heading text-zendo-navy mb-4 pb-3 border-b border-gray-100">Property Information</h2>
            
            @if($inquiry->propertyEntry)
            <div class="space-y-4">
                @if($inquiry->propertyEntry->facility_type)
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Facility Type</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->propertyEntry->facility_type }}</p>
                </div>
                @endif

                @if($inquiry->propertyEntry->nearest_city)
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Location</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->propertyEntry->nearest_city }}</p>
                </div>
                @endif

                @if($inquiry->propertyEntry->plot_area)
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Plot Area</p>
                    <p class="text-sm font-medium text-gray-900">{{ number_format($inquiry->propertyEntry->plot_area) }} {{ str_replace('_', ' ', $inquiry->propertyEntry->area_unit ?? 'sq ft') }}</p>
                </div>
                @endif

                <div class="pt-2">
                    <a href="{{ route('property-entries.show', $inquiry->property_entry_code) }}" 
                        class="inline-flex items-center text-sm font-medium text-zendo-gold hover:text-zendo-navy transition-colors">
                        View Property Details →
                    </a>
                </div>
            </div>
            @elseif($inquiry->property)
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 mb-1">Property Name</p>
                    <p class="text-sm font-medium text-gray-900">{{ $inquiry->property->name }}</p>
                </div>

                <div class="pt-2">
                    <a href="{{ route('properties.show', $inquiry->property) }}" 
                        class="inline-flex items-center text-sm font-medium text-zendo-gold hover:text-zendo-navy transition-colors">
                        View Property Details →
                    </a>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-500">General inquiry - no specific property attached.</p>
            @endif
        </div>
    </div>

    <!-- Message -->
    @if($inquiry->message)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <h2 class="text-lg font-heading text-zendo-navy mb-4 pb-3 border-b border-gray-100">Your Message</h2>
        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
    </div>
    @endif

    <!-- Status Timeline (Optional - for future enhancement) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <h2 class="text-lg font-heading text-zendo-navy mb-4 pb-3 border-b border-gray-100">Inquiry Timeline</h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-green-500 mt-2 mr-4"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Inquiry Submitted</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>

            @if($inquiry->status !== 'pending')
            <div class="flex items-start">
                <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2 mr-4"></div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Status Updated: {{ $inquiry->formatted_status }}</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->updated_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
