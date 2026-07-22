@extends('layouts.admin')

@section('title', 'Properties Trash')

@section('content')
    @if (session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Properties Trash</h2>
            <p class="text-gray-600 mt-1">{{ $properties->total() }} trashed {{ Str::plural('property', $properties->total()) }}</p>
        </div>
        <a href="{{ route('admin.properties.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Properties
        </a>
    </div>

    <!-- Search -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.properties.trash') }}">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search trashed properties..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="px-4 py-2 bg-zendo-gold text-white rounded-lg hover:bg-zendo-navy transition-colors">Search</button>
                <a href="{{ route('admin.properties.trash') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Desktop -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trashed At</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($properties as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($property->mainImage)
                                        <img src="{{ asset('storage/' . $property->mainImage->image_path) }}"
                                            class="w-14 h-14 object-cover rounded-lg mr-4 border border-gray-200 opacity-60">
                                    @else
                                        <div class="w-14 h-14 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-700">{{ Str::limit($property->title, 40) }}</div>
                                        <div class="text-xs text-gray-400">{{ $property->propertyType?->name ?? 'N/A' }} • {{ $property->bhk?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $property->location?->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">{{ $property->city?->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                ₹{{ number_format($property->price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $property->deleted_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <!-- Restore -->
                                    @canDo('properties.restore')
                                    <form action="{{ route('admin.properties.restore', $property->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Restore
                                        </button>
                                    </form>
                                    @endCanDo
                                    <!-- Permanent Delete -->
                                    @canDo('properties.delete')
                                    <form action="{{ route('admin.properties.force-delete', $property->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Permanently delete this property? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Forever
                                        </button>
                                    </form>
                                    @endCanDo
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <p class="text-lg font-medium">Trash is empty</p>
                                <p class="mt-1 text-sm">No trashed properties found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($properties as $property)
                <div class="p-4">
                    <div class="flex items-start space-x-3 mb-3">
                        @if($property->mainImage)
                            <img src="{{ asset('storage/' . $property->mainImage->image_path) }}"
                                class="w-16 h-16 object-cover rounded-lg flex-shrink-0 opacity-60">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-700 truncate">{{ $property->title }}</h3>
                            <p class="text-xs text-gray-400">{{ $property->location?->name ?? 'N/A' }}, {{ $property->city?->name ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">₹{{ number_format($property->price) }}</p>
                            <p class="text-xs text-gray-400 mt-1">Trashed: {{ $property->deleted_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.properties.restore', $property->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            @canDo('properties.restore')
                            <button type="submit" class="w-full px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs font-medium">
                                Restore
                            </button>
                            @endCanDo
                        </form>
                        @canDo('properties.delete')
                        <form action="{{ route('admin.properties.force-delete', $property->id) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('Permanently delete? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium">
                                Delete Forever
                            </button>
                        </form>
                        @endCanDo
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <p class="text-lg font-medium">Trash is empty</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $properties->links() }}
    </div>
@endsection
