@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Edit User</h2>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        @canDo('users.delete')
<form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- User Info Header -->
            <div>
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-zendo-gold rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500">Member since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Enter full name"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select name="role" id="role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('role') border-red-500 @enderror select2-role"
                                required onchange="toggleSupplyHeadField()">
                            @foreach(App\Models\User::ROLES as $value => $label)
                                <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3" id="supply-head-field" style="display: none;">
                        <label for="supply_head_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Supply Head *</label>
                        <select name="supply_head_id" id="supply_head_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('supply_head_id') border-red-500 @enderror select2-supply-head">
                            <option value="">Select Supply Head</option>
                            @foreach($supplyHeads as $head)
                                <option value="{{ $head->id }}" {{ old('supply_head_id', $user->supply_head_id) == $head->id ? 'selected' : '' }}>
                                    {{ $head->name }} ({{ $head->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Field officers must be assigned to a supply head</p>
                        @error('supply_head_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- User Status -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">User Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-zendo-gold border-gray-300 rounded focus:ring-zendo-gold focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-700">Active User</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Inactive users cannot login to the system</p>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="pt-1">
                <h3 class="text-base font-semibold text-gray-900 mb-2">Change Password</h3>
                <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Enter new password (min. 8 characters)">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                               placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update User
                    </button>
                </div>
                
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" 
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex justify-center items-center w-full sm:w-auto px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete User
                        </button>
                    </form>
                @endif
            </div>
        </form>
@endCanDo
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.select2-container .select2-selection--single {
    height: 42px;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    padding-left: 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
}
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #f59e0b;
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
}
</style>

<script>
function toggleSupplyHeadField() {
    const roleSelect = document.getElementById('role');
    const supplyHeadField = document.getElementById('supply-head-field');
    const supplyHeadSelect = document.getElementById('supply_head_id');
    
    if (roleSelect.value === 'field_officer') {
        supplyHeadField.style.display = 'block';
        supplyHeadSelect.required = true;
    } else {
        supplyHeadField.style.display = 'none';
        supplyHeadSelect.required = false;
        $('#supply_head_id').val('').trigger('change');
    }
}

// Initialize on page load
$(document).ready(function() {
    // Initialize Select2 for role dropdown
    $('.select2-role').select2({
        placeholder: 'Select a role',
        allowClear: false,
        width: '100%'
    });
    
    // Initialize Select2 for supply head dropdown
    $('.select2-supply-head').select2({
        placeholder: 'Search and select supply head',
        allowClear: true,
        width: '100%'
    });
    
    // Initial toggle
    toggleSupplyHeadField();
    
    // Listen for role change
    $('#role').on('change', function() {
        toggleSupplyHeadField();
    });
});
</script>
@endsection
