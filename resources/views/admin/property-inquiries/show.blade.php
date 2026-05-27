@extends('layouts.admin')

@section('title', 'Property Inquiry Details - ZendoIndia Admin')
@section('page-title', 'Property Inquiry Details')
@section('page-description', 'View and manage property inquiry information')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Property Inquiry #{{ $propertyInquiry->id }}</h2>
                <a href="{{ route('admin.property-inquiries.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Property Inquiries
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-zendo-gold rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-semibold">{{ $propertyInquiry->initials }}</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $propertyInquiry->name }}</h4>
                                    <p class="text-sm text-gray-600">Customer</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:{{ $propertyInquiry->email }}" class="text-zendo-navy hover:text-zendo-gold">{{ $propertyInquiry->email }}</a>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <a href="tel:{{ $propertyInquiry->phone }}" class="text-zendo-navy hover:text-zendo-gold">{{ $propertyInquiry->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Information -->
                    @if($propertyInquiry->property)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-4">
                                @if($propertyInquiry->property->mainImage)
                                    <img src="{{ asset($propertyInquiry->property->mainImage->image_path) }}" 
                                         alt="{{ $propertyInquiry->property->mainImage->alt_tag ?? $propertyInquiry->property->title }}" 
                                         class="w-20 h-20 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-1">{{ $propertyInquiry->property->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $propertyInquiry->property->location->name ?? '' }}@if($propertyInquiry->property->location && $propertyInquiry->property->city), @endif{{ $propertyInquiry->property->city->name ?? '' }}</p>
                                    <p class="text-sm font-semibold text-zendo-gold mt-1">{{ $propertyInquiry->property->formatted_price }}</p>
                                </div>
                                <a href="{{ route('admin.properties.show', $propertyInquiry->property) }}" 
                                   class="text-blue-600 hover:text-blue-900 ml-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Property Type</span>
                                        <p class="text-gray-900 font-medium">{{ $propertyInquiry->property->propertyType->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm text-gray-600">Inquiry Type</span>
                                        <p class="text-gray-900 font-medium">{{ $propertyInquiry->formatted_inquiry_type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Property Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-600 text-center">The property associated with this inquiry has been deleted.</p>
                        </div>
                    </div>
                    @endif

                    <!-- Message -->
                    @if($propertyInquiry->message)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed">{{ $propertyInquiry->message }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Inquiry Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Inquiry Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Submitted:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $propertyInquiry->created_at->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Time:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $propertyInquiry->created_at->format('h:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Days ago:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $propertyInquiry->created_at->diffForHumans() }}</span>
                                </div>
                                @if($propertyInquiry->ip_address)
                                <div>
                                    <span class="text-gray-600">IP Address:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $propertyInquiry->ip_address }}</span>
                                </div>
                                @endif
                                @if($propertyInquiry->updated_at != $propertyInquiry->created_at)
                                <div class="md:col-span-2">
                                    <span class="text-gray-600">Last updated:</span>
                                    <span class="text-gray-900 font-medium ml-2">{{ $propertyInquiry->updated_at->diffForHumans() }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Management -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Management</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $propertyInquiry->status_badge }}">
                                {{ $propertyInquiry->formatted_status }}
                            </span>
                        </div>

                        @canDo('property-inquiries.delete')
<form action="{{ route('admin.property-inquiries.update-status', $propertyInquiry) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                    <option value="pending" {{ $propertyInquiry->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="contacted" {{ $propertyInquiry->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="interested" {{ $propertyInquiry->status === 'interested' ? 'selected' : '' }}>Interested</option>
                                    <option value="not_interested" {{ $propertyInquiry->status === 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                    <option value="closed" {{ $propertyInquiry->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="mailto:{{ $propertyInquiry->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Email
                            </a>
                            <a href="tel:{{ $propertyInquiry->phone }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Call Customer
                            </a>
                            @if($propertyInquiry->property)
                            <a href="{{ route('properties.show', $propertyInquiry->property->slug) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                View Property Page
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-white border border-red-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-red-700 mb-4">Danger Zone</h3>
                        <form action="{{ route('admin.property-inquiries.destroy', $propertyInquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Inquiry
                            </button>
                        </form>
@endCanDo
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
