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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Add User
            </a>
            @endCanDo
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Name or email..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-zendo-gold focus:border-zendo-gold">
                    </div>
                    
                    <!-- Role Filter -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="role" name="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-zendo-gold focus:border-zendo-gold select2-filter-role">
                            <option value="">All Roles</option>
                            @foreach(App\Models\User::ROLES as $value => $label)
                                <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Supply Head Filter -->
                    <div>
                        <label for="supply_head_id" class="block text-sm font-medium text-gray-700 mb-2">Supply Head</label>
                        <select id="supply_head_id" name="supply_head_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-zendo-gold focus:border-zendo-gold select2-filter-supply">
                            <option value="">All Supply Heads</option>
                            @foreach($supplyHeads as $head)
                                <option value="{{ $head->id }}" {{ request('supply_head_id') == $head->id ? 'selected' : '' }}>
                                    {{ $head->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-zendo-gold focus:border-zendo-gold select2-filter-status">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition-colors">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reports To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-zendo-gold rounded-full mr-3 flex items-center justify-center">
                                            <span
                                                class="text-white text-xs font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if ($user->id === auth()->id())
                                                <div class="text-xs text-zendo-gold font-medium">(You)</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-purple-100 text-purple-800',
                                            'admin' => 'bg-blue-100 text-blue-800',
                                            'supply_head' => 'bg-green-100 text-green-800',
                                            'field_officer' => 'bg-orange-100 text-orange-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ App\Models\User::ROLES[$user->role] ?? $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->isFieldOfficer() && $user->supplyHead)
                                        <div class="text-sm text-gray-900">{{ $user->supplyHead->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->supplyHead->email }}</div>
                                    @elseif($user->isSupplyHead())
                                        <div class="text-sm text-gray-600">
                                            {{ $user->fieldOfficers->count() }} Field Officer(s)
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="text-blue-600 hover:text-blue-900 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        @canDo('users.edit')
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @endCanDo
                                        @if ($user->id !== auth()->id() || !$user->is_active)
                                            @canDo('users.edit')
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="text-{{ $user->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $user->is_active ? 'orange' : 'green' }}-900 transition-colors"
                                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
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
                                        @if ($user->id !== auth()->id())
                                            @canDo('users.delete')
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No users found</p>
                                    <p class="mt-1">Get started by creating your first user.</p>
                                    <a href="{{ route('admin.users.create') }}"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add First User
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
            @forelse($users as $user)
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center flex-1">
                            <div class="w-10 h-10 bg-zendo-gold rounded-full mr-3 flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}</h3>
                                @if ($user->id === auth()->id())
                                    <span class="text-xs text-zendo-gold font-medium">(You)</span>
                                @endif
                                <p class="text-sm text-gray-600 mb-2">{{ $user->email }}</p>
                                <div class="flex items-center space-x-2 mb-2">
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-purple-100 text-purple-800',
                                            'admin' => 'bg-blue-100 text-blue-800',
                                            'supply_head' => 'bg-green-100 text-green-800',
                                            'field_officer' => 'bg-orange-100 text-orange-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ App\Models\User::ROLES[$user->role] ?? $user->role }}
                                    </span>
                                </div>
                                
                                @if($user->isFieldOfficer() && $user->supplyHead)
                                    <div class="text-sm text-gray-600 mb-2">
                                        Reports to: <span class="font-medium">{{ $user->supplyHead->name }}</span>
                                    </div>
                                @elseif($user->isSupplyHead())
                                    <div class="text-sm text-gray-600 mb-2">
                                        Manages {{ $user->fieldOfficers->count() }} field officer(s)
                                    </div>
                                @endif
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        @if($user->is_active)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            View
                        </a>
                        @canDo('users.edit')
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                        @endCanDo
                        @if ($user->id !== auth()->id() || !$user->is_active)
                            @canDo('users.edit')
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-{{ $user->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $user->is_active ? 'orange' : 'green' }}-800 transition-colors"
                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                    @if($user->is_active)
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12"></path>
                                        </svg>
                                        Deactivate
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activate
                                    @endif
                                </button>
                            </form>
                            @endCanDo
                        @endif
                        @if ($user->id !== auth()->id())
                            @canDo('users.delete')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                            @endCanDo
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    <p class="text-lg font-medium text-gray-900">No users found</p>
                    <p class="mt-1 text-gray-600">Get started by creating your first user.</p>
                    <a href="{{ route('admin.users.create') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First User
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div>
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #f59e0b;
        box-shadow: 0 0 0 1px rgba(245, 158, 11, 0.2);
    }
    </style>

    <script>
    $(document).ready(function() {
        // Initialize Select2 for filter dropdowns
        $('.select2-filter-role').select2({
            placeholder: 'All Roles',
            allowClear: true,
            width: '100%'
        });
        
        $('.select2-filter-supply').select2({
            placeholder: 'All Supply Heads',
            allowClear: true,
            width: '100%'
        });

        $('.select2-filter-status').select2({
            placeholder: 'All Status',
            allowClear: true,
            width: '100%'
        });
    });
    </script>
@endsection
