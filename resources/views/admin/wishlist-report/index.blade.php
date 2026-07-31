@extends('layouts.admin')
@section('title', 'Wishlist Report - ZendoIndia Admin')
@section('page-title', 'Wishlist Report')

@section('content')
<div class="space-y-6">

    {{-- Top Header & Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Saved --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Wishlisted</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-zendo-gold flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-zendo-navy font-heading">{{ number_format($summary['total_saved']) }}</div>
            <p class="text-xs text-gray-400 mt-1">Total property saves across platform</p>
        </div>

        {{-- Unique Users --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unique Users</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-zendo-navy font-heading">{{ number_format($summary['unique_users']) }}</div>
            <p class="text-xs text-gray-400 mt-1">Users with at least 1 saved item</p>
        </div>

        {{-- Regular Properties --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saved Properties</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-zendo-navy font-heading">{{ number_format($summary['regular_properties_count']) }}</div>
            <p class="text-xs text-gray-400 mt-1">Website property listings</p>
        </div>

        {{-- Warehousing Entries --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warehousing Entries</span>
                <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-zendo-navy font-heading">{{ number_format($summary['property_entries_count']) }}</div>
            <p class="text-xs text-gray-400 mt-1">Commercial & warehousing entries</p>
        </div>

    </div>

    {{-- Top Saved / Most Popular Widget --}}
    @if($topSavedEntries->count() > 0 || $topSavedProperties->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Most Saved Warehousing Entries --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-zendo-navy mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-zendo-gold" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    Most Saved Warehousing Entries
                </h3>
                <div class="space-y-3">
                    @forelse($topSavedEntries as $top)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <span class="w-6 h-6 rounded-full bg-zendo-navy text-white text-xs font-bold flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <a href="{{ route('property-entries.show', $top->property_entry_code) }}" target="_blank"
                                       class="text-sm font-semibold text-zendo-navy hover:underline font-mono">
                                        {{ $top->property_entry_code }}
                                    </a>
                                    <p class="text-xs text-gray-500">
                                        {{ $top->propertyEntry?->nearest_city ?? 'Commercial Entry' }}
                                        @if($top->propertyEntry?->facility_type)
                                            • {{ $top->propertyEntry->facility_type }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                                {{ $top->count }} {{ Str::plural('save', $top->count) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">No entry saves recorded yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Most Saved Regular Properties --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-zendo-navy mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-zendo-gold" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    Most Saved Website Properties
                </h3>
                <div class="space-y-3">
                    @forelse($topSavedProperties as $topP)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <span class="w-6 h-6 rounded-full bg-zendo-gold text-white text-xs font-bold flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    @if($topP->property)
                                        <a href="{{ route('properties.show', $topP->property) }}" target="_blank"
                                           class="text-sm font-semibold text-zendo-navy hover:underline">
                                            {{ Str::limit($topP->property->title, 32) }}
                                        </a>
                                        <p class="text-xs text-gray-500">
                                            {{ $topP->property->city?->name ?? 'Property' }}
                                        </p>
                                    @else
                                        <span class="text-sm text-gray-400">Property #{{ $topP->property_id }} (Removed)</span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                                {{ $topP->count }} {{ Str::plural('save', $topP->count) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">No property saves recorded yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    @endif

    {{-- Filters Bar --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.wishlist-report.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">

            {{-- Search Input --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by user name, email, phone, code..."
                           class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-zendo-gold focus:border-zendo-gold">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Item Type Filter --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-zendo-gold focus:border-zendo-gold">
                    <option value="">All Types</option>
                    <option value="entry" {{ request('type') === 'entry' ? 'selected' : '' }}>Warehousing Entries</option>
                    <option value="property" {{ request('type') === 'property' ? 'selected' : '' }}>Regular Properties</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Wishlist Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-zendo-gold focus:border-zendo-gold">
                    <option value="">All Records (Active + Removed)</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Saved Only</option>
                    <option value="removed" {{ request('status') === 'removed' ? 'selected' : '' }}>Removed Only (Soft Deleted)</option>
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:ring-zendo-gold focus:border-zendo-gold">
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 lg:col-span-1">
                <button type="submit" class="flex-1 bg-zendo-navy text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition-all shadow">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'type', 'status', 'from_date', 'to_date']))
                    <a href="{{ route('admin.wishlist-report.index') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Wishlists Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        @if($wishlists->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <p class="text-gray-500 font-medium text-sm mb-1">No wishlist items found</p>
                <p class="text-xs text-gray-400">Try adjusting your filters or search term</p>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wishlisted Item</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wishlist Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Saved On</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($wishlists as $wishlist)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- # --}}
                                <td class="px-5 py-4 text-sm text-gray-500">
                                    {{ $loop->iteration + ($wishlists->currentPage() - 1) * $wishlists->perPage() }}
                                </td>

                                {{-- User --}}
                                <td class="px-5 py-4">
                                    @if($wishlist->user)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-zendo-navy text-white text-xs font-bold flex items-center justify-center">
                                                {{ strtoupper(substr($wishlist->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $wishlist->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $wishlist->user->email ?? $wishlist->user->phone ?? '—' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">User #{{ $wishlist->user_id }}</span>
                                    @endif
                                </td>

                                {{-- Wishlisted Item --}}
                                <td class="px-5 py-4">
                                    @if($wishlist->property_entry_code)
                                        <div>
                                            <span class="text-sm font-mono font-bold text-zendo-navy">{{ $wishlist->property_entry_code }}</span>
                                            @if($wishlist->propertyEntry)
                                                <p class="text-xs text-gray-600 mt-0.5">
                                                    {{ $wishlist->propertyEntry->nearest_city ?? '—' }}
                                                    @if($wishlist->propertyEntry->facility_type)
                                                        • {{ $wishlist->propertyEntry->facility_type }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    @elseif($wishlist->property)
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $wishlist->property->title }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $wishlist->property->city?->name ?? '—' }}</p>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Item no longer available</span>
                                    @endif
                                </td>

                                {{-- Property / Entry Status --}}
                                <td class="px-5 py-4">
                                    @if($wishlist->property_entry_code)
                                        @if($wishlist->propertyEntry)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $wishlist->propertyEntry->status_badge_class }}">
                                                {{ $wishlist->propertyEntry->status_label }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                Entry Deleted
                                            </span>
                                        @endif
                                    @elseif($wishlist->property)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $wishlist->property->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $wishlist->property->is_active ? 'Published' : 'Inactive' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Wishlist Status --}}
                                <td class="px-5 py-4">
                                    @if($wishlist->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                            &#10007; Removed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                            &#10003; Active Saved
                                        </span>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td class="px-5 py-4">
                                    @if($wishlist->property_entry_code)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Warehousing Entry
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            Regular Property
                                        </span>
                                    @endif
                                </td>

                                {{-- Saved On --}}
                                <td class="px-5 py-4 text-sm text-gray-500">
                                    {{ $wishlist->created_at ? $wishlist->created_at->format('d M Y, h:i A') : '—' }}
                                    <span class="block text-xs text-gray-400">{{ $wishlist->created_at?->diffForHumans() }}</span>
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-4 text-right">
                                    @if($wishlist->property_entry_code)
                                        <a href="{{ route('property-entries.show', $wishlist->property_entry_code) }}" target="_blank"
                                           class="inline-flex items-center text-xs font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                                            View Entry &rarr;
                                        </a>
                                    @elseif($wishlist->property)
                                        <a href="{{ route('properties.show', $wishlist->property) }}" target="_blank"
                                           class="inline-flex items-center text-xs font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                                            View Property &rarr;
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile View Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($wishlists as $wishlist)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-gray-400">#{{ $loop->iteration + ($wishlists->currentPage() - 1) * $wishlists->perPage() }}</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $wishlist->user?->name ?? 'User #' . $wishlist->user_id }}</span>
                            </div>
                            @if($wishlist->property_entry_code)
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    Entry
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    Property
                                </span>
                            @endif
                        </div>

                        <div>
                            @if($wishlist->property_entry_code)
                                <a href="{{ route('property-entries.show', $wishlist->property_entry_code) }}" target="_blank"
                                   class="text-sm font-mono font-bold text-zendo-navy hover:underline">
                                    {{ $wishlist->property_entry_code }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $wishlist->propertyEntry?->nearest_city }}</p>
                            @elseif($wishlist->property)
                                <a href="{{ route('properties.show', $wishlist->property) }}" target="_blank"
                                   class="text-sm font-semibold text-gray-900 hover:underline">
                                    {{ $wishlist->property->title }}
                                </a>
                            @endif
                        </div>

                        <div class="flex items-center justify-between text-xs text-gray-400 pt-1">
                            <span>Saved {{ $wishlist->created_at?->format('d M Y') }}</span>
                            <span>{{ $wishlist->user?->phone ?? $wishlist->user?->email }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($wishlists->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $wishlists->links() }}
                </div>
            @endif

        @endif

    </div>

</div>
@endsection
