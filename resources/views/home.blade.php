@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <section class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
        <div class="space-y-8">
            <p class="animate-fade-up inline-flex items-center gap-2 rounded-full border border-moss/15 bg-moss/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-moss">
                <span class="h-1.5 w-1.5 rounded-full bg-clay"></span>
                Modular Laravel showcase
            </p>

            <h1 class="animate-fade-up stagger-1 font-display text-4xl font-semibold leading-[1.12] tracking-tight text-ink sm:text-5xl lg:text-[3.25rem]">
                Branching quiz funnels with Stripe checkout and mobile API auth.
            </h1>

            <p class="animate-fade-up stagger-2 max-w-xl text-lg leading-relaxed text-ink-muted">
                Domain-driven modules for quiz, payments, integrations, and Sanctum API — Filament admin, Horizon queues, and encrypted provider credentials stored in the database.
            </p>

            <div class="animate-fade-up stagger-3 flex flex-wrap gap-3">
                <a href="{{ route('quiz.show', 'health-assessment') }}" class="btn-primary">
                    Start health quiz
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="{{ route('checkout.show', 'consultation-package') }}" class="btn-secondary">
                    View checkout
                </a>
            </div>

            <dl class="animate-fade-up stagger-4 grid grid-cols-3 gap-6 border-t border-ink/8 pt-8">
                <div>
                    <dt class="font-display text-2xl font-semibold text-moss">4</dt>
                    <dd class="mt-1 text-sm text-ink-faint">Domain modules</dd>
                </div>
                <div>
                    <dt class="font-display text-2xl font-semibold text-moss">DB</dt>
                    <dd class="mt-1 text-sm text-ink-faint">Encrypted Stripe keys</dd>
                </div>
                <div>
                    <dt class="font-display text-2xl font-semibold text-moss">API</dt>
                    <dd class="mt-1 text-sm text-ink-faint">Sanctum + Livewire</dd>
                </div>
            </dl>
        </div>

        <div class="animate-fade-up stagger-2 card-elevated relative overflow-hidden p-8 lg:p-10">
            <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-moss/8"></div>
            <div class="pointer-events-none absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-clay/8"></div>

            <h2 class="relative text-xs font-bold uppercase tracking-widest text-moss">Modules</h2>
            <ul class="relative mt-7 space-y-5">
                <li class="flex items-start gap-4">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-moss/10 text-xs font-bold text-moss">1</span>
                    <div>
                        <p class="font-semibold text-ink">Quiz funnel</p>
                        <p class="mt-0.5 text-sm text-ink-muted">Branching logic with session persistence and outcome routing</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-moss/10 text-xs font-bold text-moss">2</span>
                    <div>
                        <p class="font-semibold text-ink">Stripe payments</p>
                        <p class="mt-0.5 text-sm text-ink-muted">Sandbox checkout with DB-stored encrypted credentials</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-moss/10 text-xs font-bold text-moss">3</span>
                    <div>
                        <p class="font-semibold text-ink">Mobile API</p>
                        <p class="mt-0.5 text-sm text-ink-muted">Sanctum auth, quiz sessions, and checkout endpoints</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-moss/10 text-xs font-bold text-moss">4</span>
                    <div>
                        <p class="font-semibold text-ink">Filament admin</p>
                        <p class="mt-0.5 text-sm text-ink-muted">Spatie roles: Admin, Clinic, Pharmacy, Patient</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>
@endsection
