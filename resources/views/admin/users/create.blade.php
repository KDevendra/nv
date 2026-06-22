@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-heading text-zendo-navy font-semibold">Create New User</h2>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-zendo-navy transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
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
                               value="{{ old('email') }}"
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
                            <option value="">Select a role</option>
                            @foreach(App\Models\User::ROLES as $value => $label)
                                <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
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
                                <option value="{{ $head->id }}" {{ old('supply_head_id') == $head->id ? 'selected' : '' }}>
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

            <!-- Region & Area Assignment -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Region & Area Assignment</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="region_id" class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                        <select name="region_id" 
                                id="region_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('region_id') border-red-500 @enderror select2-region"
                                required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">Area *</label>
                        <select name="area_id" 
                                id="area_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('area_id') border-red-500 @enderror select2-area"
                                required>
                            <option value="">Select Area (Select Region First)</option>
                        </select>
                        @error('area_id')
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
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

            <!-- Password -->
            <div class="pt-1">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Password</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Enter password (min. 8 characters)"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent"
                               placeholder="Confirm password"
                               required>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create User
                </button>
            </div>
        </form>
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

    // Initialize Select2 for region dropdown
    $('.select2-region').select2({
        placeholder: 'Select a region',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for area dropdown
    $('.select2-area').select2({
        placeholder: 'Select area (Select region first)',
        allowClear: true,
        width: '100%'
    });
    
    // Initial toggle
    toggleSupplyHeadField();
    
    // Listen for role change
    $('#role').on('change', function() {
        toggleSupplyHeadField();
    });

    // Load areas when region changes
    $('#region_id').on('change', function() {
        const regionId = $(this).val();
        const areaSelect = $('#area_id');
        
        // Clear and disable area dropdown
        areaSelect.empty().append('<option value="">Loading...</option>').prop('disabled', true);
        
        if (regionId) {
            // Fetch areas for selected region
            fetch('{{ route("admin.areas.by-region") }}?region_id=' + regionId)
                .then(response => response.json())
                .then(data => {
                    areaSelect.empty().append('<option value="">Select Area</option>');
                    data.forEach(area => {
                        areaSelect.append(`<option value="${area.id}">${area.name}</option>`);
                    });
                    areaSelect.prop('disabled', false);
                })
                .catch(error => {
                    console.error('Error loading areas:', error);
                    areaSelect.empty().append('<option value="">Error loading areas</option>');
                    areaSelect.prop('disabled', false);
                });
        } else {
            areaSelect.empty().append('<option value="">Select Region First</option>').prop('disabled', false);
        }
    });
});
</script>
@endsection
