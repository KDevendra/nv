@extends('layouts.admin')

@section('title', 'Video Tour Section')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-heading text-zendo-navy font-semibold">Video Tour Section</h2>
                <p class="text-gray-600 mt-1 text-sm">Manage the video tour section on the home page</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.video-tour.update') }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-lg shadow-lg p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf
            @method('PUT')

            <!-- Badge Text -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Badge Text</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $videoTour->badge_text) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="e.g. Take a video tour">
                @error('badge_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $videoTour->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="Watch the video for taking your decision easily.">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Button -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $videoTour->button_text) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="View all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                    <input type="text" name="button_link" value="{{ old('button_link', $videoTour->button_link) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="/properties">
                </div>
            </div>

            <!-- Video Section -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Video</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
                        <input type="url" name="youtube_url" value="{{ old('youtube_url', $videoTour->youtube_url) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="https://www.youtube.com/watch?v=...">
                        <p class="mt-1 text-sm text-gray-500">Paste a YouTube video link. This takes priority over uploaded video.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Or Upload Video</label>
                        @if($videoTour->video_path)
                            <div class="mb-2 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                                <span class="text-sm text-gray-600">Current: {{ basename($videoTour->video_path) }}</span>
                                <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                    <input type="checkbox" name="remove_video" value="1" class="mr-2 rounded border-gray-300">
                                    Remove
                                </label>
                            </div>
                        @endif
                        <input type="file" name="video_path" accept="video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Max 100MB. Formats: MP4, MOV, AVI, WMV.</p>
                    </div>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Thumbnail</h3>

                <div class="space-y-4">
                    @if($videoTour->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset($videoTour->thumbnail) }}" alt="Current thumbnail"
                                class="h-40 w-auto rounded-lg border border-gray-200">
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Recommended: 16:9 aspect ratio. Max 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Alt Tag (SEO)</label>
                        <input type="text" name="thumbnail_alt" value="{{ old('thumbnail_alt', $videoTour->thumbnail_alt) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="Describe the thumbnail for SEO">
                    </div>
                </div>
            </div>

            <!-- Phone Number -->
            <div class="pt-4 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number (shown below section)</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $videoTour->phone_number) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                    placeholder="+91 97323 00007">
            </div>

            <!-- Active Status -->
            <div class="pt-4 border-t border-gray-200">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $videoTour->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                    <span class="text-sm font-medium text-gray-700">Show section on home page</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
