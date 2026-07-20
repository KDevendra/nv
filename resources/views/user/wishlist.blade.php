@extends('layouts.user')

@section('title', 'My Wishlist - ZendoIndia')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-heading text-zendo-navy mb-2">My Wishlist</h1>
        <p class="text-gray-600">Properties you've saved for later viewing.</p>
    </div>

    @if($wishlists->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wishlists as $wishlist)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            @if($wishlist->property)
                {{-- Regular Property --}}
                @php
                    $property = $wishlist->property;
                @endphp
                
                <a href="{{ route('properties.show', $property->slug) }}" class="block">
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        @if($property->mainImage)
                            <img src="{{ asset('storage/' . $property->mainImage->image_path) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-xs text-gray-400 font-medium">No Image Available</span>
                            </div>
                        @endif
                        
                        @if($property->projectStatus)
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-zendo-gold text-white text-xs font-semibold rounded-full">
                                {{ $property->projectStatus->name }}
                            </span>
                        </div>
                        @endif
                    </div>
                </a>

                <div class="p-5">
                    <a href="{{ route('properties.show', $property->slug) }}" class="block mb-3">
                        <h3 class="text-lg font-heading font-semibold text-zendo-navy mb-1 hover:text-zendo-gold transition-colors">
                            {{ $property->title }}
                        </h3>
                        <p class="text-sm text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $property->location->name ?? 'Location' }}, {{ $property->city->name ?? 'City' }}
                        </p>
                    </a>

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Starting Price</p>
                            <p class="text-xl font-bold text-zendo-gold">₹{{ number_format($property->price) }}</p>
                        </div>
                        @if($property->bhk)
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Configuration</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $property->bhk->name }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('properties.show', $property->slug) }}" class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                            View Details →
                        </a>
                        <button type="button" class="remove-wishlist-btn text-sm font-medium text-red-600 hover:text-red-700 transition-colors" data-property-id="{{ $property->id }}" data-entry-code="">
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
            @elseif($wishlist->propertyEntry)
                {{-- Property Entry --}}
                @php
                    $entry = $wishlist->propertyEntry;
                @endphp
                
                <a href="{{ route('property-entries.show', $entry->code) }}" class="block">
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        @if($entry->photos->first())
                            <img src="{{ asset('storage/' . $entry->photos->first()->photo_path) }}" 
                                 alt="{{ $entry->property_name ?? $entry->facility_type }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-xs text-gray-400 font-medium">No Image Available</span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-zendo-gold text-white text-xs font-semibold rounded-full">
                                Entry #{{ $entry->code }}
                            </span>
                        </div>
                    </div>
                </a>

                <div class="p-5">
                    <a href="{{ route('property-entries.show', $entry->code) }}" class="block mb-3">
                        <h3 class="text-lg font-heading font-semibold text-zendo-navy mb-1 hover:text-zendo-gold transition-colors">
                            {{ $entry->property_name ?? $entry->facility_type }}
                        </h3>
                        <p class="text-sm text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $entry->nearest_city }}
                        </p>
                    </a>

                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Facility Type</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $entry->facility_type }}</p>
                        </div>
                        @if($entry->built_up_area)
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Area</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $entry->built_up_area }} sq ft</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('property-entries.show', $entry->code) }}" class="text-sm font-medium text-zendo-navy hover:text-zendo-gold transition-colors">
                            View Details →
                        </a>
                        <button type="button" class="remove-wishlist-btn text-sm font-medium text-red-600 hover:text-red-700 transition-colors" data-property-id="" data-entry-code="{{ $entry->code }}">
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($wishlists->hasPages())
    <div class="mt-8">
        {{ $wishlists->links() }}
    </div>
    @endif

    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-heading font-semibold text-gray-900 mb-2">Your Wishlist is Empty</h3>
        <p class="text-gray-600 mb-6">Start exploring properties and save your favorites here for easy access.</p>
        <a href="{{ route('properties.index') }}" class="inline-flex items-center px-6 py-3 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Properties
        </a>
    </div>
    @endif

</div>

<!-- Remove from Wishlist Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.remove-wishlist-btn');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const propertyId = this.dataset.propertyId || null;
            const entryCode = this.dataset.entryCode || null;
            const card = this.closest('.bg-white');
            
            if (confirm('Are you sure you want to remove this property from your wishlist?')) {
                // Disable button during request
                this.disabled = true;
                
                fetch('{{ route("user.wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        property_id: propertyId,
                        property_entry_code: entryCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && !data.added) {
                        // Remove the card with animation
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        card.style.transition = 'all 0.3s ease';
                        
                        setTimeout(() => {
                            card.remove();
                            
                            // Check if wishlist is now empty and reload page
                            const remainingCards = document.querySelectorAll('.remove-wishlist-btn').length;
                            if (remainingCards === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to remove from wishlist');
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    this.disabled = false;
                });
            }
        });
    });
});
</script>
@endsection
