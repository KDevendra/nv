<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Admin Dashboard - ZendoIndia')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&family=Raleway:wght@500;700&display=swap"
        rel="stylesheet">
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'zendo-navy': '#0B2C3D',
                        'zendo-gold': '#B39359',
                        'zendo-light-bg': '#FBF8F2',
                        'admin-sidebar': '#1F2937',
                        'admin-sidebar-hover': '#374151',
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

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Forum', cursive;
        }

        select:disabled,
        select[disabled],
        input:disabled,
        input[disabled],
        textarea:disabled,
        textarea[disabled] {
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            cursor: not-allowed !important;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            background: linear-gradient(180deg, #1F2937 0%, #111827 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar-link {
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .admin-sidebar-link:hover {
            background-color: rgba(55, 65, 81, 0.8);
            transform: translateX(2px);
        }

        .admin-sidebar-link.active {
            background: linear-gradient(90deg, #B39359 0%, #9a7c4d 100%);
            color: white;
            /* box-shadow: 0 2px 8px rgba(179, 147, 89, 0.3); */
        }

        .admin-sidebar-link.active:hover {
            background: linear-gradient(90deg, #9a7c4d 0%, #B39359 100%);
            transform: translateX(0);
        }

        .admin-sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #ffffff;
        }

        /* Simple Dropdown Styles */
        .dropdown-submenu {
            margin-left: 2px;
            border-left: 2px solid rgba(179, 147, 89, 0.2);
            /* padding-left: 8px; */
        }

        .dropdown-submenu .admin-sidebar-link {
            padding: 8px 12px;
            margin: 2px 0;
            font-size: 13px;
            background: transparent !important;
        }

        .dropdown-submenu .admin-sidebar-link:hover {
            transform: translateX(0);
            background: transparent !important;
            color: #B39359;
        }

        .dropdown-submenu .admin-sidebar-link.active {
            background: transparent !important;
            color: #B39359;
            font-weight: 500;
        }

        .dropdown-submenu .admin-sidebar-link.active::before {
            width: 2px;
            background: #B39359;
        }

        /* Header Styles */
        .admin-header {
            background: linear-gradient(90deg, #ffffff 0%, #f9fafb 100%);
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Footer Styles */
        .admin-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
        }

        /* Main Content Area */
        .admin-main-content {
            min-height: calc(100vh - 140px);
            background: #f3f4f6;
            overflow-x: hidden;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        /* Custom Checkbox Styling - Change blue to gold */
        input[type="checkbox"] {
            accent-color: rgb(179, 147, 89);
        }

        input[type="checkbox"]:checked {
            background-color: rgb(179, 147, 89);
            border-color: rgb(179, 147, 89);
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }

        input[type="checkbox"]:focus {
            --tw-ring-color: rgb(179, 147, 89);
            border-color: rgb(179, 147, 89);
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #B39359;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9a7c4d;
        }

        /* Logo section */
        .admin-logo-section {
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }

            .admin-main-content {
                margin-left: 0 !important;
            }
        }

        /* Collapsed sidebar styles */
        .admin-sidebar.w-20 .admin-sidebar-link {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-100 font-body overflow-x-hidden">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-cloak></div>
        <!-- Sidebar -->
        <div class="admin-sidebar fixed h-full z-50 lg:z-30 transition-all duration-300"
            :class="{
                'mobile-open': sidebarOpen,
                'w-64': !sidebarCollapsed,
                'w-20': sidebarCollapsed
            }">
            <!-- Logo Section -->
            <div class="admin-logo-section p-5">
                <div class="flex items-center" :class="{ 'justify-center': sidebarCollapsed }">
                    <div class="w-10 h-10 bg-zendo-gold rounded-lg flex items-center justify-center"
                        :class="{ 'mr-3': !sidebarCollapsed }">
                        <span class="text-white font-bold text-lg">Z</span>
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition>
                        <h2 class="text-white font-heading text-lg font-semibold">ZendoIndia</h2>
                        <p class="text-gray-300 text-xs">Admin Panel</p>
                    </div>
                </div>
                <!-- Collapse Toggle Button (Desktop Only) -->
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex absolute -right-3 top-8 w-6 h-6 bg-zendo-gold rounded-full items-center justify-center text-white hover:bg-zendo-navy transition-colors shadow-lg z-50">
                    <svg class="w-3 h-3 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
            </div>
            <!-- Navigation -->
            <nav class="mt-2 custom-scrollbar overflow-y-auto h-full pb-20">
                @php $navPerms = auth()->check() ? auth()->user()->getPermissions() : []; @endphp
                <div class="px-3 space-y-1">
                    <!-- Dashboard -->
                    @if (in_array('dashboard.view', $navPerms))
                        <a href="{{ route('dashboard') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Dashboard</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Dashboard
                            </div>
                        </a>
                    @endif

                    <!-- Users -->
                    @if (in_array('users.view', $navPerms))
                        <a href="{{ route('admin.users.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Users</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Users
                            </div>
                        </a>
                    @endif

                    <!-- Role Permissions -->
                    @if (in_array('users.edit', $navPerms))
                        <a href="{{ route('admin.role-permissions.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.role-permissions.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Role Permissions</span>
                        </a>
                    @endif

                    <!-- Regions -->
                    @if (in_array('users.view', $navPerms))
                        <a href="{{ route('admin.regions.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.regions.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Regions</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Regions
                            </div>
                        </a>
                    @endif

                    <!-- Areas -->
                    @if (in_array('users.view', $navPerms))
                        <a href="{{ route('admin.areas.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.areas.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Areas</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Areas
                            </div>
                        </a>
                    @endif

                    <!-- Zones -->
                    @if (in_array('users.view', $navPerms))
                        <a href="{{ route('admin.zones.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.zones.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Zones</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Zones
                            </div>
                        </a>
                    @endif

                    <!-- Lead Tracking Dropdown -->
                    @if (in_array('inquiries.view', $navPerms) ||
                            in_array('consultations.view', $navPerms) ||
                            in_array('property-inquiries.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.inquiries.*', 'admin.consultations.*', 'admin.property-inquiries.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.inquiries.*', 'admin.consultations.*', 'admin.property-inquiries.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                        </path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>Lead Tracking</span>
                                    @php
                                        $totalPendingLeads =
                                            App\Models\Inquiry::pending()->count() +
                                            App\Models\Consultation::pending()->count() +
                                            App\Models\PropertyInquiry::pending()->count();
                                    @endphp
                                    @if ($totalPendingLeads > 0)
                                        <span x-show="!sidebarCollapsed"
                                            class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 min-w-[18px] h-4 justify-center">
                                            {{ $totalPendingLeads }}
                                        </span>
                                        <span x-show="sidebarCollapsed"
                                            class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500"></span>
                                    @endif
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    Lead Tracking
                                </div>
                                @if (in_array('inquiries.view', $navPerms))
                                    <a href="{{ route('admin.inquiries.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.inquiries.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7">
                                            </path>
                                        </svg>
                                        Inquiries
                                        @if (App\Models\Inquiry::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ App\Models\Inquiry::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                                @if (in_array('consultations.view', $navPerms))
                                    <a href="{{ route('admin.consultations.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.consultations.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        Consultations
                                        @if (App\Models\Consultation::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ App\Models\Consultation::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                                @if (in_array('property-inquiries.view', $navPerms))
                                    <a href="{{ route('admin.property-inquiries.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.property-inquiries.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                            </path>
                                        </svg>
                                        Property Inquiries
                                        @if (App\Models\PropertyInquiry::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ App\Models\PropertyInquiry::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">

                                <!-- Inquiries -->
                                @if (in_array('inquiries.view', $navPerms))
                                    <a href="{{ route('admin.inquiries.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.inquiries.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7">
                                            </path>
                                        </svg>
                                        Inquiries
                                        @if (App\Models\Inquiry::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 min-w-[18px] h-4 justify-center">
                                                {{ App\Models\Inquiry::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif

                                <!-- Consultations -->
                                @if (in_array('consultations.view', $navPerms))
                                    <a href="{{ route('admin.consultations.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.consultations.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        Consultations
                                        @if (App\Models\Consultation::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 min-w-[18px] h-4 justify-center">
                                                {{ App\Models\Consultation::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif

                                <!-- Property Inquiries -->
                                @if (in_array('property-inquiries.view', $navPerms))
                                    <a href="{{ route('admin.property-inquiries.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.property-inquiries.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                            </path>
                                        </svg>
                                        Property Inquiries
                                        @if (App\Models\PropertyInquiry::pending()->count() > 0)
                                            <span
                                                class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 min-w-[18px] h-4 justify-center">
                                                {{ App\Models\PropertyInquiry::pending()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- CRM Lead Pipeline -->
                    @if (in_array('dashboard.view', $navPerms))
                        <a href="{{ route('admin.leads.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.leads.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>CRM Lead Pipeline</span>
                            @php 
                                $activeLeads = App\Models\Lead::whereNull('side_state')->count();
                                $holdingQueue = App\Models\Lead::holdingQueue()->count();
                            @endphp
                            @if($holdingQueue > 0)
                                <span x-show="!sidebarCollapsed"
                                    class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 min-w-[18px] h-4 justify-center">
                                    {{ $holdingQueue }}
                                </span>
                                <span x-show="sidebarCollapsed"
                                    class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-indigo-500"></span>
                            @endif
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                CRM Lead Pipeline
                            </div>
                        </a>
                    @endif

                    <!-- Property Entry Report -->
                    @if (in_array('dashboard.view', $navPerms))
                        <a href="{{ route('admin.property-entry-report.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.property-entry-report.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Property Entry Report</span>
                            @php $pendingEntries = App\Models\PropertyEntry::where('status', 'submitted')->count(); @endphp
                            @if($pendingEntries > 0)
                                <span x-show="!sidebarCollapsed"
                                    class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 min-w-[18px] h-4 justify-center">
                                    {{ $pendingEntries }}
                                </span>
                                <span x-show="sidebarCollapsed"
                                    class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-blue-500"></span>
                            @endif
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Property Entry Report
                            </div>
                        </a>
                    @endif

                    <!-- Wishlist Report -->
                    @if (in_array('dashboard.view', $navPerms) || in_array('properties.view', $navPerms))
                        <a href="{{ route('admin.wishlist-report.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.wishlist-report.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                            @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Wishlist Report</span>
                            <!-- Tooltip -->
                            <div x-show="tooltip" x-cloak
                                class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                                Wishlist Report
                            </div>
                        </a>
                    @endif

                    <!-- Property Management Dropdown -->
                    @if (in_array('properties.view', $navPerms) ||
                            in_array('service-types.view', $navPerms) ||
                            in_array('property-types.view', $navPerms) ||
                            in_array('locations.view', $navPerms) ||
                            in_array('builders.view', $navPerms) ||
                            in_array('amenities.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.service-types.*', 'admin.property-types.*', 'admin.locations.*', 'admin.project-statuses.*', 'admin.bhks.*', 'admin.builders.*', 'admin.amenities.*', 'admin.properties.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.service-types.*', 'admin.property-types.*', 'admin.locations.*', 'admin.project-statuses.*', 'admin.bhks.*', 'admin.builders.*', 'admin.amenities.*', 'admin.properties.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                        </path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>Property Management</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    Property Management
                                </div>

                                @if (in_array('service-types.view', $navPerms))
                                    <a href="{{ route('admin.service-types.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.service-types.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Service Types
                                    </a>
                                @endif

                                @if (in_array('property-types.view', $navPerms))
                                    <a href="{{ route('admin.property-types.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.property-types.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        Property Types
                                    </a>
                                @endif

                                @if (in_array('locations.view', $navPerms))
                                    <a href="{{ route('admin.locations.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.locations.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>
                                        </svg>
                                        Locations
                                    </a>
                                @endif

                                @if (in_array('project-statuses.view', $navPerms))
                                    <a href="{{ route('admin.project-statuses.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.project-statuses.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Project Statuses
                                    </a>
                                @endif

                                @if (in_array('bhks.view', $navPerms))
                                    <a href="{{ route('admin.bhks.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.bhks.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        BHK
                                    </a>
                                @endif

                                @if (in_array('builders.view', $navPerms))
                                    <a href="{{ route('admin.builders.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.builders.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Builders
                                    </a>
                                @endif

                                @if (in_array('amenities.view', $navPerms))
                                    <a href="{{ route('admin.amenities.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.amenities.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                            </path>
                                        </svg>
                                        Amenities
                                    </a>
                                @endif

                                <div class="border-t border-gray-700 my-2"></div>

                                @if (in_array('properties.view', $navPerms))
                                    <a href="{{ route('admin.properties.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.properties.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                            </path>
                                        </svg>
                                        Properties
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">

                                <!-- Service Types -->
                                @if (in_array('service-types.view', $navPerms))
                                    <a href="{{ route('admin.service-types.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.service-types.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Service Types
                                    </a>
                                @endif

                                <!-- Property Types -->
                                @if (in_array('property-types.view', $navPerms))
                                    <a href="{{ route('admin.property-types.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.property-types.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        Property Types
                                    </a>
                                @endif

                                <!-- Locations -->
                                @if (in_array('locations.view', $navPerms))
                                    <a href="{{ route('admin.locations.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.locations.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>
                                        </svg>
                                        Locations
                                    </a>
                                @endif

                                <!-- Project Statuses -->
                                @if (in_array('project-statuses.view', $navPerms))
                                    <a href="{{ route('admin.project-statuses.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.project-statuses.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Project Statuses
                                    </a>
                                @endif

                                <!-- BHK -->
                                @if (in_array('bhks.view', $navPerms))
                                    <a href="{{ route('admin.bhks.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.bhks.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        BHK
                                    </a>
                                @endif

                                <!-- Builders -->
                                @if (in_array('builders.view', $navPerms))
                                    <a href="{{ route('admin.builders.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.builders.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Builders
                                    </a>
                                @endif

                                <!-- Amenities -->
                                @if (in_array('amenities.view', $navPerms))
                                    <a href="{{ route('admin.amenities.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.amenities.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                            </path>
                                        </svg>
                                        Amenities
                                    </a>
                                @endif

                                <div class="border-t border-gray-700 my-2 mx-4"></div>

                                <!-- Properties -->
                                @if (in_array('properties.view', $navPerms))
                                    <a href="{{ route('admin.properties.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.properties.*') && !request()->routeIs('admin.property-page-sections.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                            </path>
                                        </svg>
                                        Properties
                                    </a>
                                @endif

                                <!-- Work Processes -->
                                @if (in_array('work-processes.view', $navPerms))
                                    <a href="{{ route('admin.work-processes.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.work-processes.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        How We Work
                                    </a>
                                @endif
                                <!-- Property Page Sections -->
                                @if (in_array('property-page-sections.view', $navPerms))
                                    <a href="{{ route('admin.property-page-sections.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.property-page-sections.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z">
                                            </path>
                                        </svg>
                                        Property Page Sections
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif


                    <!-- Home Page Dropdown -->
                    @if (in_array('hero-sections.view', $navPerms) ||
                            in_array('about-us.view', $navPerms) ||
                            in_array('features.view', $navPerms) ||
                            in_array('categories.view', $navPerms) ||
                            in_array('cities.view', $navPerms) ||
                            in_array('testimonials.view', $navPerms) ||
                            in_array('faqs.view', $navPerms) ||
                            in_array('commercial-sections.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.hero-sections.*', 'admin.about-us.*', 'admin.features.*', 'admin.categories.*', 'admin.cities.*', 'admin.testimonials.*', 'admin.faqs.*', 'admin.commercial-sections.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.hero-sections.*', 'admin.about-us.*', 'admin.features.*', 'admin.categories.*', 'admin.cities.*', 'admin.testimonials.*', 'admin.faqs.*', 'admin.commercial-sections.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>Home Page</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    Home Page
                                </div>

                                @if (in_array('hero-sections.view', $navPerms))
                                    <a href="{{ route('admin.hero-sections.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.hero-sections.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                                            </path>
                                        </svg>
                                        Hero Sections
                                    </a>
                                @endif

                                @if (in_array('about-us.view', $navPerms))
                                    <a href="{{ route('admin.about-us.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.about-us.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        About Us
                                    </a>
                                @endif

                                @if (in_array('categories.view', $navPerms))
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14-7H3a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zM9 7h1m4 0h1m-5.5 5h1m4 0h1m-5.5 5h1m4 0h1">
                                            </path>
                                        </svg>
                                        Categories
                                    </a>
                                @endif

                                @if (in_array('cities.view', $navPerms))
                                    <a href="{{ route('admin.cities.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.cities.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Cities
                                    </a>
                                @endif

                                @if (in_array('commercial-sections.view', $navPerms))
                                    <a href="{{ route('admin.commercial-sections.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.commercial-sections.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Commercial Sections
                                    </a>
                                @endif

                                @if (in_array('features.view', $navPerms))
                                    <a href="{{ route('admin.features.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.features.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                            </path>
                                        </svg>
                                        Services
                                    </a>
                                @endif

                                @if (in_array('testimonials.view', $navPerms))
                                    <a href="{{ route('admin.testimonials.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.testimonials.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        Testimonials
                                    </a>
                                @endif

                                @if (in_array('faqs.view', $navPerms))
                                    <a href="{{ route('admin.faqs.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        FAQs
                                    </a>
                                    <a href="{{ route('admin.video-tour.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.video-tour.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Video Tour
                                    </a>
                                @endif

                                {{-- @if (in_array('video-tour.view', $navPerms)) --}}

                                {{-- @endif --}}
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">

                                <!-- Hero Sections -->
                                @if (in_array('hero-sections.view', $navPerms))
                                    <a href="{{ route('admin.hero-sections.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.hero-sections.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z">
                                            </path>
                                        </svg>
                                        Hero Sections
                                    </a>
                                @endif

                                <!-- About Us -->
                                @if (in_array('about-us.view', $navPerms))
                                    <a href="{{ route('admin.about-us.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.about-us.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        About Us
                                    </a>
                                @endif


                                <!-- Categories -->
                                @if (in_array('categories.view', $navPerms))
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14-7H3a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zM9 7h1m4 0h1m-5.5 5h1m4 0h1m-5.5 5h1m4 0h1">
                                            </path>
                                        </svg>
                                        Categories
                                    </a>
                                @endif

                                <!-- Cities -->
                                @if (in_array('cities.view', $navPerms))
                                    <a href="{{ route('admin.cities.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.cities.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Cities
                                    </a>
                                @endif

                                <!-- Commercial Sections -->
                                @if (in_array('commercial-sections.view', $navPerms))
                                    <a href="{{ route('admin.commercial-sections.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.commercial-sections.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Commercial Sections
                                    </a>
                                @endif


                                <!-- Services -->
                                @if (in_array('features.view', $navPerms))
                                    <a href="{{ route('admin.features.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.features.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                            </path>
                                        </svg>
                                        Services
                                    </a>
                                @endif

                                <!-- Testimonials -->
                                @if (in_array('testimonials.view', $navPerms))
                                    <a href="{{ route('admin.testimonials.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.testimonials.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        Testimonials
                                    </a>
                                @endif

                                <!-- FAQs -->
                                @if (in_array('faqs.view', $navPerms))
                                    <a href="{{ route('admin.faqs.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.faqs.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        FAQs
                                    </a>

                                    <a href="{{ route('admin.video-tour.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.video-tour.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Video Tour
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- About Page Dropdown -->
                    @if (in_array('about-page.view', $navPerms) ||
                            in_array('our-clients.view', $navPerms) ||
                            in_array('team-members.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.about-page.*', 'admin.our-clients.*', 'admin.team-members.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.about-page.*', 'admin.our-clients.*', 'admin.team-members.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>About Page</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    About Page
                                </div>
                                @if (in_array('about-page.view', $navPerms))
                                    <a href="{{ route('admin.about-page.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.about-page.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        About Page Content
                                    </a>
                                @endif

                                @if (in_array('our-clients.view', $navPerms))
                                    <a href="{{ route('admin.our-clients.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.our-clients.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Our Clients
                                    </a>
                                @endif

                                @if (in_array('team-members.view', $navPerms))
                                    <a href="{{ route('admin.team-members.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.team-members.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Team Members
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">

                                <!-- About Page Content -->
                                @if (in_array('about-page.view', $navPerms))
                                    <a href="{{ route('admin.about-page.edit') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.about-page.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        About Page Content
                                    </a>
                                @endif

                                <!-- Our Clients -->
                                @if (in_array('our-clients.view', $navPerms))
                                    <a href="{{ route('admin.our-clients.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.our-clients.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Our Clients
                                    </a>
                                @endif

                                <!-- Team Members -->
                                @if (in_array('team-members.view', $navPerms))
                                    <a href="{{ route('admin.team-members.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.team-members.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        Team Members
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Contact Page Dropdown -->
                    @if (in_array('contact-page.view', $navPerms) || in_array('contact-info.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.contact-page.*', 'admin.contact-info.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.contact-page.*', 'admin.contact-info.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>Contact Page</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    Contact Page
                                </div>
                                @if (in_array('contact-page.view', $navPerms))
                                    <a href="{{ route('admin.contact-page.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.contact-page.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Contact Page Content
                                    </a>
                                @endif

                                @if (in_array('contact-info.view', $navPerms))
                                    <a href="{{ route('admin.contact-info.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.contact-info.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Contact Information
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">
                                <!-- Contact Page Content -->
                                @if (in_array('contact-page.view', $navPerms))
                                    <a href="{{ route('admin.contact-page.edit') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.contact-page.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Contact Page Content
                                    </a>
                                @endif

                                <!-- Contact Information -->
                                @if (in_array('contact-info.view', $navPerms))
                                    <a href="{{ route('admin.contact-info.index') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.contact-info.*') ? 'active' : 'text-gray-400 hover:text-white' }}">
                                        <svg class="w-4 h-4 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Contact Information
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Blog/News -->
                    @if (in_array('blogs.view', $navPerms))
                        <a href="{{ route('admin.blogs.index') }}"
                            class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blogs.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                            :class="{ 'justify-center': sidebarCollapsed }">
                            <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                            <span x-show="!sidebarCollapsed" x-transition>Blog/News</span>
                        </a>
                    @endif

                    <!-- Legal Dropdown -->
                    @if (in_array('privacy-policy.view', $navPerms) || in_array('terms-and-conditions.view', $navPerms))
                        <div x-data="{ open: {{ request()->routeIs('admin.privacy-policy.*', 'admin.terms-and-conditions.*') ? 'true' : 'false' }}, showFloating: false }" @mouseenter="showFloating = sidebarCollapsed"
                            @mouseleave="showFloating = false" class="relative">
                            <!-- Dropdown Toggle -->
                            <button @click="open = !open"
                                class="admin-sidebar-link w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.privacy-policy.*', 'admin.terms-and-conditions.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                :class="{ 'justify-center': sidebarCollapsed }">
                                <div class="flex items-center" :class="{ 'justify-center w-full': sidebarCollapsed }">
                                    <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11c0-1.657 1.343-3 3-3h4a3 3 0 013 3v7a3 3 0 01-3 3h-4c-1.657 0-3-1.343-3-3v-7z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 11V7a3 3 0 016 0v4"></path>
                                    </svg>
                                    <span x-show="!sidebarCollapsed" x-transition>Legal</span>
                                </div>
                                <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Floating Menu (Collapsed State) -->
                            <div x-show="showFloating && sidebarCollapsed" x-cloak
                                class="absolute left-full top-0 ml-2 w-56 bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-700">
                                <div
                                    class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-700 mb-2">
                                    Legal
                                </div>
                                @if (in_array('privacy-policy.view', $navPerms))
                                    <a href="{{ route('admin.privacy-policy.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.privacy-policy.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Privacy Policy
                                    </a>
                                @endif
                                @if (in_array('terms-and-conditions.view', $navPerms))
                                    <a href="{{ route('admin.terms-and-conditions.edit') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.terms-and-conditions.*') ? 'bg-gray-700 text-white' : '' }}">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        Terms &amp; Conditions
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Menu (Expanded State) -->
                            <div x-show="open && !sidebarCollapsed"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="dropdown-submenu mt-1">

                                <!-- Privacy Policy -->
                                @if (in_array('privacy-policy.view', $navPerms))
                                    <a href="{{ route('admin.privacy-policy.edit') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.privacy-policy.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                        :class="{ 'justify-center': sidebarCollapsed }">
                                        <svg class="w-5 h-5 flex-shrink-0 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        <span x-show="!sidebarCollapsed" x-transition>Privacy Policy</span>
                                    </a>
                                @endif

                                <!-- Terms & Conditions -->
                                @if (in_array('terms-and-conditions.view', $navPerms))
                                    <a href="{{ route('admin.terms-and-conditions.edit') }}"
                                        class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.terms-and-conditions.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                                        :class="{ 'justify-center': sidebarCollapsed }">
                                        <svg class="w-5 h-5 flex-shrink-0 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        <span x-show="!sidebarCollapsed" x-transition>Terms &amp; Conditions</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SEO Meta -->
                    {{-- @if (in_array('seo-metas.view', $navPerms)) --}}
                    <a href="{{ route('admin.seo-metas.index') }}"
                        class="admin-sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.seo-metas.*') ? 'active' : 'text-gray-300 hover:text-white' }}"
                        :class="{ 'justify-center': sidebarCollapsed }" x-data="{ tooltip: false }"
                        @mouseenter="tooltip = sidebarCollapsed" @mouseleave="tooltip = false">
                        <svg class="w-5 h-5 flex-shrink-0" :class="{ 'mr-3': !sidebarCollapsed }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span x-show="!sidebarCollapsed" x-transition>SEO Meta</span>
                        <div x-show="tooltip" x-cloak
                            class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap z-50">
                            SEO Meta
                        </div>
                    </a>
                    {{-- @endif --}}
                </div>
            </nav>
        </div>
        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 flex flex-col min-h-screen overflow-x-hidden transition-all duration-300"
            :class="{
                'lg:ml-64': !sidebarCollapsed,
                'lg:ml-20': sidebarCollapsed
            }">
            <!-- Top Header -->
            <header class="admin-header sticky top-0 z-20">
                <div class="px-4 lg:px-6 py-4">
                    <div class="flex justify-between items-center">
                        <!-- Mobile Menu Button & Page Title -->
                        <div class="flex items-center">
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="lg:hidden p-2 rounded-md text-gray-600 hover:text-zendo-navy hover:bg-gray-100 mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-xl lg:text-2xl font-heading text-zendo-navy font-semibold"
                                    style="display: none">@yield('page-title', 'Dashboard')</h1>
                                <p class="text-gray-600 text-sm hidden lg:block" style="display: none">
                                    @yield('page-description', 'Welcome to your admin dashboard')
                                </p>
                            </div>
                        </div>
                        <!-- Right side - User menu -->
                        <div class="flex items-center space-x-3">
                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-zendo-gold p-2 hover:bg-gray-100">
                                    <div class="w-8 h-8 bg-zendo-gold rounded-full flex items-center justify-center">
                                        <span
                                            class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <span
                                        class="font-medium text-gray-700 hidden lg:block">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400 hidden lg:block" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile
                                        Settings</a>
                                    <a href="{{ route('home') }}" target="_blank"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View
                                        Website</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            onclick="this.disabled=true;this.form.submit();">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Main Content -->
            <main class="admin-main-content flex-1 min-w-0 overflow-x-hidden p-4 lg:p-6">
                @yield('content')
            </main>
            <!-- Admin Footer -->
            <footer class="admin-footer">
                <div class="px-4 lg:px-6 py-4">
                    <div
                        class="flex flex-col lg:flex-row justify-between items-center text-sm text-gray-600 space-y-2 lg:space-y-0">
                        <div>
                            <p>&copy; {{ date('Y') }} ZendoIndia. All rights reserved.</p>
                        </div>
                        <div class="flex space-x-6">
                            <a href="#" class="hover:text-zendo-navy transition-colors">Support</a>
                            <a href="#" class="hover:text-zendo-navy transition-colors">Documentation</a>
                            <a href="#" class="hover:text-zendo-navy transition-colors">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 bg-zendo-gold text-white p-3 rounded-full shadow-lg hover:bg-zendo-navy transition-all duration-300 z-50 opacity-0 invisible"
        onclick="scrollToTop()" title="Back to Top">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
            </path>
        </svg>
    </button>

    <script>
        // Back to Top Button Functionality
        const backToTopButton = document.getElementById('backToTop');

        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.remove('opacity-100', 'visible');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        });

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
    @yield('scripts')
    @stack('scripts')
    <script>
        // Alpine.js cloak
        document.addEventListener('alpine:init', () => {
            // Any Alpine.js initialization code
        });
    </script>
</body>

</html>
