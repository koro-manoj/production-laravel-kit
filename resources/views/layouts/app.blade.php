<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Koro Kit')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|outfit:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="page-shell">
    <div class="relative z-10 flex min-h-screen flex-col">
        @include('partials.nav')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @livewireScripts
</body>
</html>
