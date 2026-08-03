@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto mt-20">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col lg:flex-row">

                <!-- Left-Side Content Panel (Hidden on mobile) -->
                <div
                    class="hidden lg:flex lg:w-5/12 bg-zendo-navy bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-20 p-10 flex-col justify-between relative h-full">
                    <!-- Decorative overlay to ensure text readability against pattern -->
                    <div class="absolute inset-0 bg-zendo-navy/90"></div>

                    <div class="relative z-10 flex-grow flex flex-col justify-center">
                        <h2 class="text-4xl font-heading mb-8 text-white">Welcome Back</h2>

                        <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                            Sign in to access your dashboard, manage your properties, and connect with buyers or tenants.
                        </p>

                        <ul class="space-y-4 text-gray-200 font-body">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Manage your favorites and alerts
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Access exclusive listings
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Connect directly with agents
                            </li>
                        </ul>
                    </div>

                    <!-- Bottom Info / Trust Badge -->
                    <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 bg-white/10 p-2 rounded-full">
                                <svg class="w-6 h-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">100% Secure & Confidential</p>
                                <p class="text-xs text-gray-400 font-body">Your data is safe with us</p>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div
                        class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-zendo-gold opacity-10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl pointer-events-none">
                    </div>
                </div>

                <!-- Right Form Panel -->
                <div class="w-full lg:w-7/12 p-6 lg:p-5 bg-white">


                    <!-- Session Status -->
                    <x-auth-session-status class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800"
                        :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email or Phone -->
                        <div>
                            <label for="login_field" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                                Email Address or Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="login_field" type="text" name="login_field" value="{{ old('login_field') }}"
                                    required autofocus autocomplete="username"
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
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required
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
                                <input id="remember_me" type="checkbox" name="remember"
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
                                class="bg-zendo-navy hover:bg-zendo-gold w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-300 transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Sign In to Your Account
                            </button>
                        </div>
                    </form>

                    <!-- Need an account? -->
                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-sm text-gray-600 font-body">
                            Don't have an account?
                            <a href="{{ route('register') }}"
                                class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors underline decoration-2 underline-offset-4">
                                Create Account
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection