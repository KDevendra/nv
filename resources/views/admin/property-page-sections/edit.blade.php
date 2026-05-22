@extends('layouts.admin')

@section('title', 'Edit Property Page Section')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-heading text-zendo-navy font-semibold">Edit Property Page Section</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Section: <span class="font-semibold text-zendo-gold">{{ ucwords(str_replace('_', ' ', $propertyPageSection->section_key)) }}</span>
                        @if($propertyPageSection->propertyType)
                            &mdash; <span class="text-gray-600">{{ $propertyPageSection->propertyType->name }}</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.property-page-sections.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.property-page-sections.update', $propertyPageSection) }}"
              method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- ── Meta ─────────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section Key</label>
                    <input type="text" value="{{ $propertyPageSection->section_key }}" readonly
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-400">Cannot be changed</p>
                </div>
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" name="order" id="order"
                           value="{{ old('order', $propertyPageSection->order) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    @error('order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── Intro / Kicker (only for intro_section) ─────────────── --}}
            @if(str_contains($propertyPageSection->section_key, 'intro'))
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Intro Fields</h3>
                <div class="space-y-4">
                    <div>
                        <label for="kicker" class="block text-sm font-medium text-gray-700 mb-2">Kicker Text</label>
                        <input type="text" name="kicker" id="kicker"
                               value="{{ old('kicker', $propertyPageSection->kicker) }}"
                               placeholder="e.g., Residential Properties"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-400">Small label shown above the main title</p>
                        @error('kicker')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Title / Subtitle ─────────────────────────────────────── --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Content</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle"
                               value="{{ old('subtitle', $propertyPageSection->subtitle) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('subtitle')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" id="title"
                               value="{{ old('title', $propertyPageSection->title) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">{{ old('description', $propertyPageSection->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── Buttons ──────────────────────────────────────────────── --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Buttons</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
                        <input type="text" name="button_text" id="button_text"
                               value="{{ old('button_text', $propertyPageSection->button_text) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('button_text')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">Primary Button Link</label>
                        <input type="text" name="button_link" id="button_link"
                               value="{{ old('button_link', $propertyPageSection->button_link) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('button_link')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="secondary_button_text" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                        <input type="text" name="secondary_button_text" id="secondary_button_text"
                               value="{{ old('secondary_button_text', $propertyPageSection->secondary_button_text) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('secondary_button_text')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="secondary_button_link" class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
                        <input type="text" name="secondary_button_link" id="secondary_button_link"
                               value="{{ old('secondary_button_link', $propertyPageSection->secondary_button_link) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        @error('secondary_button_link')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- ── Badges (intro section) ───────────────────────────────── --}}
            @if(str_contains($propertyPageSection->section_key, 'intro'))
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Badges</h3>
                <div id="badges-container" class="space-y-2">
                    @php $badges = old('badges', $propertyPageSection->badges ?? []); @endphp
                    @foreach($badges as $badge)
                    <div class="flex gap-2 badge-item">
                        <input type="text" name="badges[]" value="{{ $badge }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <button type="button" onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
                    </div>
                    @endforeach
                    @if(empty($badges))
                    <div class="flex gap-2 badge-item">
                        <input type="text" name="badges[]" placeholder="e.g., Verified Listings"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <button type="button" onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addBadge()"
                        class="mt-2 text-zendo-gold hover:text-zendo-navy text-sm font-semibold">+ Add Badge</button>
            </div>
            @endif

            {{-- ── Images ───────────────────────────────────────────────── --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Images</h3>

                @if($propertyPageSection->images && count($propertyPageSection->images) > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current images — click × to delete</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($propertyPageSection->images_urls as $index => $imageUrl)
                        <div class="relative group">
                            <img src="{{ $imageUrl }}" alt="Image {{ $index + 1 }}"
                                 class="w-full h-28 object-cover rounded-lg">
                            <a href="#" onclick="event.preventDefault(); if(confirm('Delete this image?')) document.getElementById('delete-image-{{ $index }}').submit();"
                               class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-400">JPEG, PNG, WebP — max 2 MB each. New images are appended to existing ones.</p>
                    @error('images.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── Features (perspective section) ──────────────────────── --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-1 uppercase tracking-wide">Features</h3>
                <p class="text-xs text-gray-400 mb-3">Used in the Perspective section. HTML tags like &lt;strong&gt; are supported.</p>
                <div id="features-container" class="space-y-2">
                    @php $features = old('features', $propertyPageSection->features ?? []); @endphp
                    @foreach($features as $feature)
                    <div class="flex gap-2 feature-item">
                        <input type="text" name="features[]" value="{{ $feature }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <button type="button" onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
                    </div>
                    @endforeach
                    @if(empty($features))
                    <div class="flex gap-2 feature-item">
                        <input type="text" name="features[]"
                               placeholder="e.g., <strong>Connectivity:</strong> Main roads, expressways..."
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <button type="button" onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addFeature()"
                        class="mt-2 text-zendo-gold hover:text-zendo-navy text-sm font-semibold">+ Add Feature</button>
            </div>

            {{-- ── Status ───────────────────────────────────────────────── --}}
            <div class="pt-4 border-t border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $propertyPageSection->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-zendo-gold focus:ring-zendo-gold">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>

            {{-- ── Submit ───────────────────────────────────────────────── --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.property-page-sections.index') }}"
                   class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Section
                </button>
            </div>
        </form>

        {{-- Image delete forms (outside main form to avoid nesting) --}}
        @if($propertyPageSection->images && count($propertyPageSection->images) > 0)
            @foreach($propertyPageSection->images as $index => $image)
            <form id="delete-image-{{ $index }}"
                  action="{{ route('admin.property-page-sections.delete-image', $propertyPageSection) }}"
                  method="POST" style="display:none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="image_index" value="{{ $index }}">
            </form>
            @endforeach
        @endif
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 feature-item';
    div.innerHTML = `
        <input type="text" name="features[]"
               placeholder="e.g., <strong>Amenities:</strong> Clubhouse, gym..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
        <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
    `;
    container.appendChild(div);
}

function addBadge() {
    const container = document.getElementById('badges-container');
    if (!container) return;
    const div = document.createElement('div');
    div.className = 'flex gap-2 badge-item';
    div.innerHTML = `
        <input type="text" name="badges[]"
               placeholder="e.g., Prime Locations"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
        <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">Remove</button>
    `;
    container.appendChild(div);
}
</script>
@endsection
