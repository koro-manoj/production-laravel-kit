<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <header class="border-b border-white/10 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-white">
                {{ config('app.name') }}
            </a>
            <nav class="flex items-center gap-4 text-sm text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <a href="/admin" class="rounded-lg bg-indigo-500 px-3 py-1.5 font-medium text-white hover:bg-indigo-400">Admin</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-10">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
