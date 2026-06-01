@extends('layouts.admin')

@section('title', 'Commercial Section Details - ZendoIndia Admin')
@section('page-title', 'Commercial Section Details')
@section('page-description', 'View commercial showcase section details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-heading text-zendo-navy font-semibold">Commercial Section Information</h2>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $commercialSection->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $commercialSection->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                        <p class="text-gray-900">{{ $commercialSection->badge }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-gray-900">{!! $commercialSection->title !!}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <p class="text-gray-900">{{ $commercialSection->subtitle }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gallery Note</label>
                        <p class="text-gray-900">{{ $commercialSection->gallery_note }}</p>
                    </div>
                </div>
            </div>

            <!-- Commercial Points -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Commercial Points</h3>
                <div class="space-y-4">
                    @foreach($commercialSection->formatted_points as $index => $point)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="w-3 h-3 bg-zendo-gold rounded-full mt-2 mr-3 flex-shrink-0"></div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 mb-1">{{ $point['title'] }}</h4>
                                @if(!empty($point['description']))
                                    <p class="text-gray-600 text-sm">{{ $point['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Button Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Button Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary Button</label>
                        <p class="text-gray-900 font-medium">{{ $commercialSection->primary_button_text }}</p>
                        <p class="text-gray-600 text-sm">{{ $commercialSection->primary_button_link }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Button</label>
                        <p class="text-gray-900 font-medium">{{ $commercialSection->secondary_button_text }}</p>
                        <p class="text-gray-600 text-sm">{{ $commercialSection->secondary_button_link }}</p>
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Gallery Images</h3>
                
                @if($commercialSection->uploaded_images && count($commercialSection->uploaded_images) > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Uploaded Images</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($commercialSection->uploaded_images as $image)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="aspect-w-16 aspect-h-9 mb-3">
                                    <img src="{{ asset($image['path']) }}" alt="{{ $image['alt'] }}" 
                                         class="w-full h-32 object-cover rounded-lg">
                                </div>
                                <div class="space-y-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $image['label'] }}</p>
                                    <p class="text-gray-600 text-xs">{{ $image['alt'] }}</p>
                                    <p class="text-gray-500 text-xs">Type: Uploaded</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($commercialSection->gallery_images && count($commercialSection->gallery_images) > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Manual Images</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($commercialSection->gallery_images as $image)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="aspect-w-16 aspect-h-9 mb-3">
                                    <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}" 
                                         class="w-full h-32 object-cover rounded-lg">
                                </div>
                                <div class="space-y-1">
                                    <p class="font-medium text-gray-900 text-sm">{{ $image['label'] }}</p>
                                    <p class="text-gray-600 text-xs">{{ $image['alt'] }}</p>
                                    <p class="text-gray-500 text-xs">Type: Manual</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if((!$commercialSection->uploaded_images || count($commercialSection->uploaded_images) == 0) && (!$commercialSection->gallery_images || count($commercialSection->gallery_images) == 0))
                    <p class="text-gray-500 text-center py-8">No images available</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @canDo('commercial-sections.edit')
<a href="{{ route('admin.commercial-sections.edit', $commercialSection) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-zendo-gold text-white rounded-lg hover:bg-opacity-90 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Section
                    </a>

                    @endCanDo
                    @canDo('commercial-sections.delete')
<form action="{{ route('admin.commercial-sections.toggle-status', $commercialSection) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 {{ $commercialSection->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors">
                            @if($commercialSection->is_active)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                Deactivate
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activate
                            @endif
                        </button>
                    </form>

                    <a href="{{ route('admin.commercial-sections.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
@endCanDo
                </div>
            </div>

            <!-- Section Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-heading text-zendo-navy font-semibold mb-4">Section Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span class="text-gray-900">{{ $commercialSection->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span class="text-gray-900">{{ $commercialSection->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="text-gray-900">{{ $commercialSection->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Points:</span>
                        <span class="text-gray-900">{{ count($commercialSection->formatted_points) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Images:</span>
                        <span class="text-gray-900">{{ count($commercialSection->formatted_gallery_images) }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                <h3 class="text-lg font-heading text-red-600 font-semibold mb-4">Danger Zone</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Once you delete this commercial section, there is no going back. Please be certain.
                </p>
                <form action="{{ route('admin.commercial-sections.destroy', $commercialSection) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this commercial section? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Section
                    </button>
                </form>
@endCanDo
            </div>
        </div>
    </div>
</div>
@endsection