<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
        <input type="text" 
               name="name" 
               id="name" 
               value="{{ old('name', $user->name) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-zendo-gold focus:border-transparent @error('name') border-red-500 @enderror"
               required 
               autofocus 
               autocomplete="name">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email (Username)</label>
        <input type="text"
               value="{{ $user->email }}"
               class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
               disabled>
        <p class="mt-1 text-xs text-gray-400">Email is your username and cannot be changed.</p>
    </div>

    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" 
               x-show="show" 
               x-transition 
               x-init="setTimeout(() => show = false, 3000)"
               class="text-sm text-green-600 font-medium">
                Profile updated successfully!
            </p>
        @endif
        
        <button type="submit" 
                class="inline-flex justify-center items-center px-6 py-2 bg-zendo-gold text-white font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Save Changes
        </button>
    </div>
</form>
