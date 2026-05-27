@extends('layouts.admin')

@section('title', 'SEO Meta Management')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-heading text-zendo-navy font-semibold">SEO Meta Management</h2>
                <p class="text-gray-600 mt-1 text-sm">Manage meta titles, descriptions, keywords, and schema markup for all pages</p>
            </div>
            @canDo('seo-metas.create')
            <a href="{{ route('admin.seo-metas.create') }}"
                class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add SEO Meta
            </a>
            @endCanDo
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop/Tablet Table -->
        <div class="hidden lg:block bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-1/6 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                            <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Title</th>
                            <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="w-1/5 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keywords</th>
                            <th class="w-28 px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($seoMetas as $seo)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $pageOptions[$seo->page_key] ?? $seo->page_key }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $seo->page_key }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $seo->title }}">{{ $seo->title ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $seo->description }}">{{ $seo->description ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $seo->keywords }}">{{ $seo->keywords ?? '—' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($seo->isStatic())
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700" title="Static page - cannot be deleted">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                            </span>
                                        @endif
                                        {{-- @canDo('seo-metas.edit') --}}
                                        <a href="{{ route('admin.seo-metas.edit', $seo) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors p-1" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        {{-- @endCanDo --}}
                                        @if(!$seo->isStatic())
                                            {{-- @canDo('seo-metas.delete') --}}
                                            <form action="{{ route('admin.seo-metas.destroy', $seo) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this SEO meta?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-1" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            {{-- @endCanDo --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No SEO meta entries found</p>
                                    <p class="mt-1">Get started by adding SEO meta for your pages.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($seoMetas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $seoMetas->links() }}
                </div>
            @endif
        </div>

        <!-- Tablet View (md only) -->
        <div class="hidden md:block lg:hidden bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                            <th class="w-2/5 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Title</th>
                            <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="w-24 px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($seoMetas as $seo)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $pageOptions[$seo->page_key] ?? $seo->page_key }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $seo->page_key }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-600 truncate" title="{{ $seo->title }}">{{ $seo->title ?? '—' }}</div>
                                    <div class="text-xs text-gray-400 truncate mt-0.5" title="{{ $seo->description }}">{{ Str::limit($seo->description, 50) ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($seo->title && $seo->description)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Complete</span>
                                    @elseif($seo->title || $seo->description)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Empty</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        {{-- @canDo('seo-metas.edit') --}}
                                        <a href="{{ route('admin.seo-metas.edit', $seo) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors p-1" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        {{-- @endCanDo --}}
                                        {{-- @if(!$seo->isStatic()) --}}
                                            {{-- @canDo('seo-metas.delete') --}}
                                            <form action="{{ route('admin.seo-metas.destroy', $seo) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Delete this SEO meta?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors p-1" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            {{-- @endCanDo --}}
                                        {{-- @endif --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    <p class="text-lg font-medium">No SEO meta entries found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($seoMetas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $seoMetas->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @forelse($seoMetas as $seo)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $pageOptions[$seo->page_key] ?? $seo->page_key }}</h3>
                                @if($seo->isStatic())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400">{{ $seo->page_key }}</p>
                        </div>
                        <div class="flex items-center space-x-1 flex-shrink-0">
                            {{-- @canDo('seo-metas.edit') --}}
                            <a href="{{ route('admin.seo-metas.edit', $seo) }}" class="text-indigo-600 p-1.5 rounded hover:bg-indigo-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            {{-- @endCanDo --}}
                            @if(!$seo->isStatic())
                                {{-- @canDo('seo-metas.delete') --}}
                                <form action="{{ route('admin.seo-metas.destroy', $seo) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete this SEO meta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 p-1.5 rounded hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                {{-- @endCanDo --}}
                            @endif
                        </div>
                    </div>
                    @if($seo->title)
                        <p class="text-xs text-gray-600 truncate"><span class="font-medium text-gray-700">Title:</span> {{ $seo->title }}</p>
                    @endif
                    @if($seo->description)
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2"><span class="font-medium text-gray-700">Desc:</span> {{ $seo->description }}</p>
                    @endif
                    @if($seo->keywords)
                        <p class="text-xs text-gray-500 mt-1 truncate"><span class="font-medium text-gray-700">Keywords:</span> {{ $seo->keywords }}</p>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                    <svg class="mx-auto h-10 w-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-sm font-medium">No SEO meta entries found</p>
                    <p class="mt-1 text-xs">Get started by adding SEO meta for your pages.</p>
                </div>
            @endforelse
            @if ($seoMetas->hasPages())
                <div class="mt-4">{{ $seoMetas->links() }}</div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
