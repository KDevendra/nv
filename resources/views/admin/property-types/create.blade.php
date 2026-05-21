@extends('layouts.admin')

@section('title', 'Create Property Type')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Create New Property Type</h2>
                <a href="{{ route('admin.property-types.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.property-types.store') }}" method="POST" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Property Type Name *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="e.g., House"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Property Type Selection *</label>
                    <select name="category" 
                            id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('category') border-red-500 @enderror"
                            required>
                        <option value="">Select Property Type</option>
                        <option value="residential" {{ old('category') == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="commercial" {{ old('category') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Brief description of this property type...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" 
                       name="sort_order" 
                       id="sort_order" 
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('sort_order') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service Types Mapping -->
            <div class="pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Map Service Types
                </label>
                <p class="text-sm text-gray-600 mb-4">Select which service types support this property type</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @forelse($serviceTypes as $serviceType)
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-zendo-gold cursor-pointer transition-colors">
                            <input type="checkbox" name="service_types[]" value="{{ $serviceType->id }}"
                                {{ in_array($serviceType->id, old('service_types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold mr-3">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $serviceType->name }}
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 col-span-3">No service types available. Create service types first.</p>
                    @endforelse
                </div>
                @error('service_types')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- BHK Mapping -->
            <div class="pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Map BHK Types
                </label>
                <p class="text-sm text-gray-600 mb-4">Select which BHK configurations are available for this property type</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @forelse($bhks as $bhk)
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-zendo-gold cursor-pointer transition-colors">
                            <input type="checkbox" name="bhks[]" value="{{ $bhk->id }}"
                                {{ in_array($bhk->id, old('bhks', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold mr-3">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $bhk->name }}
                            </span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 col-span-4">No BHK types available. Create BHK types first.</p>
                    @endforelse
                </div>
                @error('bhks')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="pt-6 border-t border-gray-200">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" 
                               name="status" 
                               id="status" 
                               value="1"
                               {{ old('status', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">
                            Active (visible on website)
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="hidden" name="show_in_header" value="0">
                        <input type="checkbox" 
                               name="show_in_header" 
                               id="show_in_header" 
                               value="1"
                               {{ old('show_in_header', false) ? 'checked' : '' }}
                               class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded">
                        <label for="show_in_header" class="ml-2 block text-sm text-gray-700">
                            Show in Header Menu (Services dropdown)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Intro Section (Top Banner) -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Intro Section (Top Banner)</h3>
                <p class="text-sm text-gray-600 mb-4">Configure the intro banner that appears at the top of the property listing page</p>
                
                <div class="space-y-4">
                    <div>
                        <label for="intro_kicker" class="block text-sm font-medium text-gray-700 mb-2">Kicker Text</label>
                        <input type="text" name="intro_kicker" id="intro_kicker" value="{{ old('intro_kicker') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                               placeholder="e.g., Residential Properties">
                        <p class="mt-1 text-sm text-gray-500">Small text above the main title</p>
                    </div>
                    
                    <div>
                        <label for="intro_title" class="block text-sm font-medium text-gray-700 mb-2">Main Title</label>
                        <input type="text" name="intro_title" id="intro_title" value="{{ old('intro_title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                               placeholder="e.g., Find Your Premium Home in Top Locations">
                    </div>
                    
                    <div>
                        <label for="intro_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="intro_description" id="intro_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                  placeholder="Brief description...">{{ old('intro_description') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Badges (3 items)</label>
                        <div class="space-y-2">
                            <input type="text" name="intro_badges[]" value="{{ old('intro_badges.0', 'Verified Listings') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="Badge 1">
                            <input type="text" name="intro_badges[]" value="{{ old('intro_badges.1', 'Fast Shortlisting') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="Badge 2">
                            <input type="text" name="intro_badges[]" value="{{ old('intro_badges.2', 'Prime Locations') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="Badge 3">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Section -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Carousel Section (Property Page)</h3>
                <p class="text-sm text-gray-600 mb-4">Configure the carousel section that appears on the property listing page</p>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="carousel_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <input type="text" name="carousel_subtitle" id="carousel_subtitle" value="{{ old('carousel_subtitle') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Residential Living">
                        </div>
                        <div>
                            <label for="carousel_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="carousel_title" id="carousel_title" value="{{ old('carousel_title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Homes Designed for Comfort">
                        </div>
                    </div>
                    
                    <div>
                        <label for="carousel_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="carousel_description" id="carousel_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                  placeholder="Describe the carousel section...">{{ old('carousel_description') }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="carousel_button_text" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
                            <input type="text" name="carousel_button_text" id="carousel_button_text" value="{{ old('carousel_button_text') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Get Expert Advice">
                        </div>
                        <div>
                            <label for="carousel_button_link" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Link</label>
                            <input type="text" name="carousel_button_link" id="carousel_button_link" value="{{ old('carousel_button_link') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., #enquiry">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="carousel_secondary_button_text" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                            <input type="text" name="carousel_secondary_button_text" id="carousel_secondary_button_text" value="{{ old('carousel_secondary_button_text') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., View More Projects">
                        </div>
                        <div>
                            <label for="carousel_secondary_button_link" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
                            <input type="text" name="carousel_secondary_button_link" id="carousel_secondary_button_link" value="{{ old('carousel_secondary_button_link') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., #projects">
                        </div>
                    </div>
                    
                    <div>
                        <label for="carousel_images" class="block text-sm font-medium text-gray-700 mb-2">Carousel Images</label>
                        <input type="file" name="carousel_images[]" id="carousel_images" multiple accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold">
                        <p class="mt-1 text-sm text-gray-500">Upload multiple images for the carousel (JPEG, PNG, WebP, max 2MB each)</p>
                    </div>
                </div>
            </div>

            <!-- Perspective Section -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Perspective Section (Property Page)</h3>
                <p class="text-sm text-gray-600 mb-4">Configure the perspective section with property insights</p>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="perspective_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <input type="text" name="perspective_subtitle" id="perspective_subtitle" value="{{ old('perspective_subtitle') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Residential Properties">
                        </div>
                        <div>
                            <label for="perspective_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="perspective_title" id="perspective_title" value="{{ old('perspective_title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Choose the Right Home">
                        </div>
                    </div>
                    
                    <div>
                        <label for="perspective_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="perspective_description" id="perspective_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                  placeholder="Describe the perspective section...">{{ old('perspective_description') }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="perspective_button_text" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
                            <input type="text" name="perspective_button_text" id="perspective_button_text" value="{{ old('perspective_button_text') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Get Project List">
                        </div>
                        <div>
                            <label for="perspective_button_link" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Link</label>
                            <input type="text" name="perspective_button_link" id="perspective_button_link" value="{{ old('perspective_button_link') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., #enquiry">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="perspective_secondary_button_text" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                            <input type="text" name="perspective_secondary_button_text" id="perspective_secondary_button_text" value="{{ old('perspective_secondary_button_text') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., Request a Call">
                        </div>
                        <div>
                            <label for="perspective_secondary_button_link" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
                            <input type="text" name="perspective_secondary_button_link" id="perspective_secondary_button_link" value="{{ old('perspective_secondary_button_link') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold"
                                   placeholder="e.g., #contact">
                        </div>
                    </div>
                    
                    <div>
                        <label for="perspective_images" class="block text-sm font-medium text-gray-700 mb-2">Perspective Images</label>
                        <input type="file" name="perspective_images[]" id="perspective_images" multiple accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold">
                        <p class="mt-1 text-sm text-gray-500">Upload 4 images for the perspective grid (JPEG, PNG, WebP, max 2MB each)</p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.property-types.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Property Type
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
