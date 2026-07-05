@extends('layouts.store')

@section('title', config('store.name').' — '.config('store.tagline'))

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-hero-gradient pb-20 pt-12 lg:pb-28 lg:pt-16">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_70%_20%,rgba(255,255,255,0.08),transparent_45%)]"></div>
        <div class="pointer-events-none absolute -left-32 top-20 h-96 w-96 rounded-full bg-moss-glow/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-clay/15 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2 lg:gap-16 lg:px-10">
            <div class="animate-fade-up">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.18em] text-white/90">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-clay opacity-60"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-clay"></span>
                    </span>
                    New season · Free shipping over $75
                </div>

                <h1 class="mt-7 font-display text-[2.6rem] font-semibold leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-[3.75rem]">
                    Goods for everyday ritual.
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-relaxed text-white/70">
                    Curated home, desk, and travel essentials — designed to last, shipped with care. Browse the catalog, fill your bag, and checkout securely with Stripe.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="{{ route('shop.index') }}" class="btn-primary btn-primary-dark">
                        Shop the collection
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @if($featured)
                        <a href="{{ route('shop.show', $featured) }}" class="btn-secondary">Featured: {{ $featured->name }}</a>
                    @endif
                </div>

                <dl class="mt-12 grid grid-cols-3 gap-4 sm:max-w-md">
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['products'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Products</dd>
                    </div>
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['orders'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Orders shipped</dd>
                    </div>
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['categories'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Collections</dd>
                    </div>
                </dl>
            </div>

            {{-- Shopping bag preview --}}
            <div class="animate-fade-up stagger-2 animate-float relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-2 shadow-glow backdrop-blur-xl">
                    <div class="overflow-hidden rounded-xl bg-cream">
                        <div class="flex items-center justify-between border-b border-ink/6 bg-white px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-clay/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-moss/60"></span>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-ink-faint">Your bag</span>
                        </div>
                        <div class="p-5">
                            @if($featured)
                                <div class="flex gap-4">
                                    <div class="product-thumb product-thumb--home h-16 w-16 shrink-0 rounded-lg">
                                        <span class="product-thumb-letter text-2xl">{{ mb_substr($featured->name, 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-display font-semibold text-ink">{{ $featured->name }}</p>
                                        <p class="text-sm text-ink-muted">{{ money_format_cents($featured->price_cents) }}</p>
                                        <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-moss">Qty 1</p>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-4 space-y-2 border-t border-ink/6 pt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-ink-muted">Subtotal</span>
                                    <span class="font-semibold text-ink">{{ $featured ? money_format_cents($featured->price_cents) : '$0.00' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-ink-muted">Shipping</span>
                                    <span class="font-medium text-moss">Calculated at checkout</span>
                                </div>
                            </div>
                            <div class="mt-4 rounded-lg bg-moss px-4 py-3 text-center text-sm font-semibold text-white">
                                Secure checkout · Stripe
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-3 -left-3 rounded-lg border border-white/20 bg-moss-dark/90 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-white/80 shadow-glow backdrop-blur">
                    Cart · Checkout · Orders
                </div>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-20">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-label">Collections</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-ink sm:text-4xl">Shop by room & ritual</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="btn-ghost">View all products →</a>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-3">
            @php
                $categoryMeta = [
                    'home' => [
                        'class' => 'category-tile--home',
                        'desc' => 'Ceramics, textiles & slow mornings',
                        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    ],
                    'desk' => [
                        'class' => 'category-tile--desk',
                        'desc' => 'Organizers, tools & focus',
                        'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    ],
                    'outdoor' => [
                        'class' => 'category-tile--outdoor',
                        'desc' => 'Bags, layers & weekend escapes',
                        'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp
            @foreach($categories as $slug => $label)
                @php $meta = $categoryMeta[$slug] ?? $categoryMeta['home']; @endphp
                <a href="{{ route('shop.index', ['category' => $slug]) }}" @class(['category-tile group', $meta['class']])>
                    <div class="category-tile-glow"></div>
                    <div class="category-tile-icon">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="relative mt-auto">
                        <span class="category-tile-label">{{ $label }}</span>
                        <p class="mt-1 text-sm text-white/65">{{ $meta['desc'] }}</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="category-tile-count">{{ $categoryCounts[$slug] ?? 0 }} items</span>
                            <span class="category-tile-cta">Shop collection →</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured products --}}
    <section class="border-t border-ink/6 bg-white/40 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="text-center">
                <p class="section-label">Bestsellers</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-ink sm:text-4xl">This week's picks</h2>
                <p class="mx-auto mt-3 max-w-lg text-ink-muted">Materials chosen for longevity. Every order flows through inventory, payments, and fulfillment modules.</p>
            </div>

            <div class="mx-auto mt-12 max-w-6xl">
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('shop.index') }}" class="btn-primary">Shop all {{ $stats['products'] }} products</a>
            </div>
        </div>
    </section>

    {{-- Trust bento --}}
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-20">
        <p class="section-label text-center">Why Northline</p>
        <h2 class="mt-2 text-center font-display text-3xl font-semibold text-ink">Commerce built to scale</h2>

        <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bento-item">
                <div class="icon-box"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-ink">Cart & checkout</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-muted">Session cart, multi-line orders, Stripe Checkout Sessions, and webhook-driven payment confirmation.</p>
            </div>
            <div class="bento-item">
                <div class="icon-box icon-box-clay"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-ink">Operations console</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-muted">Filament admin for products, orders, payments, and integrations — role-scoped for teams.</p>
            </div>
            <div class="bento-item sm:col-span-2 lg:col-span-1">
                <div class="icon-box"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-ink">Modular platform</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-muted">E-commerce today. CRM, ERP, and product finder modules plug in as your stack grows.</p>
            </div>
        </div>
    </section>
@endsection
