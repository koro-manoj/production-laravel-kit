<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|outfit:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="site-bg relative min-h-screen font-sans text-ink antialiased">
    <div class="relative z-10 flex min-h-screen flex-col">
        <header class="border-b border-ink/6 bg-paper/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4 lg:px-8">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-moss text-sm font-bold text-paper">K</span>
                    <span class="font-display text-lg font-semibold tracking-tight text-ink group-hover:text-moss transition">{{ config('app.name') }}</span>
                </a>
                <nav class="flex items-center gap-1 text-sm font-medium text-ink-muted">
                    <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 hover:bg-ink/5 hover:text-ink transition">Home</a>
                    <a href="{{ route('quiz.show', 'health-assessment') }}" class="hidden rounded-lg px-3 py-2 hover:bg-ink/5 hover:text-ink transition sm:inline">Quiz</a>
                    <a href="{{ route('checkout.show', 'consultation-package') }}" class="hidden rounded-lg px-3 py-2 hover:bg-ink/5 hover:text-ink transition sm:inline">Checkout</a>
                    <a href="/admin" class="ml-2 rounded-full bg-moss px-4 py-2 text-paper hover:bg-moss-light transition">Admin</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-12 lg:px-8 lg:py-16">
            @yield('content')
        </main>

        <footer class="border-t border-ink/6 bg-paper-warm/60">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 text-sm text-ink-faint sm:flex-row lg:px-8">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Modular Laravel showcase.</p>
                <div class="flex gap-6">
                    <a href="https://github.com/koro-manoj/production-laravel-kit" class="hover:text-moss transition" target="_blank" rel="noopener">GitHub</a>
                    <a href="/admin" class="hover:text-moss transition">Admin panel</a>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
