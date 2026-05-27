@extends('layouts.admin')

@section('title', 'Edit Property')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-heading text-zendo-navy font-semibold">Edit Property</h2>
                    <a href="{{ route('admin.properties.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Title *</label>
                            <input type="text" name="title" value="{{ old('title', $property->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('description', $property->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Type *</label>
                            <select name="property_type_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select Property Type</option>
                                @foreach ($propertyTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">BHK</label>
                            <select name="bhk_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select BHK</option>
                                @foreach ($bhks as $bhk)
                                    <option value="{{ $bhk->id }}"
                                        {{ old('bhk_id', $property->bhk_id) == $bhk->id ? 'selected' : '' }}>
                                        {{ $bhk->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <select name="city_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id', $property->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <select name="location_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select Location</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        {{ old('location_id', $property->location_id) == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Status *</label>
                            <select name="project_status_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select Status</option>
                                @foreach ($projectStatuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('project_status_id', $property->project_status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Builder</label>
                            <select name="builder_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <option value="">Select Builder</option>
                                @foreach ($builders as $builder)
                                    <option value="{{ $builder->id }}"
                                        {{ old('builder_id', $property->builder_id) == $builder->id ? 'selected' : '' }}>
                                        {{ $builder->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <!-- Pricing & Area -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Pricing & Area</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                                <input type="number" name="price" value="{{ old('price', $property->price) }}" required
                                    step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price per Sqft (₹)</label>
                                <input type="number" name="price_per_sqft"
                                    value="{{ old('price_per_sqft', $property->price_per_sqft) }}" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Carpet Area (sqft)</label>
                                <input type="number" name="carpet_area"
                                    value="{{ old('carpet_area', $property->carpet_area) }}" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Built-up Area (sqft)</label>
                                <input type="number" name="built_up_area"
                                    value="{{ old('built_up_area', $property->built_up_area) }}" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Plot Area (sqft)</label>
                                <input type="number" name="plot_area" value="{{ old('plot_area', $property->plot_area) }}"
                                    step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Specifications</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Floors</label>
                                <input type="number" name="total_floors"
                                    value="{{ old('total_floors', $property->specifications->total_floors ?? '') }}"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Floor Number</label>
                                <input type="number" name="floor_number"
                                    value="{{ old('floor_number', $property->specifications->floor_number ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                                <input type="number" name="bedrooms"
                                    value="{{ old('bedrooms', $property->specifications->bedrooms ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                                <input type="number" name="bathrooms"
                                    value="{{ old('bathrooms', $property->specifications->bathrooms ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Balconies</label>
                                <input type="number" name="balconies"
                                    value="{{ old('balconies', $property->specifications->balconies ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parking Spaces</label>
                                <input type="number" name="parking_spaces"
                                    value="{{ old('parking_spaces', $property->specifications->parking_spaces ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Furnishing Status</label>
                                <select name="furnishing_status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                    <option value="">Select Status</option>
                                    <option value="unfurnished"
                                        {{ old('furnishing_status', $property->specifications->furnishing_status ?? '') == 'unfurnished' ? 'selected' : '' }}>
                                        Unfurnished</option>
                                    <option value="semi_furnished"
                                        {{ old('furnishing_status', $property->specifications->furnishing_status ?? '') == 'semi_furnished' ? 'selected' : '' }}>
                                        Semi Furnished</option>
                                    <option value="fully_furnished"
                                        {{ old('furnishing_status', $property->specifications->furnishing_status ?? '') == 'fully_furnished' ? 'selected' : '' }}>
                                        Fully Furnished</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facing</label>
                                <select name="facing"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                    <option value="">Select Facing</option>
                                    <option value="north"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'north' ? 'selected' : '' }}>
                                        North</option>
                                    <option value="south"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'south' ? 'selected' : '' }}>
                                        South</option>
                                    <option value="east"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'east' ? 'selected' : '' }}>
                                        East</option>
                                    <option value="west"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'west' ? 'selected' : '' }}>
                                        West</option>
                                    <option value="north_east"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'north_east' ? 'selected' : '' }}>
                                        North East</option>
                                    <option value="north_west"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'north_west' ? 'selected' : '' }}>
                                        North West</option>
                                    <option value="south_east"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'south_east' ? 'selected' : '' }}>
                                        South East</option>
                                    <option value="south_west"
                                        {{ old('facing', $property->specifications->facing ?? '') == 'south_west' ? 'selected' : '' }}>
                                        South West</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Age of Property (years)</label>
                                <input type="number" name="age_of_property"
                                    value="{{ old('age_of_property', $property->specifications->age_of_property ?? '') }}"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Possession Date</label>
                                <input type="date" name="possession_date"
                                    value="{{ old('possession_date', $property->specifications && $property->specifications->possession_date ? $property->specifications->possession_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RERA ID</label>
                                <input type="text" name="rera_id"
                                    value="{{ old('rera_id', $property->specifications->rera_id ?? '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Location Details</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="address" rows="2" id="address"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('address', $property->address) }}</textarea>
                            </div>

                            <!-- Embed Code Method -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Map Embed Code</label>
                                <textarea name="map_embed_code" id="map_embed_code" rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent font-mono text-sm"
                                    placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('map_embed_code', $property->map_embed_code) }}</textarea>
                                <p class="mt-2 text-sm text-gray-500">
                                    Paste the embed code from Google Maps.
                                    <a href="https://www.google.com/maps" target="_blank"
                                        class="text-zendo-gold hover:underline">Get embed code from Google Maps</a>
                                </p>
                            </div>

                            <!-- Preview -->
                            <div id="embed-preview" style="{{ $property->map_embed_code ? '' : 'display: none;' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                                <div class="w-full h-96 rounded-lg border-2 border-gray-300 overflow-hidden"
                                    id="embed-preview-container">
                                    @if ($property->map_embed_code)
                                        {!! $property->map_embed_code !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($amenities as $amenity)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                        {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                                    <span class="text-sm text-gray-700">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Existing Images -->
                    @if ($property->images->count() > 0)
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Existing Images</h3>

                            <!-- Main Image -->
                            @php
                                $mainImage = $property->images->where('image_type', 'main')->first();
                                $galleryImages = $property->images->where('image_type', 'gallery');
                            @endphp

                            @if ($mainImage)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Main Image</h4>
                                    <div class="relative group inline-block">
                                        <img src="{{ asset($mainImage->image_path) }}" alt="{{ $mainImage->alt_tag ?? $property->title }}"
                                            class="w-48 h-32 object-cover rounded-lg border-2 border-zendo-gold">
                                        <span
                                            class="absolute top-2 left-2 bg-zendo-gold text-white text-xs px-2 py-1 rounded">Main</span>
                                        <button type="button" onclick="deleteImage({{ $mainImage->id }})"
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-red-600 text-white p-1 rounded hover:bg-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-2 max-w-xs">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Tag</label>
                                        <input type="text" name="existing_images_alt[{{ $mainImage->id }}]"
                                            value="{{ $mainImage->alt_tag }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                                            placeholder="Describe this image for SEO">
                                    </div>
                                </div>
                            @endif

                            <!-- Gallery Images -->
                            @if ($galleryImages->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gallery Images</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @foreach ($galleryImages as $image)
                                            <div class="relative group">
                                                <img src="{{ asset($image->image_path) }}"
                                                    alt="{{ $image->alt_tag ?? 'Gallery Image' }}" class="w-full h-32 object-cover rounded-lg">
                                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity bg-red-600 text-white p-1 rounded hover:bg-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                                <div class="mt-1">
                                                    <input type="text" name="existing_images_alt[{{ $image->id }}]"
                                                        value="{{ $image->alt_tag }}"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                                                        placeholder="Alt tag">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Add New Images -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Add New Images</h3>

                        <!-- Upload New Main Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $property->images->where('image_type', 'main')->count() > 0 ? 'Replace Main Image' : 'Upload Main Image' }}
                            </label>
                            <input type="file" name="main_image" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">
                                @if ($property->images->where('image_type', 'main')->count() > 0)
                                    Upload a new image to replace the current main image.
                                @else
                                    Upload the primary image for this property.
                                @endif
                                Accepted formats: JPEG, PNG, JPG, WEBP. Max size: 2MB.
                            </p>
                            @error('main_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alt Tag (SEO)</label>
                                <input type="text" name="main_image_alt" value="{{ old('main_image_alt') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                                    placeholder="Describe the image for SEO & accessibility">
                            </div>
                        </div>

                        <!-- Upload Gallery Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Add Gallery Images</label>
                            <input type="file" name="gallery_images[]" multiple accept="image/*" id="gallery_images_input"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">Upload additional images for the property gallery.
                                Accepted formats: JPEG, PNG, JPG, WEBP. Max size: 2MB per image.</p>
                            @error('gallery_images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="gallery-alt-tags-container" class="mt-3 space-y-2"></div>
                        </div>
                    </div>

                    <!-- Video & Media -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Video & Media</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
                                <input type="url" name="youtube_url" value="{{ old('youtube_url', $property->youtube_url) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                <p class="mt-1 text-sm text-gray-500">Paste a YouTube video link for this property.</p>
                                @error('youtube_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($property->video_path)
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Video</p>
                                <video controls class="w-full max-h-48 rounded-lg">
                                    <source src="{{ asset('storage/' . $property->video_path) }}" type="video/mp4">
                                </video>
                                <label class="flex items-center space-x-2 cursor-pointer mt-3">
                                    <input type="checkbox" name="remove_video" value="1"
                                           class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                    <span class="text-sm text-red-600 font-medium">Remove current video</span>
                                </label>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ $property->video_path ? 'Replace Video' : 'Upload Video' }}</label>
                                <input type="file" name="video_path"
                                    accept="video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                <p class="mt-1 text-sm text-gray-500">Accepted formats: MP4, MOV, AVI, WMV. Max size: 100MB.</p>
                                @error('video_path')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- FAQs -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Frequently Asked Questions</h3>
                        <div id="faqs-container">
                            @php
                                $existingFaqs = old('faqs', $property->faqs ? $property->faqs->toArray() : []);
                            @endphp

                            @forelse($existingFaqs as $index => $faq)
                                <div class="faq-item border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="text-sm font-medium text-gray-700">FAQ #{{ $index + 1 }}</h4>
                                        <button type="button" onclick="removeFaq(this)"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                                            <input type="text" name="faqs[{{ $index }}][question]"
                                                value="{{ $faq['question'] ?? '' }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                                            <textarea name="faqs[{{ $index }}][answer]" rows="3"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ $faq['answer'] ?? '' }}</textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Display
                                                    Order</label>
                                                <input type="number" name="faqs[{{ $index }}][display_order]"
                                                    value="{{ $faq['display_order'] ?? $index }}" min="0"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                                            </div>

                                            <div class="flex items-end">
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" name="faqs[{{ $index }}][is_active]"
                                                        value="1" {{ $faq['is_active'] ?? true ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                                                    <span class="text-sm text-gray-700">Active</span>
                                                </label>
                                            </div>
                                        </div>

                                        <input type="hidden" name="faqs[{{ $index }}][id]"
                                            value="{{ $faq['id'] ?? '' }}">
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 mb-4">No FAQs added yet. Click "Add FAQ" to create one.</p>
                            @endforelse
                        </div>

                        <button type="button" onclick="addFaq()"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Add FAQ
                        </button>
                    </div>

                    <!-- Hidden Details (Admin Only) -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Hidden Details</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Always visible to admin. Shown publicly only when "Show on public page" is enabled.</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Admin Only</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <textarea id="hidden_details" name="hidden_details" rows="8"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('hidden_details', $property->hidden_details) }}</textarea>
                            </div>
                            <div>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="show_hidden_details" value="1"
                                        {{ old('show_hidden_details', $property->show_hidden_details) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                                    <span class="text-sm font-medium text-gray-700">Show on public property page</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1 ml-6">When checked, this content will be visible to visitors on the property detail page.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Status & Publishing -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900 mb-4">Status & Publishing</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $property->is_active) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                                    <span class="text-sm font-medium text-gray-700">Active</span>
                                </label>
                            </div>

                            <div>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1"
                                        {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                                    <span class="text-sm font-medium text-gray-700">Featured</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Published At</label>
                                <input type="datetime-local" name="published_at"
                                    value="{{ old('published_at', $property->published_at ? $property->published_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div
                        class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.properties.index') }}"
                            class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update Property
                        </button>
                    </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.2/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: '#hidden_details',
                height: 450,
                plugins: 'advlist lists link image table preview fullscreen charmap paste codesample code',
                toolbar: `bold italic underline | alignleft aligncenter alignright alignjustify | fontsizeselect | forecolor backcolor | numlist bullist | indent outdent | link image | increasefontsize decreasefontsize | print table | preview fullscreen | charmap code | importword exportpdf exportword`,
                automatic_uploads: true,
                images_upload_url: '{{ route("admin.blogs.upload-image") }}',
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route("admin.blogs.upload-image") }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.onload = function() {
                        if (xhr.status != 200) { failure('HTTP Error: ' + xhr.status); return; }
                        var json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') { failure('Invalid JSON: ' + xhr.responseText); return; }
                        success(json.location);
                    };
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                },
                file_picker_types: 'image',
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            var file = this.files[0];
                            var reader = new FileReader();
                            reader.onload = function() {
                                var id = 'blobid' + (new Date()).getTime();
                                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                var base64 = reader.result.split(',')[1];
                                var blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                callback(blobInfo.blobUri(), { title: file.name });
                            };
                            reader.readAsDataURL(file);
                        };
                        input.click();
                    }
                },
                setup: function(editor) {
                    editor.ui.registry.addButton('increasefontsize', {
                        text: 'A+',
                        onAction: () => editor.execCommand('FontSize', false, 'larger')
                    });
                    editor.ui.registry.addButton('decreasefontsize', {
                        text: 'A-',
                        onAction: () => editor.execCommand('FontSize', false, 'smaller')
                    });
                    editor.ui.registry.addButton('exportpdf', {
                        text: 'Export PDF',
                        onAction: () => editor.execCommand('mcePrint')
                    });
                    editor.ui.registry.addButton('exportword', {
                        text: 'Export Word',
                        onAction: function() {
                            let content = editor.getContent();
                            let blob = new Blob([content], { type: 'application/msword' });
                            let url = URL.createObjectURL(blob);
                            let a = document.createElement('a');
                            a.href = url; a.download = 'document.doc'; a.click();
                            URL.revokeObjectURL(url);
                        }
                    });
                }
            });
        </script>
        <script>
            let faqIndex = {{ count($existingFaqs ?? []) }};

            // Preview embed code
            document.addEventListener('DOMContentLoaded', function() {
                const embedInput = document.getElementById('map_embed_code');
                const previewContainer = document.getElementById('embed-preview-container');
                const previewSection = document.getElementById('embed-preview');

                if (embedInput) {
                    embedInput.addEventListener('input', function() {
                        const embedCode = this.value.trim();
                        if (embedCode) {
                            previewContainer.innerHTML = embedCode;
                            previewSection.style.display = 'block';
                        } else {
                            previewContainer.innerHTML = '';
                            previewSection.style.display = 'none';
                        }
                    });
                }
            });

            function addFaq() {
                const container = document.getElementById('faqs-container');
                const emptyMessage = container.querySelector('p.text-gray-500');
                if (emptyMessage) emptyMessage.remove();

                const faqHtml = `
        <div class="faq-item border border-gray-200 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-start mb-3">
                <h4 class="text-sm font-medium text-gray-700">FAQ #${faqIndex + 1}</h4>
                <button type="button" onclick="removeFaq(this)" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                    <input type="text" name="faqs[${faqIndex}][question]"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                    <textarea name="faqs[${faqIndex}][answer]" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                        <input type="number" name="faqs[${faqIndex}][display_order]" value="${faqIndex}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    </div>
                    
                    <div class="flex items-end">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="faqs[${faqIndex}][is_active]" value="1" checked
                                   class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                
                <input type="hidden" name="faqs[${faqIndex}][id]" value="">
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', faqHtml);
                faqIndex++;
            }

            function removeFaq(button) {
                if (confirm('Are you sure you want to remove this FAQ?')) {
                    button.closest('.faq-item').remove();

                    // Update FAQ numbers
                    document.querySelectorAll('.faq-item').forEach((item, index) => {
                        item.querySelector('h4').textContent = `FAQ #${index + 1}`;
                    });

                    // Show empty message if no FAQs
                    const container = document.getElementById('faqs-container');
                    if (container.querySelectorAll('.faq-item').length === 0) {
                        container.innerHTML =
                            '<p class="text-sm text-gray-500 mb-4">No FAQs added yet. Click "Add FAQ" to create one.</p>';
                    }
                }
            }

            function deleteImage(imageId) {
                if (confirm('Are you sure you want to delete this image?')) {
                    // Create a form dynamically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/properties/images/${imageId}`;

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add DELETE method
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Submit the form
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Gallery images alt tag dynamic inputs
            document.getElementById('gallery_images_input').addEventListener('change', function() {
                const container = document.getElementById('gallery-alt-tags-container');
                container.innerHTML = '';
                const files = this.files;
                for (let i = 0; i < files.length; i++) {
                    const div = document.createElement('div');
                    div.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alt Tag for: ${files[i].name}</label>
                        <input type="text" name="gallery_images_alt[]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                            placeholder="Describe this image for SEO & accessibility">
                    `;
                    container.appendChild(div);
                }
            });
        </script>
    @endpush
