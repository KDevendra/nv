@extends('layouts.admin')

@section('title', 'Edit About Page')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">About Page Content</h2>
                <a href="{{ route('about') }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    View About Page
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mx-6 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.about-page.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Section Header -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Page Header</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                        <input type="text" name="section_title" value="{{ old('section_title', $aboutPage->section_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section Subtitle</label>
                        <input type="text" name="section_subtitle" value="{{ old('section_subtitle', $aboutPage->section_subtitle ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Our Company Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Our Company Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Who We Are -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-800">Who We Are</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="who_we_are_title" value="{{ old('who_we_are_title', $aboutPage->who_we_are_title ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="who_we_are_description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('who_we_are_description', $aboutPage->who_we_are_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            @if ($aboutPage && $aboutPage->who_we_are_icon)
                                <img src="{{ $aboutPage->who_we_are_icon_url }}" alt="Icon" class="h-12 w-12 mb-2">
                            @endif
                            <input type="file" name="who_we_are_icon" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                    </div>

                    <!-- Mission -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-800">Our Mission</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="mission_title" value="{{ old('mission_title', $aboutPage->mission_title ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="mission_description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('mission_description', $aboutPage->mission_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            @if ($aboutPage && $aboutPage->mission_icon)
                                <img src="{{ $aboutPage->mission_icon_url }}" alt="Icon" class="h-12 w-12 mb-2">
                            @endif
                            <input type="file" name="mission_icon" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                    </div>

                    <!-- Vision -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-800">Our Vision</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="vision_title" value="{{ old('vision_title', $aboutPage->vision_title ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="vision_description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('vision_description', $aboutPage->vision_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                            @if ($aboutPage && $aboutPage->vision_icon)
                                <img src="{{ $aboutPage->vision_icon_url }}" alt="Icon" class="h-12 w-12 mb-2">
                            @endif
                            <input type="file" name="vision_icon" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Values Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Our Values Section</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Values Heading</label>
                        <input type="text" name="values_heading" value="{{ old('values_heading', $aboutPage->values_heading ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tab 1: Who We Are</label>
                            <textarea name="values_who_we_are" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('values_who_we_are', $aboutPage->values_who_we_are ?? '') }}</textarea>
                            <label class="block text-sm font-medium text-gray-700 mt-3 mb-2">Tab 1 Image</label>
                            @if ($aboutPage && $aboutPage->values_who_we_are_image)
                                <img src="{{ $aboutPage->values_who_we_are_image_url }}" alt="Who We Are" class="h-20 w-20 object-cover rounded mb-2">
                            @endif
                            <input type="file" name="values_who_we_are_image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tab 2: Mission</label>
                            <textarea name="values_mission" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('values_mission', $aboutPage->values_mission ?? '') }}</textarea>
                            <label class="block text-sm font-medium text-gray-700 mt-3 mb-2">Tab 2 Image</label>
                            @if ($aboutPage && $aboutPage->values_mission_image)
                                <img src="{{ $aboutPage->values_mission_image_url }}" alt="Mission" class="h-20 w-20 object-cover rounded mb-2">
                            @endif
                            <input type="file" name="values_mission_image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tab 3: Vision</label>
                            <textarea name="values_vision" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('values_vision', $aboutPage->values_vision ?? '') }}</textarea>
                            <label class="block text-sm font-medium text-gray-700 mt-3 mb-2">Tab 3 Image</label>
                            @if ($aboutPage && $aboutPage->values_vision_image)
                                <img src="{{ $aboutPage->values_vision_image_url }}" alt="Vision" class="h-20 w-20 object-cover rounded mb-2">
                            @endif
                            <input type="file" name="values_vision_image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tab 4: Teamwork</label>
                            <textarea name="values_teamwork" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('values_teamwork', $aboutPage->values_teamwork ?? '') }}</textarea>
                            <label class="block text-sm font-medium text-gray-700 mt-3 mb-2">Tab 4 Image</label>
                            @if ($aboutPage && $aboutPage->values_teamwork_image)
                                <img src="{{ $aboutPage->values_teamwork_image_url }}" alt="Teamwork" class="h-20 w-20 object-cover rounded mb-2">
                            @endif
                            <input type="file" name="values_teamwork_image" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Team Section Title</label>
                        <input type="text" name="team_section_title" value="{{ old('team_section_title', $aboutPage->team_section_title ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Team Section Heading</label>
                        <input type="text" name="team_section_heading" value="{{ old('team_section_heading', $aboutPage->team_section_heading ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('about') }}" target="_blank"
                   class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Preview
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update About Page
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
