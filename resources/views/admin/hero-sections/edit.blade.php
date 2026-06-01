@extends('layouts.admin')

@section('title', 'Edit Hero Section')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Edit Hero Section</h2>
                <a href="{{ route('admin.hero-sections.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.hero-sections.update', $heroSection) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $heroSection->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Find Your Perfect"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="highlight_text" class="block text-sm font-medium text-gray-700 mb-2">Highlight Text (Gold Color)</label>
                    <input type="text" 
                           name="highlight_text" 
                           id="highlight_text" 
                           value="{{ old('highlight_text', $heroSection->highlight_text) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('highlight_text') border-red-500 @enderror"
                           placeholder="Property">
                    <p class="mt-1 text-sm text-gray-500">This text will appear in gold color</p>
                    @error('highlight_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Discover the best properties to buy, lease, or invest in across India...">{{ old('description', $heroSection->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Video -->
            @if($heroSection->video_path)
            <div class="pt-6 border-t border-gray-200">
                <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Video</label>
                    <video class="w-full max-w-md rounded-lg" controls>
                        <source src="{{ asset($heroSection->video_path) }}" type="video/mp4">
                    </video>
                    <label class="flex items-center mt-3 text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_video" value="1" class="mr-2 rounded border-gray-300">
                        Remove this video
                    </label>
                </div>
            </div>
            @endif

            <!-- Video Upload -->
            <div class="{{ $heroSection->video_path ? '' : 'pt-6 border-t border-gray-200' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $heroSection->video_path ? 'Upload New Video' : 'Background Video' }}
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-zendo-gold transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="video_path" class="relative cursor-pointer bg-white rounded-md font-medium text-zendo-gold hover:text-zendo-navy">
                                <span>Upload a video</span>
                                <input id="video_path" name="video_path" type="file" class="sr-only" accept="video/mp4,video/mov,video/avi,video/wmv">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">MP4, MOV, AVI, WMV up to 50MB</p>
                    </div>
                </div>
                @error('video_path')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Poster -->
            @if($heroSection->poster_image)
            <div>
                <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Poster Image</label>
                    <img src="{{ asset($heroSection->poster_image) }}" alt="Poster" class="w-full max-w-md rounded-lg">
                    <label class="flex items-center mt-3 text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_poster" value="1" class="mr-2 rounded border-gray-300">
                        Remove this poster
                    </label>
                </div>
            </div>
            @endif

            <!-- Poster Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $heroSection->poster_image ? 'Upload New Poster' : 'Poster Image (Fallback)' }}
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-zendo-gold transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="poster_image" class="relative cursor-pointer bg-white rounded-md font-medium text-zendo-gold hover:text-zendo-navy">
                                <span>Upload an image</span>
                                <input id="poster_image" name="poster_image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB</p>
                    </div>
                </div>
                @error('poster_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', $heroSection->sort_order) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('sort_order') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" 
                           name="status" 
                           id="status" 
                           value="1"
                           {{ old('status', $heroSection->status) ? 'checked' : '' }}
                           class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                    <label for="status" class="ml-2 block text-sm text-gray-700">
                        Active (visible on website)
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.hero-sections.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Hero Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
