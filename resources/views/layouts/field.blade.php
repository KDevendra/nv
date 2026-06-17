<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Dashboard - ZendoIndia')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&family=Raleway:wght@500;700&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'zendo-navy': '#0B2C3D',
                        'zendo-gold': '#B39359',
                        'zendo-light-bg': '#FBF8F2',
                    },
                    fontFamily: {
                        heading: ['Forum', 'cursive'],
                        body: ['"Nunito Sans"', 'sans-serif'],
                        highlight: ['Raleway', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Forum', cursive;
        }

        .field-header {
            background: linear-gradient(90deg, #0B2C3D 0%, #1a4a62 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        input[type="checkbox"] {
            accent-color: rgb(179, 147, 89);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-100 font-body min-h-screen flex flex-col" x-data="{ userMenuOpen: false }">

    <!-- Top Navigation Bar (no sidebar) -->
    <header class="field-header sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-zendo-gold rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-base">Z</span>
                    </div>
                    <div>
                        <h1 class="text-white font-heading text-lg leading-none">ZendoIndia</h1>
                        <p class="text-gray-300 text-xs">
                            @if(auth()->user()->role === 'supply_head')
                                Supply Head Portal
                            @else
                                Field Officer Portal
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Right: user info + logout -->
                <div class="flex items-center space-x-4">
                    <!-- User dropdown -->
                    <div class="relative" @click.outside="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center space-x-2 text-white hover:text-zendo-gold transition-colors focus:outline-none">
                            <div class="w-8 h-8 bg-zendo-gold rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="userMenuOpen" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 py-1 z-50">

                            <!-- User info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Profile -->
                            <a href="{{ route('field.profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-zendo-navy transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Profile
                            </a>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content (full width, no sidebar) -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Flash Messages -->
            {{-- @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif --}}

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} ZendoIndia. All rights reserved.
            </p>
        </div>
    </footer>

    @yield('scripts')
</body>

</html>
