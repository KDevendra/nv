@extends('layouts.admin')

@section('title', $amenity->name . ' - ZendoIndia Admin')

@section('page-title', 'Amenity Details')
@section('page-description', 'View amenity information and associated properties')

@section('content')
<div class="max-w-5xl space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.amenities.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-zendo-gold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Amenities
        </a>
        <div class="flex space-x-3">
            @canDo('amenities.edit')
<a href="{{ route('admin.amenities.edit', $amenity) }}" 
               class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white rounded-lg hover:bg-zendo-navy transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Amenity
            </a>
        </div>
    </div>

    <!-- Amenity Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-zendo-gold rounded-full mr-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">{{ substr($amenity->name, 0, 1) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-heading font-bold text-zendo-navy mb-2">{{ $amenity->name }}</h1>
                    <p class="text-gray-600">{{ $amenity->slug }}</p>
                </div>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $amenity->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $amenity->status ? 'Active' : 'Inactive' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($amenity->category === 'basic') bg-blue-100 text-blue-800
                    @elseif($amenity->category === 'security') bg-red-100 text-red-800
                    @elseif($amenity->category === 'recreation') bg-green-100 text-green-800
                    @elseif($amenity->category === 'convenience') bg-purple-100 text-purple-800
                    @else bg-teal-100 text-teal-800
                    @endif">
                    {{ ucwords(str_replace('_', ' ', $amenity->category)) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-200">
            <div>
                <p class="text-sm text-gray-600 mb-1">Display Order</p>
                <p class="text-base font-medium text-gray-900">{{ $amenity->display_order ?? 'Not set' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Properties</p>
                <p class="text-base font-medium text-gray-900">{{ $amenity->properties->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Created At</p>
                <p class="text-base font-medium text-gray-900">{{ $amenity->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Associated Properties -->
    @if($amenity->properties->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-4">
            Associated Properties (Showing latest 10)
        </h2>
        <div class="space-y-3">
            @foreach($amenity->properties as $property)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center flex-1">
                        @if($property->mainImage)
                            <img src="{{ asset($property->mainImage->image_path) }}" 
                                 alt="{{ $property->mainImage->alt_tag ?? $property->title }}" 
                                 class="w-16 h-16 object-cover rounded-lg mr-4">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-base font-medium text-gray-900">{{ $property->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $property->location->name }}, {{ $property->city->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-zendo-gold">₹{{ number_format($property->price) }}</span>
                        <a href="{{ route('admin.properties.show', $property) }}" 
                           class="text-blue-600 hover:text-blue-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
@endCanDo
                    </div>
                </div>
            @endforeach
        </div>
        @if($amenity->properties->count() >= 10)
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Showing latest 10 properties. Total: {{ $amenity->properties->count() }}</p>
            </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <p class="text-lg font-medium text-gray-900">No properties associated</p>
        <p class="mt-1 text-gray-600">This amenity is not yet used in any properties.</p>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            @canDo('amenities.delete')
<form action="{{ route('admin.amenities.toggle-status', $amenity) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    {{ $amenity->status ? 'Deactivate' : 'Activate' }}
                </button>
            </form>

            <form action="{{ route('admin.amenities.destroy', $amenity) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this amenity? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        {{ $amenity->properties->count() > 0 ? 'disabled' : '' }}>
                    Delete Amenity
                </button>
            </form>
@endCanDo
            @if($amenity->properties->count() > 0)
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cannot delete amenity with associated properties
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
