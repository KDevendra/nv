@extends('layouts.admin')

@section('title', 'Properties Management - ZendoIndia Admin')

@section('page-title', 'Properties Management')
@section('page-description')
    Manage all properties in the system. Create, edit, view, and delete property listings.
@endsection

@section('content')
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">All Properties</h2>
            <p class="text-gray-600 mt-1">Total {{ $properties->total() }} properties in the system</p>
        </div>
        @canDo('properties.create')
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.properties.trash') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Trash
                @php $trashCount = \App\Models\Property::onlyTrashed()->count(); @endphp
                @if($trashCount > 0)
                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">{{ $trashCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.properties.create') }}"
                class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Property
            </a>
        </div>
        @endCanDo
    </div>

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.properties.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Search by title, address, or description..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- City Filter -->
                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <select name="city_id" id="city_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">All Cities</option>
                        @foreach (\App\Models\City::active()->ordered()->get() as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type Filter -->
                <div>
                    <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                    <select name="property_type_id" id="property_type_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach (\App\Models\PropertyType::active()->ordered()->get() as $type)
                            <option value="{{ $type->id }}"
                                {{ request('property_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Featured Filter -->
                <div>
                    <label for="featured" class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                    <select name="featured" id="featured"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="">All</option>
                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                        <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Non-Featured</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort_by" id="sort_by"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                        <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High
                            to Low</option>
                        <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to
                            High</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-zendo-gold text-white font-medium rounded-lg hover:bg-zendo-navy transition-colors">
                        Apply
                    </button>
                    <a href="{{ route('admin.properties.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Properties Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($properties as $property)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @php
                                        $imageUrl = null;
                                        if ($property->mainImage) {
                                            $imageUrl = asset($property->mainImage->image_path);
                                        } elseif ($property->images->first()) {
                                            $imageUrl = asset($property->images->first()->image_path);
                                        }
                                    @endphp

                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $property->mainImage->alt_tag ?? $property->title }}"
                                            class="w-16 h-16 object-cover rounded-lg mr-4 border border-gray-200">
                                    @else
                                        <div
                                            class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center border border-gray-300">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($property->title, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ $property->propertyType->name ?? 'N/A' }} •
                                            {{ $property->bhk->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $property->location->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $property->city->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">₹{{ number_format($property->price) }}
                                </div>
                                @if ($property->price_per_sqft)
                                    <div class="text-xs text-gray-500">
                                        ₹{{ number_format($property->price_per_sqft) }}/sqft</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $property->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if ($property->is_featured)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Featured
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.properties.show', $property) }}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    @canDo('properties.edit')
                                    <a href="{{ route('admin.properties.edit', $property) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    @endCanDo
                                    @canDo('properties.delete')
                                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Move this property to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Move to Trash">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endCanDo
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">No properties found</p>
                                <p class="mt-1">Get started by creating your first property.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($properties as $property)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-4">
                        @php
                            $imageUrl = null;
                            if ($property->mainImage) {
                                $imageUrl = asset($property->mainImage->image_path);
                            } elseif ($property->images->first()) {
                                $imageUrl = asset($property->images->first()->image_path);
                            }
                        @endphp

                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $property->mainImage->alt_tag ?? $property->title }}"
                                class="w-20 h-20 object-cover rounded-lg flex-shrink-0 border border-gray-200">
                        @else
                            <div
                                class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center border border-gray-300">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $property->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $property->propertyType->name ?? 'N/A' }} •
                                {{ $property->bhk->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $property->location->name ?? 'N/A' }},
                                {{ $property->city->name ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-2">₹{{ number_format($property->price) }}</p>

                            <div class="flex flex-wrap gap-1 mt-2">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $property->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if ($property->is_featured)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3 mt-3">
                                <a href="{{ route('admin.properties.show', $property) }}"
                                    class="text-blue-600 hover:text-blue-900 text-xs font-medium">View</a>
                                @canDo('properties.edit')
                                <a href="{{ route('admin.properties.edit', $property) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">Edit</a>
                                @endCanDo
                                @canDo('properties.delete')
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Move this property to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 text-xs font-medium">Trash</button>
                                </form>
                                @endCanDo
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <p class="text-lg font-medium">No properties found</p>
                    <p class="mt-1">Get started by creating your first property.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $properties->links() }}
    </div>
@endsection
