<header id="main-header" class="fixed top-0 z-50 w-full transition-all duration-300">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-3xl font-heading">
                <img src="{{ asset('main/images/zendo.png') }}" alt="ZENDO INDIA" class="h-10 w-auto header-logo-img">
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('about') }}" class="header-nav-link font-highlight font-medium">About Us</a>
                
                <!-- Services Dropdown -->
                <div class="relative group">
                    <button class="header-nav-link font-highlight font-medium flex items-center">
                        Services
                        <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                        <div class="py-2">
                            <a href="{{ route('properties.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors font-semibold">
                                All Properties
                            </a>
                            @php
                                $headerPropertyTypes = \App\Models\PropertyType::where('status', true)
                                    ->where('show_in_header', true)
                                    ->orderBy('sort_order', 'asc')
                                    ->get();
                            @endphp
                            @foreach($headerPropertyTypes as $propertyType)
                                <a href="{{ route('properties.index', ['property_type_slug' => $propertyType->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                    {{ $propertyType->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Calculators Dropdown -->
                <div class="relative group">
                    <button class="header-nav-link font-highlight font-medium flex items-center">
                        Calculators
                        <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-64 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                        <div class="py-2">
                            <a href="{{ route('calculators.emi-calculator') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">EMI Calculator</a>
                            <a href="{{ route('calculators.acre-to-bigha') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Acre to Bigha</a>
                            <a href="{{ route('calculators.acre-to-hectare') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Acre to Hectare</a>
                            {{-- <a href="{{ route('calculators.acre-to-squaremeter') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Acre to Square Meter</a> --}}
                            <a href="{{ route('calculators.cent-to-square-feet') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Cent to Square Feet</a>
                            <a href="{{ route('calculators.cent-to-square-meter') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Cent to Square Meter</a>
                            <a href="{{ route('calculators.length-calculator') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Length Calculator</a>
                            <a href="{{ route('calculators.cm-to-mm') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">CM to MM</a>
                            <a href="{{ route('calculators.cm-to-inches') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">CM to Inches</a>
                            <a href="{{ route('calculators.ft-to-cm') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Feet to CM</a>
                            <a href="{{ route('calculators.ft-to-inches') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Feet to Inches</a>
                            <a href="{{ route('calculators.ft-to-mm') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">Feet to MM</a>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('blogs.index') }}" class="header-nav-link font-highlight font-medium">Blog/News</a>
                <a href="{{ route('contact') }}" class="header-nav-link font-highlight font-medium">Contact Us</a>

                <!-- User Authentication -->
                @if (Route::has('login'))
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative group ml-4">
                            <button class="header-nav-link font-highlight font-medium flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ Auth::user()->name }}
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 -translate-y-2">
                                <div class="py-2">
                                    @if(Auth::user()->role === 'user')
                                        <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0M8 5a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                            </svg>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                        <a href="{{ route('user.inquiries') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            My Inquiries
                                        </a>
                                    @else
                                        <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0M8 5a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                            </svg>
                                            Dashboard
                                        </a>
                                    @endif
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-zendo-light-bg hover:text-zendo-navy transition-colors">
                                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="header-nav-link font-highlight font-medium ml-4">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="header-nav-link font-highlight font-medium ml-4">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
                
                <a href="tel:+917494010101"
                    class="header-button btn-anim ml-4 mr-4 px-5 py-2.5 rounded-full font-highlight font-medium shadow-lg transform hover:scale-105">+91 74-94-01-01-01</a>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="focus:outline-none transition-colors duration-300">
                    <svg id="menu-icon" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                    <svg id="close-icon" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden bg-zendo-light-bg shadow-xl absolute top-20 left-0 w-full z-40">
        <div class="flex flex-col space-y-4 p-5">
            <a href="{{ route('about') }}"
                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">About
                Us</a>
            
            <!-- Mobile Services Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full text-left px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy flex items-center justify-between">
                    <span>Services</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition class="pl-4 mt-2 space-y-2">
                    <a href="{{ route('properties.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy rounded-md font-semibold">
                        All Properties
                    </a>
                    @php
                        $headerPropertyTypes = \App\Models\PropertyType::where('status', true)
                            ->where('show_in_header', true)
                            ->orderBy('sort_order', 'asc')
                            ->get();
                    @endphp
                    @foreach($headerPropertyTypes as $propertyType)
                        <a href="{{ route('properties.index', ['property_type_slug' => $propertyType->slug]) }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy rounded-md">
                            {{ $propertyType->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Mobile Calculators Dropdown -->
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full text-left px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy flex items-center justify-between">
                    Calculators
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('calculators.emi-calculator') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">EMI Calculator</a>
                    <a href="{{ route('calculators.acre-to-bigha') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Acre to Bigha</a>
                    <a href="{{ route('calculators.acre-to-hectare') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Acre to Hectare</a>
                    <a href="{{ route('calculators.acre-to-squaremeter') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Acre to Square Meter</a>
                    <a href="{{ route('calculators.cent-to-square-feet') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Cent to Square Feet</a>
                    <a href="{{ route('calculators.cent-to-square-meter') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Cent to Square Meter</a>
                    <a href="{{ route('calculators.length-calculator') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Length Calculator</a>
                    <a href="{{ route('calculators.cm-to-mm') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">CM to MM</a>
                    <a href="{{ route('calculators.cm-to-inches') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">CM to Inches</a>
                    <a href="{{ route('calculators.ft-to-cm') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Feet to CM</a>
                    <a href="{{ route('calculators.ft-to-inches') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Feet to Inches</a>
                    <a href="{{ route('calculators.ft-to-mm') }}" class="block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100 hover:text-zendo-navy">Feet to MM</a>
                </div>
            </div>
            
            <a href="{{ route('blogs.index') }}"
                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">Blog/News</a>
            <a href="{{ route('contact') }}"
                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">Contact
                Us</a>

            @if (Route::has('login'))
                @auth
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex items-center px-3 py-2 mb-3">
                            <svg class="w-5 h-5 mr-2 text-zendo-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-highlight font-semibold text-zendo-navy">{{ Auth::user()->name }}</span>
                        </div>
                        @if(Auth::user()->role === 'user')
                            <a href="{{ route('user.dashboard') }}"
                                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">
                                Dashboard
                            </a>
                            <a href="{{ route('user.profile') }}"
                                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">
                                Profile
                            </a>
                            <a href="{{ route('user.inquiries') }}"
                                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">
                                My Inquiries
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}"
                                class="block px-3 py-2 rounded-md font-highlight font-semibold text-gray-700 hover:bg-gray-100 hover:text-zendo-navy">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-md font-highlight font-semibold text-red-600 hover:bg-red-50 hover:text-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <a href="{{ route('login') }}"
                            class="block w-full text-center mt-2 px-5 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all btn-anim btn-light-bg">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="block w-full text-center mt-2 px-5 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all btn-anim btn-light-bg">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            @else
                <a href="tel:+917494010101"
                    class="w-full text-center mt-2 px-5 py-3 rounded-full font-highlight font-semibold shadow-lg transition-all btn-anim btn-light-bg">
                    Connect Now
                </a>
            @endif
        </div>
    </div>
</header>
