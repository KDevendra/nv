@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">User Details</h2>
                <div class="flex items-center space-x-3">
                    @canDo('users.edit')
<a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm bg-zendo-gold text-white rounded-lg hover:bg-opacity-90 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to List
                    </a>
@endCanDo
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- User Profile Header -->
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-20 h-20 bg-zendo-gold rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-heading font-bold text-zendo-navy">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            @if($user->id === auth()->id())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zendo-gold text-white mt-2">
                                    Current User
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Full Name</p>
                        <p class="text-base font-medium text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email Address</p>
                        <p class="text-base font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">User ID</p>
                        <p class="text-base font-medium text-gray-900">#{{ $user->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Role</p>
                        @php
                            $roleColors = [
                                'super_admin' => 'bg-purple-100 text-purple-800',
                                'admin' => 'bg-blue-100 text-blue-800',
                                'supply_head' => 'bg-green-100 text-green-800',
                                'field_officer' => 'bg-orange-100 text-orange-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ App\Models\User::ROLES[$user->role] ?? $user->role }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Account Status</p>
                        <div class="mt-1">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($user->isFieldOfficer() && $user->supplyHead)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Reports To</p>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-white text-xs font-semibold">{{ substr($user->supplyHead->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->supplyHead->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->supplyHead->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($user->isSupplyHead() && $user->fieldOfficers->count() > 0)
                <!-- Field Officers Section -->
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Field Officers ({{ $user->fieldOfficers->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->fieldOfficers as $officer)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-semibold">{{ substr($officer->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $officer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $officer->email }}</p>
                                </div>
                                <a href="{{ route('admin.users.show', $officer) }}" class="text-zendo-gold hover:text-yellow-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Account Information -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Member Since</p>
                        <p class="text-base font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="text-base font-medium text-gray-900">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Account Status</p>
                        @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if($user->id !== auth()->id())
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" 
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
