<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'CRM — ZendoIndia')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&family=Nunito+Sans:wght@400;500;700&family=Raleway:wght@500;700&display=swap" rel="stylesheet">
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
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Nunito Sans', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Forum', cursive; }
        [x-cloak] { display: none !important; }
        .crm-sidebar { background: #0B2C3D; min-height: 100vh; }
        .crm-sidebar a { transition: background .15s; }
        .crm-sidebar a:hover, .crm-sidebar a.active { background: rgba(179,147,89,.2); }
        .stage-pill { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; font-size: .75rem; font-weight: 600; }
        .flash-success { animation: fadeout 4s forwards; }
        @keyframes fadeout { 0%{opacity:1} 80%{opacity:1} 100%{opacity:0;display:none} }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50 text-gray-800">
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="crm-sidebar w-56 flex-shrink-0 flex flex-col py-6 px-3 text-white">
        <div class="mb-8 px-2">
            <span class="text-zendo-gold font-heading text-xl tracking-wide">Zendo CRM</span>
            <div class="text-xs text-gray-400 mt-1">{{ ucwords(str_replace('_',' ', auth()->user()->role)) }}
                @if(auth()->user()->division)
                    · {{ ucfirst(auth()->user()->division) }}
                @endif
            </div>
        </div>
        @yield('sidebar-links')
        <div class="mt-auto px-2 pt-6 border-t border-white/10 text-xs text-gray-400">
            <div class="mb-1 truncate">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Sign out</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm">
            <h1 class="text-lg font-heading text-zendo-navy">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                @yield('topbar-actions')
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm flash-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

{{-- Global AJAX CSRF setup --}}
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Generic AJAX form helper: data-ajax-form on <form>
    $(document).on('submit', 'form[data-ajax-form]', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $btn  = $form.find('[type=submit]');
        $btn.prop('disabled', true);
        $.ajax({
            type: $form.attr('method') || 'POST',
            url:  $form.attr('action'),
            data: $form.serialize(),
            success(res) {
                if (res.success) {
                    showToast(res.message || 'Done.', 'success');
                    if ($form.data('reload')) location.reload();
                } else {
                    showToast(res.message || 'An error occurred.', 'error');
                }
            },
            error(xhr) {
                showToast(xhr.responseJSON?.message || 'Request failed.', 'error');
            },
            complete() { $btn.prop('disabled', false); }
        });
    });

    function showToast(msg, type = 'success') {
        const colours = type === 'success'
            ? 'bg-green-600 text-white'
            : 'bg-red-600 text-white';
        const $t = $(`<div class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg text-sm font-medium ${colours}">${msg}</div>`);
        $('body').append($t);
        setTimeout(() => $t.fadeOut(400, () => $t.remove()), 3500);
    }
</script>
@stack('scripts')
</body>
</html>
