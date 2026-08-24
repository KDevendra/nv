@props([
    'idx' => 0,
    'label' => 'Photo',
    'property' => null,
])
@php
    $existing = null;
    if (isset($property) && $property && isset($property->photos) && count($property->photos) > 0) {
        $existing = $property->photos->firstWhere('slot_label', $label) 
                 ?? $property->photos->firstWhere('slot_index', $idx) 
                 ?? ($property->photos[$idx] ?? null);
    }
    
    $filePath = $existing ? ($existing->file_path ?? $existing->photo_path ?? '') : '';
    $imgUrl = '';
    $fileName = '';
    if ($filePath) {
        $fileName = basename($filePath);
        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
            $imgUrl = $filePath;
        } else {
            $cleanPath = ltrim($filePath, '/');
            $imgUrl = asset($cleanPath);
        }
    }
@endphp

<div class="photo-card-wrapper border border-gray-200 rounded-xl p-3 bg-white shadow-sm hover:shadow transition-all flex flex-col justify-between" id="photo-card-{{ $idx }}">
    <div class="mb-2">
        <span class="text-xs font-bold text-zendo-navy block truncate" title="{{ $label }}">{{ $label }}</span>
    </div>

    {{-- Dropzone / Thumbnail Box --}}
    <div class="relative w-full aspect-square rounded-lg overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center cursor-pointer group hover:border-zendo-navy transition-colors"
         onclick="document.getElementById('photo-input-{{ $idx }}').click()">
        
        {{-- Preview Image --}}
        <img id="photo-img-preview-{{ $idx }}" 
             src="{{ $imgUrl }}" 
             alt="{{ $label }}" 
             class="w-full h-full object-cover {{ $imgUrl ? '' : 'hidden' }}">

        {{-- Dropzone Placeholder --}}
        <div id="photo-dropzone-{{ $idx }}" class="flex flex-col items-center justify-center p-2 text-center {{ $imgUrl ? 'hidden' : '' }}">
            <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-[11px] font-semibold text-gray-700">Click to Upload</span>
            <span class="text-[9px] text-gray-400">JPG, PNG, WebP</span>
        </div>

        {{-- Hover overlay --}}
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <span class="text-white text-xs font-semibold px-2 py-1 rounded bg-black/60 shadow">Change Photo</span>
        </div>
    </div>

    {{-- File Name badge & Actions --}}
    <div id="photo-meta-{{ $idx }}" class="mt-2 text-center {{ $imgUrl ? '' : 'hidden' }}">
        <p id="photo-filename-{{ $idx }}" class="text-[10px] text-gray-600 font-mono truncate px-1" title="{{ $fileName }}">{{ $fileName }}</p>
        <div class="flex items-center justify-center gap-2 mt-1">
            <button type="button" 
                    onclick="document.getElementById('photo-input-{{ $idx }}').click()" 
                    class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                Change
            </button>
            <span class="text-gray-300">|</span>
            <button type="button" 
                    onclick="clearPhotoCard({{ $idx }})" 
                    class="text-[11px] font-semibold text-red-600 hover:text-red-800 transition-colors">
                Remove
            </button>
        </div>
    </div>

    {{-- Hidden File Input --}}
    <input type="file" 
           name="photo_{{ $idx }}" 
           id="photo-input-{{ $idx }}" 
           accept="image/*,.pdf" 
           class="hidden photo-input-control" 
           onchange="previewPhotoCard(this, {{ $idx }})">
           
    <input type="hidden" name="remove_photos[]" id="remove-photo-input-{{ $idx }}" value="{{ $existing ? $existing->id : '' }}" disabled>
</div>
