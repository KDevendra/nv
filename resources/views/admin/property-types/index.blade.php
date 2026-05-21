@extends('layouts.admin')

@section('title', 'Property Types Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Property Types</h2>
            <p class="text-gray-600 mt-1">Manage property types and their service mappings</p>
        </div>
        @canDo('property-types.create')
        <a href="{{ route('admin.property-types.create') }}"
            class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Property Type
        </a>
        @endCanDo
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mappings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In Header</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($propertyTypes as $propertyType)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-zendo-gold rounded-full mr-3 flex items-center justify-center">
                                        <span class="text-white text-xs font-semibold">{{ substr($propertyType->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $propertyType->name }}</div>
                                        @if($propertyType->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($propertyType->description, 40) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $propertyType->service_types_count }} service types
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $propertyType->bhks_count }} BHKs
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @canDo('property-types.edit')
                                <form action="{{ route('admin.property-types.toggle-status', $propertyType) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $propertyType->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $propertyType->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                                @endCanDo
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($propertyType->show_in_header)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $propertyType->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $propertyType->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.property-types.show', $propertyType) }}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @canDo('property-types.edit')
                                    <a href="{{ route('admin.property-types.edit', $propertyType) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endCanDo
                                    @canDo('property-types.delete')
                                    <form action="{{ route('admin.property-types.destroy', $propertyType) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this property type? This will also remove all service type mappings.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endCanDo
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <p class="text-lg font-medium">No property types found</p>
                                <p class="mt-1">Get started by creating your first property type.</p>
                                <a href="{{ route('admin.property-types.create') }}"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add First Property Type
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($propertyTypes as $propertyType)
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center flex-1">
                        <div class="w-10 h-10 bg-zendo-gold rounded-full mr-3 flex items-center justify-center">
                            <span class="text-white font-semibold">{{ substr($propertyType->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $propertyType->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">
                                @if($propertyType->category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $propertyType->category === 'residential' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800' }} mr-2">
                                        {{ ucfirst($propertyType->category) }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $propertyType->service_types_count }} service types
                                </span>
                            </p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>Order: {{ $propertyType->sort_order }}</span>
                                <span>{{ $propertyType->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @canDo('property-types.edit')
                    <form action="{{ route('admin.property-types.toggle-status', $propertyType) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $propertyType->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                            {{ $propertyType->status ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                    @endCanDo
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.property-types.show', $propertyType) }}"
                        class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View
                    </a>
                    @canDo('property-types.edit')
                    <a href="{{ route('admin.property-types.edit', $propertyType) }}"
                        class="inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endCanDo
                    @canDo('property-types.delete')
                    <form action="{{ route('admin.property-types.destroy', $propertyType) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this property type?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endCanDo
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <p class="text-lg font-medium text-gray-900">No property types found</p>
                <p class="mt-1 text-gray-600">Get started by creating your first property type.</p>
                <a href="{{ route('admin.property-types.create') }}"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add First Property Type
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($propertyTypes->hasPages())
        <div class="flex justify-center">
            {{ $propertyTypes->links() }}
        </div>
    @endif
</div>
@endsection
