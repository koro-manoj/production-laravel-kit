@extends('layouts.app')

@section('title', 'Koro Kit — Telehealth assessments & checkout')

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
                    Telehealth commerce
                </div>

                <h1 class="mt-7 font-display text-[2.6rem] font-semibold leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-[3.75rem]">
                    Route every patient to the right care plan.
                </h1>

                <p class="mt-6 max-w-lg text-lg leading-relaxed text-white/70">
                    Branching health assessments, outcome-driven checkout, encrypted Stripe credentials in the database, and a full operations console — built as Laravel domain modules.
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    @if($quizzes->isNotEmpty())
                        <a href="{{ route('quiz.show', $quizzes->first()) }}" class="btn-primary btn-primary-dark">
                            Start assessment
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn-secondary">View care packages</a>
                </div>

                <dl class="mt-12 grid grid-cols-3 gap-4 sm:max-w-md">
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['completions'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Completions</dd>
                    </div>
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['orders'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Paid orders</dd>
                    </div>
                    <div class="stat-pill">
                        <dt class="text-2xl font-display font-semibold text-white">{{ $stats['products'] }}</dt>
                        <dd class="mt-1 text-[11px] uppercase tracking-wider text-white/50">Packages</dd>
                    </div>
                </dl>
            </div>

            {{-- Product preview mock --}}
            <div class="animate-fade-up stagger-2 animate-float relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-2 shadow-glow backdrop-blur-xl">
                    <div class="overflow-hidden rounded-xl bg-cream">
                        <div class="flex items-center justify-between border-b border-ink/6 bg-white px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-clay/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400/80"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-moss/60"></span>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-ink-faint">Live session</span>
                        </div>
                        <div class="p-5">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-moss">Health assessment</p>
                            <p class="mt-2 font-display text-lg font-semibold text-ink">How are you feeling today?</p>
                            <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-ink/8">
                                <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-moss to-moss-glow"></div>
                            </div>
                            <p class="mt-1.5 text-right text-[10px] text-ink-faint">Step 2 of 3</p>
                            <div class="mt-4 space-y-2">
                                <div class="rounded-lg border border-moss/25 bg-moss/5 px-3 py-2.5 text-sm font-medium text-moss">Mild symptoms — manageable</div>
                                <div class="rounded-lg border border-ink/8 bg-white px-3 py-2.5 text-sm text-ink-muted">Moderate — affecting daily life</div>
                                <div class="rounded-lg border border-ink/8 bg-white px-3 py-2.5 text-sm text-ink-muted">Severe — need urgent review</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-px border-t border-ink/6 bg-ink/6">
                            <div class="bg-white px-4 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-ink-faint">Outcome</p>
                                <p class="mt-0.5 text-sm font-semibold text-ink">Wellness plan</p>
                            </div>
                            <div class="bg-white px-4 py-3">
                                <p class="text-[10px] uppercase tracking-wider text-ink-faint">Checkout</p>
                                <p class="mt-0.5 text-sm font-semibold text-moss">$49.00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-4 -left-4 hidden rounded-xl border border-ink/8 bg-white px-4 py-3 shadow-lift lg:block">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-ink-faint">Stripe sandbox</p>
                    <p class="mt-0.5 text-sm font-semibold text-moss">Keys in database</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="text-center">
            <p class="section-label">Patient journey</p>
            <h2 class="mt-3 font-display text-3xl font-semibold text-ink sm:text-4xl">From symptom to checkout in three steps</h2>
        </div>
        <div class="mt-14 grid gap-8 md:grid-cols-3">
            @foreach([
                ['01', 'Take assessment', 'Branching quiz adapts to answers and stores the session for web and mobile API.', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['02', 'Get recommendation', 'Terminal outcomes map to care packages — wellness, consultation, or escalation.', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['03', 'Secure checkout', 'Stripe sandbox checkout with encrypted credentials — no keys in .env.', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ] as [$num, $title, $desc, $path])
                <div class="bento-item text-center md:text-left">
                    <span class="font-display text-4xl font-semibold text-moss/15">{{ $num }}</span>
                    <div class="icon-box mx-auto mt-4 md:mx-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                    </div>
                    <h3 class="mt-5 font-display text-xl font-semibold text-ink">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Assessments --}}
    @if($quizzes->isNotEmpty())
        <section class="bg-mesh-light border-y border-ink/6 py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="section-label">Assessments</p>
                        <h2 class="mt-3 font-display text-3xl font-semibold text-ink">Active intake flows</h2>
                    </div>
                    <a href="{{ route('quiz.show', $quizzes->first()) }}" class="btn-ghost">Start now →</a>
                </div>
                <div class="mt-10 grid gap-6 md:grid-cols-2">
                    @foreach($quizzes as $quiz)
                        <a href="{{ route('quiz.show', $quiz) }}" class="group card-elevated flex gap-6 p-8 transition hover:-translate-y-1">
                            <div class="icon-box icon-box-clay h-14 w-14 rounded-2xl">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-clay">{{ $quiz->questions_count }} questions · ~3 min</p>
                                <h3 class="mt-2 font-display text-2xl font-semibold text-ink group-hover:text-moss">{{ $quiz->title }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $quiz->description }}</p>
                                <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-moss">Begin assessment →</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Products --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="text-center">
            <p class="section-label section-label-light">Care packages</p>
            <h2 class="mt-3 font-display text-3xl font-semibold text-ink sm:text-4xl">Outcome-matched offerings</h2>
            <p class="mx-auto mt-3 max-w-xl text-ink-muted">Each package ties to quiz outcomes. Prices in cents, charged through Stripe sandbox.</p>
        </div>
        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach($products as $i => $product)
                <article @class(['pricing-card', 'pricing-card-featured lg:-mt-4 lg:mb-4' => $i === 1])>
                    @if($i === 1)
                        <span class="pricing-badge">Most popular</span>
                    @endif
                    <p class="text-[11px] font-bold uppercase tracking-wider text-ink-faint">{{ strtoupper($product->currency) }}</p>
                    <h3 class="mt-3 font-display text-2xl font-semibold text-ink">{{ $product->name }}</h3>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-muted">{{ $product->description }}</p>
                    <div class="mt-8 border-t border-ink/6 pt-6">
                        <span class="font-display text-4xl font-semibold text-moss">${{ number_format($product->price_cents / 100, 0) }}</span>
                        <span class="text-ink-faint">.{{ str_pad((string) ($product->price_cents % 100), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <a href="{{ route('checkout.show', $product) }}" @class([
                        'mt-6 block w-full rounded-full py-3.5 text-center text-sm font-semibold transition',
                        'bg-moss text-white hover:bg-moss-light' => $i === 1,
                        'border border-ink/12 bg-cream text-ink hover:border-moss/30 hover:text-moss' => $i !== 1,
                    ])>Continue to checkout</a>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Bento tech --}}
    <section class="border-t border-ink/6 bg-cream py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <p class="section-label">Architecture</p>
            <h2 class="mt-3 max-w-xl font-display text-3xl font-semibold text-ink">Four domain modules, one cohesive stack</h2>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['Quiz', 'Branching funnel + session API', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['Payments', 'Stripe + webhook idempotency', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['Integrations', 'Encrypted DB credentials', 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
                    ['Admin', 'Filament + Spatie roles', 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                ] as [$title, $desc, $path])
                    <div class="bento-item">
                        <div class="icon-box">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-ink">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm text-ink-muted">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="relative overflow-hidden rounded-3xl bg-hero-gradient px-8 py-14 lg:px-16 lg:py-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/5 blur-2xl"></div>
            <div class="relative flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-center">
                <div>
                    <h2 class="font-display text-3xl font-semibold text-white lg:text-4xl">Operations console</h2>
                    <p class="mt-3 max-w-lg text-white/65">Manage quizzes, branching logic, orders, payments, integrations, and users — scoped by Admin, Clinic, and Pharmacy roles.</p>
                </div>
                <a href="/admin" class="btn-primary btn-primary-dark shrink-0">Open admin panel</a>
            </div>
        </div>
    </section>
@endsection
