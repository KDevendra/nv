<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>{{ isset($seoMeta) && $seoMeta && $seoMeta->title ? $seoMeta->title : View::yieldContent('title', 'ZendoIndia - Find Your Perfect Property') }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    @include('common.seo-meta')
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/scriptina" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&family=Raleway:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'zendo-navy': '#0B2C3D',
                        'zendo-gold': '#B39359',
                        'zendo-light-bg': '#FBF8F2',
                        'dark-bg-card': '#0B2C3D',
                        'light-bg-card': '#ffffff',
                    },
                    fontFamily: {
                        heading: ['Forum', 'cursive'],
                        body: ['"Nunito Sans"', 'sans-serif'],
                        highlight: ['Raleway', 'sans-serif'],
                    },
                    maxWidth: {
                        '8xl': '1320px',
                    },
                    keyframes: {
                        spin: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' },
                        },
                        fadeOut: {
                            '0%': { opacity: '1' },
                            '100%': { opacity: '0', visibility: 'hidden' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        spin: 'spin 1s linear infinite',
                        fadeOut: 'fadeOut 0.5s ease-out forwards',
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                    }
                }
            }
        }
    </script>
    
    @yield('styles')
    @include('common.styles')
</head>
<body class="bg-pattern-white font-body">

    <!-- Preloader -->
    {{-- <div id="preloader">
        <div class="spinner"></div>
    </div> --}}

    @include('common.header')

    <main>
        @yield('content')
    </main>

    @include('common.social-icons')
    @include('common.footer')

    @yield('scripts')
    @include('common.scripts')

</body>
</html>