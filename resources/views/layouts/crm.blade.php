<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'CRM - ZendoIndia')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

        select:disabled, select[disabled],
        input:disabled,  input[disabled],
        textarea:disabled, textarea[disabled] {
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            cursor: not-allowed !important;
        }

        /* Stage / side-state pills */
        .stage-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-100 font-body min-h-screen flex flex-col" x-data="{ userMenuOpen: false, mobileNavOpen: false }">

    <!-- ── Top Navigation Bar ─────────────────────────────────────────── -->
    <header class="field-header sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo + portal label -->
                <a href="{{ auth()->user()->role === 'sales_executive'   ? route('se.leads.index')
                         : (auth()->user()->role === 'chief_coordinator' ? route('cc.leads.index')
                         : route('sh.leads.index')) }}"
                   class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-zendo-gold rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-base">Z</span>
                    </div>
                    <div>
                        <h1 class="text-white font-heading text-lg leading-none">ZendoIndia</h1>
                        <p class="text-gray-300 text-xs">
                            @php $role = auth()->user()->role; @endphp
                            @if($role === 'sales_executive')   Sales Executive Portal
                            @elseif($role === 'chief_coordinator') Chief Coordinator Portal
                            @elseif($role === 'supply_head')   Supply Head · CRM
                            @else CRM Portal
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Centre: role-aware quick nav (hidden on mobile) -->
                <nav class="hidden md:flex items-center space-x-1 text-sm">
                    @if($role === 'sales_executive')
                        <a href="{{ route('se.leads.index') }}"
                           class="px-3 py-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors
                                  {{ request()->routeIs('se.leads.*') ? 'bg-white/15 text-white font-semibold' : '' }}">
                            My Leads
                        </a>
                    @elseif($role === 'chief_coordinator')
                        <a href="{{ route('cc.leads.index') }}"
                           class="px-3 py-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors
                                  {{ request()->routeIs('cc.leads.*') ? 'bg-white/15 text-white font-semibold' : '' }}">
                            Pipeline
                        </a>
                    @elseif($role === 'supply_head')
                        <a href="{{ route('sh.leads.index') }}"
                           class="px-3 py-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors
                                  {{ request()->routeIs('sh.leads.*') ? 'bg-white/15 text-white font-semibold' : '' }}">
                            Feasibility Queue
                        </a>
                        <a href="{{ route('supplyhead.properties.index') }}"
                           class="px-3 py-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                            Properties
                        </a>
                    @endif
                </nav>

                <!-- Right: topbar actions slot + user dropdown -->
                <div class="flex items-center space-x-3">

                    <!-- Page-level actions injected by child views -->
                    @yield('topbar-actions')

                    <!-- User dropdown -->
                    <div class="relative" @click.outside="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center space-x-2 text-white hover:text-zendo-gold transition-colors focus:outline-none">
                            <div class="w-8 h-8 bg-zendo-gold rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium max-w-[140px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown panel -->
                        <div x-show="userMenuOpen" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">

                            <!-- User info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-zendo-navy/10 text-zendo-navy font-medium">
                                        {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
                                    </span>
                                    @if(auth()->user()->division)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-zendo-gold/10 text-zendo-gold font-medium">
                                            {{ ucfirst(auth()->user()->division) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Profile link -->
                            <a href="{{ route('field.profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-zendo-navy transition-colors">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Profile
                            </a>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                        onclick="this.disabled=true; this.form.submit();">
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

                    <!-- Mobile nav toggle -->
                    <button @click="mobileNavOpen = !mobileNavOpen"
                        class="md:hidden text-white/80 hover:text-white focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile nav dropdown -->
        <div x-show="mobileNavOpen" x-cloak
            class="md:hidden bg-zendo-navy/95 border-t border-white/10 px-4 pb-3 pt-2 space-y-1 text-sm">
            @if($role === 'sales_executive')
                <a href="{{ route('se.leads.index') }}" class="block px-3 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">My Leads</a>
            @elseif($role === 'chief_coordinator')
                <a href="{{ route('cc.leads.index') }}" class="block px-3 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">Pipeline</a>
            @elseif($role === 'supply_head')
                <a href="{{ route('sh.leads.index') }}"            class="block px-3 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">Feasibility Queue</a>
                <a href="{{ route('supplyhead.properties.index') }}" class="block px-3 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10">Properties</a>
            @endif
        </div>
    </header>

    <!-- ── Flash messages ─────────────────────────────────────────────── -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <!-- ── Main content ───────────────────────────────────────────────── -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </div>
    </main>

    <!-- ── Footer ─────────────────────────────────────────────────────── -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} ZendoIndia. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- ── Global jQuery AJAX helper ─────────────────────────────────── -->
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        /**
         * Generic AJAX form handler.
         * Add data-ajax-form to any <form> to intercept submit.
         * Add data-reload="1" to reload the page on success.
         * Add data-redirect="/path" to redirect on success.
         */
        $(document).on('submit', 'form[data-ajax-form]', function (e) {
            e.preventDefault();
            const $form = $(this);
            const $btn  = $form.find('[type=submit]');
            const orig  = $btn.text();
            $btn.prop('disabled', true).text('Saving…');

            $.ajax({
                type : $form.attr('method') || 'POST',
                url  : $form.attr('action'),
                data : $form.serialize(),
                success(res) {
                    if (res.success) {
                        showToast(res.message || 'Done.', 'success');
                        const redirect = $form.data('redirect');
                        if (redirect)           { window.location.href = redirect; }
                        else if ($form.data('reload')) { location.reload(); }
                        // Close any open modal the form lives inside
                        $form.closest('[id^="modal"]').hide();
                    } else {
                        showToast(res.message || 'Something went wrong.', 'error');
                    }
                },
                error(xhr) {
                    const msg = xhr.responseJSON?.message
                        || xhr.responseJSON?.errors && Object.values(xhr.responseJSON.errors).flat().join(' ')
                        || 'Request failed.';
                    showToast(msg, 'error');
                },
                complete() {
                    $btn.prop('disabled', false).text(orig);
                }
            });
        });

        function showToast(msg, type = 'success') {
            const bg = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            const $t = $(`<div class="fixed bottom-6 right-6 z-[9999] max-w-sm px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white ${bg} flex items-center gap-2">
                <span>${msg}</span></div>`);
            $('body').append($t);
            setTimeout(() => $t.fadeOut(400, () => $t.remove()), 3500);
        }
    </script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
