@extends('layouts.admin')

@section('content')
<div class="bg-gray-100 p-6 rounded-lg mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-heading text-zendo-navy font-semibold">Add New City</h1>
            <p class="text-gray-600 mt-1">Create a new city for the popular cities section</p>
        </div>
        <a href="{{ route('admin.cities.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Cities
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-8">
    <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">City Name *</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                           placeholder="Enter city name"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent transition-colors @error('description') border-red-500 @enderror"
                              placeholder="Enter city description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Count -->
                <div>
                    <label for="property_count" class="block text-sm font-medium text-gray-700 mb-2">Property Count *</label>
                    <input type="number" 
                           name="property_count" 
                           id="property_count" 
                           value="{{ old('property_count', 0) }}"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent transition-colors @error('property_count') border-red-500 @enderror"
                           placeholder="Enter number of properties"
                           required>
                    @error('property_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link -->
                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                    <input type="url" 
                           name="link" 
                           id="link" 
                           value="{{ old('link', '#') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent transition-colors @error('link') border-red-500 @enderror"
                           placeholder="Enter link URL">
                    @error('link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">City Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-zendo-gold transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-zendo-gold hover:text-yellow-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-zendo-gold">
                                    <span>Upload an image</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF, WebP up to 2MB</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Alt Tag -->
                <div>
                    <label for="image_alt" class="block text-sm font-medium text-gray-700 mb-2">Image Alt Tag (SEO)</label>
                    <input type="text" name="image_alt" id="image_alt" value="{{ old('image_alt') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                        placeholder="e.g. Properties in Delhi NCR">
                    <p class="mt-1 text-xs text-gray-500">Describe the image for SEO & accessibility</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order *</label>
                    <input type="number" 
                           name="sort_order" 
                           id="sort_order" 
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent transition-colors @error('sort_order') border-red-500 @enderror"
                           placeholder="Enter sort order"
                           required>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="status" 
                               value="1" 
                               {{ old('status', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-zendo-gold shadow-sm focus:border-zendo-gold focus:ring focus:ring-zendo-gold focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.cities.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-zendo-gold hover:bg-yellow-600 text-white rounded-lg font-medium transition-colors">
                Create City
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('preview-img').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection