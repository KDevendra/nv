@extends('layouts.admin')

@section('title', 'View Hero Section')
@section('page-title', 'Hero Section Details')
@section('page-description', 'View hero section information')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Hero Section Details</h2>
                <div class="flex items-center space-x-3">
                    @canDo('hero-sections.edit')
<a href="{{ route('admin.hero-sections.edit', $heroSection) }}" 
                       class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Hero Section
                    </a>
                    <a href="{{ route('admin.hero-sections.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-8">
            <!-- Status and Meta Info -->
            <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $heroSection->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $heroSection->status ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-sm text-gray-600">Sort Order: {{ $heroSection->sort_order }}</span>
                </div>
                <div class="text-sm text-gray-500">
                    <p>Created: {{ $heroSection->created_at->format('M d, Y \a\t g:i A') }}</p>
                    <p>Updated: {{ $heroSection->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <!-- Media Preview Section -->
            @if($heroSection->video_path || $heroSection->poster_image)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Media Preview</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    @if($heroSection->video_path)
                    <div class="relative bg-black rounded-lg overflow-hidden">
                        <video class="w-full" controls poster="{{ $heroSection->poster_image ? asset($heroSection->poster_image) : '' }}">
                            <source src="{{ asset($heroSection->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="mt-3 flex items-center space-x-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span>Video with {{ $heroSection->poster_image ? 'poster image' : 'no poster' }}</span>
                    </div>
                    @elseif($heroSection->poster_image)
                    <div class="relative rounded-lg overflow-hidden">
                        <img src="{{ asset($heroSection->poster_image) }}" alt="Poster" class="w-full">
                    </div>
                    <div class="mt-3 flex items-center space-x-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Poster image only</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Hero Section Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Title</h3>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg text-xl font-medium">{{ $heroSection->title }}</p>
                    </div>

                    @if($heroSection->highlight_text)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Highlight Text</h3>
                        <p class="text-zendo-gold bg-gray-50 p-4 rounded-lg text-xl font-semibold">{{ $heroSection->highlight_text }}</p>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Media Files</h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                            @if($heroSection->video_path)
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Video: {{ basename($heroSection->video_path) }}</span>
                            </div>
                            @else
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-gray-500 italic">No video uploaded</span>
                            </div>
                            @endif

                            @if($heroSection->poster_image)
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">Poster: {{ basename($heroSection->poster_image) }}</span>
                            </div>
                            @else
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-gray-500 italic">No poster image uploaded</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @if($heroSection->description)
                            <p class="text-gray-700 leading-relaxed">{{ $heroSection->description }}</p>
                            @else
                            <p class="text-gray-500 italic">No description provided</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Display Settings</h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Status:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $heroSection->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $heroSection->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Sort Order:</span>
                                <span class="text-gray-900 font-medium">{{ $heroSection->sort_order }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Website Preview</h3>
                <div class="bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
                    <div class="relative bg-gradient-to-br from-zendo-navy to-gray-900 rounded-lg overflow-hidden shadow-xl">
                        @if($heroSection->video_path)
                        <video class="w-full h-64 object-cover opacity-50" muted poster="{{ $heroSection->poster_image ? asset($heroSection->poster_image) : '' }}">
                            <source src="{{ asset($heroSection->video_path) }}" type="video/mp4">
                        </video>
                        @elseif($heroSection->poster_image)
                        <img src="{{ asset($heroSection->poster_image) }}" alt="Poster" class="w-full h-64 object-cover opacity-50">
                        @else
                        <div class="w-full h-64 bg-gradient-to-br from-zendo-navy to-gray-900"></div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white px-6">
                                <h1 class="text-4xl font-heading font-bold mb-2">
                                    {{ $heroSection->title }}
                                </h1>
                                @if($heroSection->highlight_text)
                                <p class="text-2xl text-zendo-gold font-semibold mb-4">{{ $heroSection->highlight_text }}</p>
                                @endif
                                @if($heroSection->description)
                                <p class="text-lg text-gray-200 max-w-2xl mx-auto">{{ $heroSection->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    @canDo('hero-sections.delete')
<form action="{{ route('admin.hero-sections.toggle-status', $heroSection) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 {{ $heroSection->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-lg transition-colors">
                            {{ $heroSection->status ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.hero-sections.edit', $heroSection) }}" 
                       class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Hero Section
                    </a>
@endCanDo
                    <form action="{{ route('admin.hero-sections.destroy', $heroSection) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this hero section? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Hero Section
                        </button>
                    </form>
@endCanDo
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
