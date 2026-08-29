<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- App CSS (Tailwind + custom) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Bootstrap CSS (after Tailwind so Bootstrap components render correctly) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

        <!-- Remix Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ navMode: localStorage.getItem('agreement-nav-mode') || 'sidebar' }"
             x-init="$watch('navMode', value => localStorage.setItem('agreement-nav-mode', value))"
             :class="`app-shell nav-${navMode}`">

            @include('layouts.navigation')

            <!-- Page Heading (slot-based pages) -->
            @isset($header)
                <header class="page-header">
                    <div class="page-header__inner">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main style="padding: 24px 45px 55px; min-height: calc(100vh - 76px);">
                {{-- Slot-based (x-app-layout) --}}
                @isset($slot){{ $slot }}@endisset

                {{-- Extends-based (@extends + @section('content')) --}}
                @yield('content')
            </main>

            {{-- Modals — outside <main> so Bootstrap stacking works correctly --}}
            @yield('modals')

        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        {{-- Page-level scripts --}}
        @stack('scripts')
    </body>
</html>
