<x-guest-layout>
    <x-slot name="title">Login</x-slot>

    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-heading text-zendo-navy mb-2">Welcome Back</h2>
            <p class="text-gray-600 font-body">Sign in to your ZendoIndia account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email or Phone -->
            <div>
                <label for="login_field" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                    Email Address or Phone Number
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="login_field" 
                           type="text" 
                           name="login_field" 
                           value="{{ old('login_field') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Enter your email or phone number">
                </div>
                @if($errors->get('login_field'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('login_field') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
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
                           autocomplete="current-password"
                           class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                           placeholder="Enter your password">
                </div>
                @if($errors->get('password'))
                    <div class="mt-2 text-sm text-red-600 font-body">
                        @foreach($errors->get('password') as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember"
                           class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded transition-colors">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700 font-body">
                        Remember me
                    </label>
                </div>

                {{-- @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" 
                           class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors font-highlight">
                            Forgot password?
                        </a>
                    </div>
                @endif --}}
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="btn-anim btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Sign In to Your Account
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
