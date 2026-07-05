@php
    $onHero = request()->routeIs('home');
    $cartCount = $cartCount ?? 0;
@endphp

<header @class(['site-header', 'site-header--light' => ! $onHero])>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-4 lg:px-10">
        <a href="{{ route('home') }}" class="group flex items-center gap-3">
            <span @class([
                'flex h-10 w-10 items-center justify-center rounded-full font-display text-lg font-semibold transition',
                'bg-white/15 text-white group-hover:bg-white/25' => $onHero,
                'bg-moss text-white group-hover:bg-moss-light' => ! $onHero,
            ])>N</span>
            <span class="hidden sm:block">
                <span @class([
                    'block font-display text-lg font-semibold leading-tight',
                    'text-white' => $onHero,
                    'text-ink' => ! $onHero,
                ])>{{ config('store.name') }}</span>
                <span @class([
                    'block text-[10px] font-bold uppercase tracking-[0.18em]',
                    'text-white/50' => $onHero,
                    'text-ink-faint' => ! $onHero,
                ])>{{ config('store.tagline') }}</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}" @class([
                'nav-link', 'nav-link-light' => ! $onHero,
                'nav-link-active' => request()->routeIs('home'),
            ])>Home</a>
            <a href="{{ route('shop.index') }}" @class([
                'nav-link', 'nav-link-light' => ! $onHero,
                'nav-link-active' => request()->routeIs('shop.*'),
            ])>Shop</a>
            <a href="{{ route('cart.index') }}" @class([
                'nav-link', 'nav-link-light' => ! $onHero,
                'nav-link-active' => request()->routeIs('cart.*'),
            ])>Bag</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('cart.index') }}" @class([
                'relative flex h-10 w-10 items-center justify-center rounded-full transition',
                'text-white/80 hover:bg-white/10 hover:text-white' => $onHero,
                'text-ink-muted hover:bg-ink/5 hover:text-ink' => ! $onHero,
            ]) aria-label="Shopping bag">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="/admin" @class([
                'hidden rounded-full px-5 py-2.5 text-sm font-semibold transition sm:inline-flex',
                'bg-clay text-white hover:bg-clay-light' => $onHero,
                'bg-moss text-white hover:bg-moss-light' => ! $onHero,
            ])>Admin</a>
        </div>
    </div>
</header>
