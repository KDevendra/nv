@extends('layouts.admin')

@section('title', 'View Property')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-heading text-zendo-navy font-semibold">Property Details</h2>
                    <div class="flex items-center space-x-3">
                        @canDo('properties.edit')
                        <a href="{{ route('admin.properties.edit', $property) }}"
                            class="inline-flex items-center px-4 py-2 text-sm bg-zendo-gold text-white rounded-lg hover:bg-opacity-90 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.properties.index') }}"
                            class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to List
                        </a>
                        @endCanDo
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Property Title & Status -->
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-heading font-bold text-zendo-navy mb-2">{{ $property->title }}</h3>
                            <p class="text-gray-600">{{ $property->location?->name ?? '-' }}, {{ $property->city?->name ?? '-' }}</p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $property->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if ($property->is_featured)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    Featured
                                </span>
                            @endif
                            @if ($property->is_verified)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Verified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Property Images -->
                @if ($property->images->count() > 0)
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Property Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($property->images as $image)
                                <div class="relative">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $image->alt_tag ?? $property->title }}"
                                        class="w-full h-48 object-cover rounded-lg">
                                    @if ($image->image_type === 'main')
                                        <span
                                            class="absolute top-2 left-2 bg-zendo-gold text-white text-xs px-2 py-1 rounded">Main</span>
                                    @endif
                                    @if ($image->alt_tag)
                                        <span class="block mt-1 text-xs text-gray-500 truncate">{{ $image->alt_tag }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Video & Media -->
                @if($property->youtube_url || $property->video_path)
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Video & Media</h3>
                    <div class="space-y-4">
                        @if($property->youtube_url)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">YouTube</p>
                            <a href="{{ $property->youtube_url }}" target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 break-all">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                {{ $property->youtube_url }}
                            </a>
                            @php
                                preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $property->youtube_url, $m);
                                $ytId = $m[1] ?? null;
                            @endphp
                            @if($ytId)
                            <div class="mt-3 aspect-video rounded-lg overflow-hidden max-w-xl">
                                <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $ytId }}"
                                    frameborder="0" allowfullscreen></iframe>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($property->video_path)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Uploaded Video</p>
                            <video controls class="w-full max-w-xl rounded-lg border border-gray-200">
                                <source src="{{ asset('storage/' . $property->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Basic Information -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Property Type</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->propertyType?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">BHK</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->bhk?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Project Status</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->projectStatus?->name ?? '-' }}</p>
                        </div>
                        @if ($property->builder)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Builder</p>
                                <p class="text-base font-medium text-gray-900">{{ $property->builder->name }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Created By</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->user?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Views</p>
                            <p class="text-base font-medium text-gray-900">{{ number_format($property->views_count) }}</p>
                        </div>
                    </div>

                    @if ($property->description)
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 mb-2">Description</p>
                            <p class="text-base text-gray-900">{{ $property->description }}</p>
                        </div>
                    @endif

                    {{-- Hidden Details — always shown to admin --}}
                    <div class="mt-6 border border-yellow-200 bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-yellow-800">Hidden Details (Admin Only)</p>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $property->show_hidden_details ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $property->show_hidden_details ? 'Visible on public page' : 'Hidden from public' }}
                            </span>
                        </div>
                        @if ($property->hidden_details)
                            <div class="prose prose-sm max-w-none text-gray-800">{!! $property->hidden_details !!}</div>
                        @else
                            <p class="text-sm text-yellow-700 italic">No hidden details added yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Pricing & Area -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Pricing & Area</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Price</p>
                            <p class="text-2xl font-bold text-zendo-gold">₹{{ number_format($property->price) }}</p>
                        </div>
                        @if ($property->price_per_sqft)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Price per Sqft</p>
                                <p class="text-xl font-semibold text-gray-900">
                                    ₹{{ number_format($property->price_per_sqft) }}</p>
                            </div>
                        @endif
                        @if ($property->carpet_area)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Carpet Area</p>
                                <p class="text-base font-medium text-gray-900">{{ number_format($property->carpet_area) }}
                                    sqft</p>
                            </div>
                        @endif
                        @if ($property->built_up_area)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Built-up Area</p>
                                <p class="text-base font-medium text-gray-900">
                                    {{ number_format($property->built_up_area) }} sqft</p>
                            </div>
                        @endif
                        @if ($property->plot_area)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Plot Area</p>
                                <p class="text-base font-medium text-gray-900">{{ number_format($property->plot_area) }}
                                    sqft</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Specifications -->
                @if ($property->specifications)
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Specifications</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            @if ($property->specifications->total_floors)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Floors</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->total_floors }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->floor_number !== null)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Floor Number</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->floor_number }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->bedrooms)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Bedrooms</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->bedrooms }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->bathrooms)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Bathrooms</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->bathrooms }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->balconies)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Balconies</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->balconies }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->parking_spaces)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Parking Spaces</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->parking_spaces }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->furnishing_status)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Furnishing Status</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', $property->specifications->furnishing_status)) }}
                                    </p>
                                </div>
                            @endif
                            @if ($property->specifications->facing)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Facing</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', $property->specifications->facing)) }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->age_of_property)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Age of Property</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->age_of_property }} years</p>
                                </div>
                            @endif
                            @if ($property->specifications->possession_date)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Possession Date</p>
                                    <p class="text-base font-medium text-gray-900">
                                        {{ $property->specifications->possession_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                            @if ($property->specifications->rera_id)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">RERA ID</p>
                                    <p class="text-base font-medium text-gray-900">{{ $property->specifications->rera_id }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Amenities -->
                @if ($property->amenities->count() > 0)
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($property->amenities as $amenity)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-zendo-gold" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Location Details -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Location Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">City</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->city?->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Location</p>
                            <p class="text-base font-medium text-gray-900">{{ $property->location?->name ?? '-' }}</p>
                        </div>
                        @if ($property->address)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Address</p>
                                <p class="text-base font-medium text-gray-900">{{ $property->address }}</p>
                            </div>
                        @endif
                        @if ($property->latitude && $property->longitude)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Coordinates</p>
                                <p class="text-base font-medium text-gray-900">{{ $property->latitude }},
                                    {{ $property->longitude }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Publishing Information -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Publishing Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Created At</p>
                            <p class="text-base font-medium text-gray-900">
                                {{ $property->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                            <p class="text-base font-medium text-gray-900">
                                {{ $property->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if ($property->published_at)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Published At</p>
                                <p class="text-base font-medium text-gray-900">
                                    {{ $property->published_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        @canDo('properties.edit')
                        <form action="{{ route('admin.properties.toggle-status', $property) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                {{ $property->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.properties.toggle-featured', $property) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                {{ $property->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.properties.toggle-verified', $property) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                {{ $property->is_verified ? 'Unverify' : 'Verify' }}
                            </button>
                        </form>
                        @endCanDo

                        @canDo('properties.delete')
                        <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Delete Property
                            </button>
                        </form>
                        @endCanDo
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
