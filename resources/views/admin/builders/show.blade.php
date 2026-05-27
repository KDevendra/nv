@extends('layouts.admin')

@section('title', $builder->name . ' - ZendoIndia Admin')

@section('page-title', 'Builder Details')
@section('page-description', 'View builder information and properties')

@section('content')
    <div class="max-w-5xl space-y-6">
        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.builders.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-zendo-gold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Builders
            </a>
            <div class="flex space-x-3">
                @canDo('builders.edit')
                <a href="{{ route('admin.builders.edit', $builder) }}"
                    class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white rounded-lg hover:bg-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Builder
                </a>
            </div>
        </div>

        <!-- Builder Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    @if ($builder->logo)
                        <img src="{{ asset('storage/' . $builder->logo) }}" alt="{{ $builder->name }}"
                            class="w-24 h-24 object-cover rounded-lg mr-6">
                    @else
                        <div class="w-24 h-24 bg-zendo-gold rounded-lg mr-6 flex items-center justify-center">
                            <span class="text-white text-3xl font-bold">{{ $builder->initials }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-heading font-bold text-zendo-navy mb-2">{{ $builder->name }}</h1>
                        <p class="text-gray-600">{{ $builder->slug }}</p>
                        @if ($builder->established_year)
                            <p class="text-sm text-gray-500 mt-1">Established: {{ $builder->established_year }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $builder->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $builder->status ? 'Active' : 'Inactive' }}
                    </span>
                    @if ($builder->is_verified)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Verified
                        </span>
                    @endif
                </div>
            </div>

            @if ($builder->description)
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">About</h3>
                    <p class="text-gray-900">{{ $builder->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($builder->email)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <a href="mailto:{{ $builder->email }}"
                            class="text-base font-medium text-blue-600 hover:text-blue-800">
                            {{ $builder->email }}
                        </a>
                    </div>
                @endif

                @if ($builder->phone)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Phone</p>
                        <a href="tel:{{ $builder->phone }}"
                            class="text-base font-medium text-blue-600 hover:text-blue-800">
                            {{ $builder->phone }}
                        </a>
                    </div>
                @endif

                @if ($builder->website)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Website</p>
                        <a href="{{ $builder->website }}" target="_blank"
                            class="text-base font-medium text-blue-600 hover:text-blue-800">
                            {{ $builder->website }}
                        </a>
                    </div>
                @endif

                @if ($builder->address)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Address</p>
                        <p class="text-base font-medium text-gray-900">{{ $builder->address }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Properties</p>
                    <p class="text-base font-medium text-gray-900">{{ $builder->properties->count() }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Created At</p>
                    <p class="text-base font-medium text-gray-900">{{ $builder->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        @if($builder->youtube_url || $builder->video_path)
        <!-- Video & Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-4">Video & Media</h2>
            <div class="space-y-4">
                @if($builder->youtube_url)
                <div>
                    <p class="text-sm text-gray-600 mb-2">YouTube</p>
                    <a href="{{ $builder->youtube_url }}" target="_blank"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 break-all">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        {{ $builder->youtube_url }}
                    </a>
                    @php
                        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $builder->youtube_url, $m);
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

                @if($builder->video_path)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Uploaded Video</p>
                    <video controls class="w-full max-w-xl rounded-lg border border-gray-200">
                        <source src="{{ asset('storage/' . $builder->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Common Details -->
        @if($builder->amenities->count() || $builder->projectStatuses->count())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-5">Common Details</h2>

            @if($builder->amenities->count())
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Amenities Offered</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($builder->amenities as $amenity)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-zendo-gold/10 text-zendo-navy font-medium">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $amenity->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($builder->projectStatuses->count())
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Project Statuses Available</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($builder->projectStatuses as $ps)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $ps->tag_color ? '' : 'bg-blue-100 text-blue-800' }}"
                        @if($ps->tag_color) style="background-color: {{ $ps->tag_color }}20; color: {{ $ps->tag_color }}" @endif>
                        {{ $ps->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Associated Properties -->
        @if ($builder->properties->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-4">
                    Properties (Showing latest 10)
                </h2>
                <div class="space-y-3">
                    @foreach ($builder->properties as $property)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center flex-1">
                                @if ($property->mainImage)
                                    <img src="{{ asset($property->mainImage->image_path) }}"
                                        alt="{{ $property->mainImage->alt_tag ?? $property->title }}" class="w-16 h-16 object-cover rounded-lg mr-4">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-base font-medium text-gray-900">{{ $property->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $property->location->name }},
                                        {{ $property->city->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span
                                    class="text-sm font-semibold text-zendo-gold">₹{{ number_format($property->price) }}</span>
                                <a href="{{ route('admin.properties.show', $property) }}"
                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                @endCanDo
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($builder->properties->count() >= 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Showing latest 10 properties. Total:
                            {{ $builder->properties->count() }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <p class="text-lg font-medium text-gray-900">No properties found</p>
                <p class="mt-1 text-gray-600">This builder doesn't have any properties yet.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-heading font-semibold text-zendo-navy mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                @canDo('builders.edit')
                <form action="{{ route('admin.builders.toggle-status', $builder) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $builder->status ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>

                <form action="{{ route('admin.builders.toggle-verified', $builder) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $builder->is_verified ? 'Unverify' : 'Verify' }}
                    </button>
                </form>
                @endCanDo

                @canDo('builders.delete')
                <form action="{{ route('admin.builders.destroy', $builder) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this builder? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        {{ $builder->properties->count() > 0 ? 'disabled' : '' }}>
                        Delete Builder
                    </button>
                </form>
                @endCanDo
                @if ($builder->properties->count() > 0)
                    <p class="text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cannot delete builder with associated properties
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
