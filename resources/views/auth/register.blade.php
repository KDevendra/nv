@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container mx-auto px-4 py-12" x-data="{ role: '{{ old('role', 'user') }}' }">
        <div class="max-w-5xl mx-auto mt-20">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col lg:flex-row">

                <!-- Dynamic Left-Side Content Panel (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-5/12 bg-zendo-navy bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-20 p-10 flex-col justify-between relative h-full">
                <!-- Decorative overlay to ensure text readability against pattern -->
                <div class="absolute inset-0 bg-zendo-navy/90"></div>
                
                <div class="relative z-10 flex-grow flex flex-col justify-center">
                    <h2 class="text-4xl font-heading mb-8 text-white">Join ZendoIndia</h2>
                    
                    <!-- Individual Content -->
                    <div x-show="role === 'user'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                            Find your perfect property — browse 25K+ listings for buy, lease, or rent. Get expert
                            guidance and 24/7 support.
                        </p>
                        
                        <ul class="space-y-4 text-gray-200 font-body">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Save your favorite properties
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Get alerts for new matches
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Direct contact with sellers/agents
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Priority customer support
                            </li>
                        </ul>
                    </div>

                    <!-- Property Owner Content -->
                    <div x-show="role === 'owner'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0" x-cloak style="display: none;">
                        <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                            List your property with ease — reach thousands of verified buyers/tenants. Manage listings,
                            track inquiries, get maximum visibility.
                        </p>
                        
                        <ul class="space-y-4 text-gray-200 font-body">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Reach a wider audience instantly
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Easy dashboard to manage listings
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Connect with verified leads
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Dedicated account manager
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Bottom Info / Trust Badge -->
                <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-white/10 p-2 rounded-full">
                            <svg class="w-6 h-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">100% Secure & Confidential</p>
                            <p class="text-xs text-gray-400 font-body">Your data is safe with us</p>
                        </div>
                    </div>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-zendo-gold opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl pointer-events-none"></div>
            </div>

            <!-- Right Form Panel -->
            <div class="w-full lg:w-7/12 p-6 lg:p-5 bg-white">


                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Register As / Account Role -->
                        <div class="mt-0">
                            <div class="grid grid-cols-2 gap-4">
                                <label
                                    class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:border-zendo-gold transition-all"
                                    :class="role === 'user' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white'">
                                    <input type="radio" name="role" value="user" x-model="role"
                                        class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">Individual</span>
                                        <span class="block text-xs text-gray-500">Looking to buy, lease, or rent
                                            property</span>
                                    </div>
                                </label>
                                <label
                                    class="relative flex items-center p-3.5 border rounded-xl cursor-pointer hover:border-zendo-gold transition-all"
                                    :class="role === 'owner' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white'">
                                    <input type="radio" name="role" value="owner" x-model="role"
                                        class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold" {{ old('role') === 'owner' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-semibold text-gray-900">Property Owner</span>
                                        <span class="block text-xs text-gray-500">Landlord or space seller</span>
                                    </div>
                                </label>
                            </div>
                            @if($errors->get('role'))
                                <div class="mt-1 text-sm text-red-600 font-body">
                                    @foreach($errors->get('role') as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                                Full Name
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                    autocomplete="name"
                                    class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                                    placeholder="Enter your full name">
                            </div>
                            @if($errors->get('name'))
                                <div class="mt-1 text-sm text-red-600 font-body">
                                    @foreach($errors->get('name') as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                    autocomplete="username"
                                    class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                                    placeholder="Enter your email address">
                            </div>
                            @if($errors->get('email'))
                                <div class="mt-1 text-sm text-red-600 font-body">
                                    @foreach($errors->get('email') as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
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
                                        autocomplete="new-password"
                                        class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                                        placeholder="Create a strong password">
                                </div>
                                @if($errors->get('password'))
                                    <div class="mt-1 text-sm text-red-600 font-body">
                                        @foreach($errors->get('password') as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input id="password_confirmation" type="password" name="password_confirmation" required
                                        autocomplete="new-password"
                                        class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                                        placeholder="Confirm your password">
                                </div>
                                @if($errors->get('password_confirmation'))
                                    <div class="mt-1 text-sm text-red-600 font-body">
                                        @foreach($errors->get('password_confirmation') as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start pt-1">
                            <div class="flex items-center h-5">
                                <input id="terms" type="checkbox" required
                                    class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded transition-colors">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700 font-body">
                                    I agree to the
                                    <a href="{{ route('terms-and-conditions') }}"
                                        class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Terms
                                        of
                                        Service</a>
                                    and
                                    <a href="{{ route('privacy-policy') }}"
                                        class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Privacy
                                        Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="bg-zendo-navy hover:bg-zendo-gold w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-300 transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Create Your Account
                            </button>
                        </div>

                        <!-- Already have account -->
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-600 font-body">
                                Already have an account?
                                <a href="{{ route('login') }}"
                                    class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection