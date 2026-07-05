@php
    $onHero = request()->routeIs('home');
    $featuredQuiz = \App\Domain\Quiz\Models\Quiz::query()->where('is_active', true)->first();
@endphp

<header @class(['site-header', 'site-header--light' => ! $onHero])>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-10">
        <a href="{{ route('home') }}" class="group flex items-center gap-3">
            <span @class([
                'flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold shadow-card transition',
                'bg-white text-moss' => $onHero,
                'bg-moss text-white' => ! $onHero,
            ])>K</span>
            <div>
                <span @class([
                    'block font-display text-lg font-semibold leading-none tracking-tight transition',
                    'text-white group-hover:text-cream' => $onHero,
                    'text-ink group-hover:text-moss' => ! $onHero,
                ])>Koro Kit</span>
                <span @class([
                    'mt-0.5 block text-[10px] font-semibold uppercase tracking-[0.18em]',
                    'text-white/50' => $onHero,
                    'text-ink-faint' => ! $onHero,
                ])>Telehealth Platform</span>
            </div>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}" @class(['nav-link', 'nav-link-light' => ! $onHero, 'nav-link-active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('products.index') }}" @class(['nav-link', 'nav-link-light' => ! $onHero, 'nav-link-active' => request()->routeIs('products.*')])>Products</a>
            @if($featuredQuiz)
                <a href="{{ route('quiz.show', $featuredQuiz) }}" @class(['nav-link', 'nav-link-light' => ! $onHero, 'nav-link-active' => request()->routeIs('quiz.*')])>Assessment</a>
            @endif
            <a href="/admin" @class([
                'ml-2 rounded-full px-5 py-2.5 text-sm font-semibold transition',
                'bg-clay text-white hover:bg-clay-light' => $onHero,
                'bg-moss text-white hover:bg-moss-light' => ! $onHero,
            ])>Admin</a>
        </nav>

        <details class="relative md:hidden">
            <summary @class([
                'flex cursor-pointer list-none items-center justify-center rounded-xl p-2.5',
                'border border-white/20 bg-white/10 text-white' => $onHero,
                'border border-ink/10 bg-white text-ink' => ! $onHero,
            ])>
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </summary>
            <div class="absolute right-0 mt-2 w-56 rounded-2xl border border-ink/8 bg-white p-2 shadow-lift">
                <a href="{{ route('home') }}" class="mobile-nav-link">Home</a>
                <a href="{{ route('products.index') }}" class="mobile-nav-link">Products</a>
                @if($featuredQuiz)
                    <a href="{{ route('quiz.show', $featuredQuiz) }}" class="mobile-nav-link">Assessment</a>
                @endif
                <a href="/admin" class="mobile-nav-link font-semibold text-moss">Admin</a>
            </div>
        </details>
    </div>
</header>
