<x-guest-layout>
    <x-slot name="title">Register</x-slot>

    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-heading text-zendo-navy mb-2">Create Account</h2>
            <p class="text-gray-600 font-body">Join ZendoIndia and start your property journey</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Register As / Account Role -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Register As
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:border-zendo-gold transition-all {{ old('role', 'channel_partner') === 'channel_partner' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white' }}">
                        <input type="radio" name="role" value="channel_partner" class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold" {{ old('role', 'channel_partner') === 'channel_partner' ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-gray-900">Channel Partner</span>
                            <span class="block text-xs text-gray-500">Broker or partner agent</span>
                        </div>
                    </label>
                    <label class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:border-zendo-gold transition-all {{ old('role') === 'owner' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white' }}">
                        <input type="radio" name="role" value="owner" class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold" {{ old('role') === 'owner' ? 'checked' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-gray-900">Property Owner</span>
                            <span class="block text-xs text-gray-500">Landlord or space seller</span>
                        </div>
                    </label>
                </div>
                @if($errors->get('role'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('role') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Full Name
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Enter your full name">
                </div>
                @if($errors->get('name'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('name') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Email Address
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="username"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Enter your email address">
                </div>
                @if($errors->get('email'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('email') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Create a strong password">
                </div>
                @if($errors->get('password'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('password') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Confirm Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Confirm your password">
                </div>
                @if($errors->get('password_confirmation'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('password_confirmation') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" 
                           type="checkbox" 
                           required
                           class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded transition-colors">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="text-gray-700 font-body">
                        I agree to the 
                        <a href="{{ route('terms-and-conditions') }}" class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Terms of Service</a> 
                        and 
                        <a href="{{ route('privacy-policy') }}" class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="btn-anim btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Create Your Account
                </button>
            </div>

            <!-- Already have account -->
            <div class="text-center">
                <p class="text-sm text-gray-600 font-body">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
    </div>

    <!-- Additional Info -->
    <div class="mt-8">
        <div class="bg-zendo-light-bg rounded-lg p-4 border border-zendo-gold/20">
            <h3 class="text-sm font-semibold text-zendo-navy font-highlight mb-3 text-center">Why Join ZendoIndia?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-600 font-body">
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 text-zendo-gold mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Access to 25K+ Properties</span>
                </div>
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 text-zendo-gold mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Expert Property Guidance</span>
                </div>
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 text-zendo-gold mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>24/7 Customer Support</span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
