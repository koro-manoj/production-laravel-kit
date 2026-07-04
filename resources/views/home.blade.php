@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <section class="grid gap-10 lg:grid-cols-2 lg:items-center">
        <div class="space-y-6">
            <p class="inline-flex rounded-full border border-indigo-400/30 bg-indigo-500/10 px-3 py-1 text-xs font-medium uppercase tracking-wider text-indigo-200">
                Modular Laravel showcase
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                Branching quiz funnels with Stripe checkout and mobile API auth.
            </h1>
            <p class="text-lg leading-relaxed text-slate-300">
                Domain-driven modules for quiz, payments, integrations, and Sanctum API — Filament admin, Horizon queues, and encrypted provider credentials stored in the database.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('quiz.show', 'health-assessment') }}"
                   class="rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white shadow-lg shadow-indigo-500/20 hover:bg-indigo-400">
                    Start health quiz
                </a>
                <a href="{{ route('checkout.show', 'consultation-package') }}"
                   class="rounded-xl border border-white/15 px-5 py-3 font-semibold text-white hover:bg-white/5">
                    View checkout
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900 to-slate-800 p-8 shadow-2xl">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-indigo-300">Modules</h2>
            <ul class="mt-6 space-y-4 text-sm text-slate-200">
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Quiz funnel with branching logic and session persistence</li>
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Stripe sandbox payments with DB-stored encrypted credentials</li>
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Sanctum API for mobile-style auth and checkout</li>
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span> Filament admin with Spatie roles: Admin, Clinic, Pharmacy, Patient</li>
            </ul>
        </div>
    </section>
@endsection
