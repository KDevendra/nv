<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZendoIndia') }} - @yield('title', 'Authentication')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?v={{ time() }}"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&family=Raleway:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Custom Tailwind Configuration -->
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
            font-size: 1.125rem;
            line-height: 1.7;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Forum', cursive;
        }

        /* Background Pattern */
        .bg-pattern-auth {
            background-color: #FBF8F2;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle fill='%23B39359' opacity='0.05' cx='10' cy='10' r='2'/%3E%3C/svg%3E");
            background-size: 12px 12px;
        }

        /* Button Animation */
        .btn-anim {
            position: relative;
            z-index: 1;
            overflow: hidden;
            transition: color 0.4s ease-out, background-color 0.4s ease-out, border-color 0.4s ease-out !important;
            border-width: 1px;
            border-style: solid;
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
        }

        .btn-anim::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            transition: width 0.4s ease-in-out;
            z-index: -1;
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: #B39359;
            color: #ffffff;
            border-color: #B39359;
        }

        .btn-primary::before {
            background-color: #0B2C3D;
        }

        .btn-primary:hover {
            color: #ffffff !important;
            border-color: #0B2C3D;
        }

        .btn-primary:hover::before {
            width: 100%;
        }

        /* Input Focus Styles */
        .form-input:focus {
            outline: none;
            border-color: #B39359;
            box-shadow: 0 0 0 3px rgba(179, 147, 89, 0.1);
        }

        /* Floating Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-body text-gray-900 antialiased bg-pattern-auth">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-zendo-navy to-gray-900 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 border border-zendo-gold rounded-full"></div>
                <div class="absolute top-40 right-20 w-20 h-20 border border-zendo-gold rounded-full"></div>
                <div class="absolute bottom-20 left-20 w-24 h-24 border border-zendo-gold rounded-full"></div>
                <div class="absolute bottom-40 right-10 w-16 h-16 border border-zendo-gold rounded-full"></div>
            </div>

            <!-- Content -->
            <div class="flex flex-col justify-center items-center w-full px-12 relative z-10">
                <div class="text-center mb-8 float-animation">
                    <img src="{{ asset('main/images/ZENDO-Logo-Silver.png') }}" alt="ZENDO INDIA"
                        class="h-20 w-auto mx-auto mb-6">
                    <h1 class="text-4xl font-heading text-white mb-4">Welcome to ZendoIndia</h1>
                    <p class="text-xl text-gray-300 font-body leading-relaxed max-w-md">
                        Your trusted partner in finding the perfect property across India.
                        Discover, invest, and build your future with us.
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12 text-center">
                    <div>
                        <div class="text-3xl font-heading text-zendo-gold mb-2">25K+</div>
                        <div class="text-sm text-gray-400 font-body">Properties Sold</div>
                    </div>
                    <div>
                        <div class="text-3xl font-heading text-zendo-gold mb-2">15.4M</div>
                        <div class="text-sm text-gray-400 font-body">Transactions</div>
                    </div>
                    <div>
                        <div class="text-3xl font-heading text-zendo-gold mb-2">1500+</div>
                        <div class="text-sm text-gray-400 font-body">Happy Clients</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-8 text-center">
                <a href="{{ route('home') }}" class="block text-sm text-gray-500 hover:text-zendo-navy mb-2 font-body">←
                    Back to Home</a>
                <img src="{{ asset('main/images/zendo.png') }}" alt="ZENDO INDIA" class="h-12 w-auto mx-auto">
            </div>

            <!-- Form Container -->
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>

        </div>
    </div>
</body>

</html>