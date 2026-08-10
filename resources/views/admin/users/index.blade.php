@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-heading text-zendo-navy font-semibold">Users</h2>
            <p class="text-gray-600 mt-1">Manage all users in the system</p>
        </div>
        @canDo('users.create')
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add User
        </a>
        @endCanDo
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-5">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <!-- Row 1: Search, Role, Supply Head, Region -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Name or email..."
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Roles</option>
                        @foreach(App\Models\User::ROLES as $value => $label)
                            <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Division Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Division</label>
                    <select name="division"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Divisions</option>
                        @foreach(App\Models\User::DIVISIONS as $value => $label)
                            <option value="{{ $value }}" {{ request('division') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Zone Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Zone</label>
                    <select name="zone_id"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Region Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Region</label>
                    <select name="region_id" id="filter_region_id"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Regions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 2: Area, Status, Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Area Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Area</label>
                    <select name="area_id" id="filter_area_id"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Empty space to align buttons to the right -->
                <div class="hidden lg:block"></div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-5 py-2.5 bg-zendo-gold text-white text-sm font-bold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 px-5 py-2.5 border-2 border-red-500 bg-white text-red-600 text-sm font-bold rounded-lg hover:bg-red-50 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Division</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region / Area</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zone(s)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $roleColors = [
                            'super_admin' => 'bg-purple-100 text-purple-800',
                            'admin'       => 'bg-blue-100 text-blue-800',
                            'supply_head' => 'bg-green-100 text-green-800',
                            'field_officer' => 'bg-orange-100 text-orange-800',
                        ];
                    @endphp
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- User -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-zendo-gold rounded-full mr-3 flex-shrink-0 flex items-center justify-center">
                                        <span class="text-white text-xs font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="text-xs text-zendo-gold font-medium">(You)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ App\Models\User::ROLES[$user->role] ?? $user->role }}
                                </span>
                            </td>

                            <!-- Division -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($user->division)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ App\Models\User::DIVISIONS[$user->division] ?? ucfirst($user->division) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Region / Area -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($user->region)
                                    <div class="text-sm text-gray-900">{{ $user->region->name }}</div>
                                    @if($user->area)
                                        <div class="text-xs text-gray-500">{{ $user->area->name }}</div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Zone(s) -->
                            <td class="px-4 py-4">
                                @if($user->isFieldOfficer() && $user->zone)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $user->zone->name }}
                                    </span>
                                @elseif($user->isSupplyHead() && $user->zones->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($user->zones as $zone)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700">
                                                {{ $zone->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>

                            <!-- Joined -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @canDo('users.edit')
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endCanDo
                                    @if($user->id !== auth()->id() || !$user->is_active)
                                        @canDo('users.edit')
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-{{ $user->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $user->is_active ? 'orange' : 'green' }}-900 transition-colors"
                                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                @if($user->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                        @endCanDo
                                    @endif
                                    @if($user->id !== auth()->id())
                                        @canDo('users.delete')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endCanDo
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <p class="text-lg font-medium">No users found</p>
                                <p class="mt-1">Try adjusting your filters or create a new user.</p>
                                @canDo('users.create')
                                <a href="{{ route('admin.users.create') }}"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90">
                                    Add User
                                </a>
                                @endCanDo
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @php
            $roleColors = [
                'super_admin'   => 'bg-purple-100 text-purple-800',
                'admin'         => 'bg-blue-100 text-blue-800',
                'supply_head'   => 'bg-green-100 text-green-800',
                'field_officer' => 'bg-orange-100 text-orange-800',
            ];
        @endphp
        @forelse($users as $user)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex justify-between items-start gap-3">
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div class="w-10 h-10 bg-zendo-gold rounded-full flex-shrink-0 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h3>
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-zendo-gold font-medium">(You)</span>
                                @endif
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $user->email }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ App\Models\User::ROLES[$user->role] ?? $user->role }}
                                </span>
                                @if($user->region)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $user->region->name }}{{ $user->area ? ' › ' . $user->area->name : '' }}
                                    </span>
                                @endif
                            </div>
                            @if($user->isFieldOfficer() && $user->zone)
                                <p class="text-xs text-gray-600 mt-1">Zone: <span class="font-medium">{{ $user->zone->name }}</span></p>
                            @elseif($user->isSupplyHead() && $user->zones->isNotEmpty())
                                <p class="text-xs text-gray-600 mt-1">Zones: <span class="font-medium">{{ $user->zones->pluck('name')->join(', ') }}</span></p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Joined {{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 mt-3 border-t border-gray-100">
                    <a href="{{ route('admin.users.show', $user) }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View
                    </a>
                    @canDo('users.edit')
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs text-indigo-600 hover:text-indigo-800 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endCanDo
                    @if($user->id !== auth()->id() || !$user->is_active)
                        @canDo('users.edit')
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 text-xs text-{{ $user->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $user->is_active ? 'orange' : 'green' }}-800 transition-colors">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        @endCanDo
                    @endif
                    @if($user->id !== auth()->id())
                        @canDo('users.delete')
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                            onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 text-xs text-red-600 hover:text-red-800 transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                        @endCanDo
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500">No users found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div>{{ $users->links() }}</div>
    @endif
</div>
@endsection
