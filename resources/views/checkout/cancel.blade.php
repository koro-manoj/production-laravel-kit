@extends('layouts.app')

@section('title', 'Checkout cancelled')

@section('content')
    <div class="mx-auto max-w-lg text-center">
        <div class="card px-8 py-12">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-ink/5">
                <svg class="h-8 w-8 text-ink-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h1 class="mt-6 font-display text-3xl font-semibold text-ink">Checkout cancelled</h1>
            <p class="mt-3 text-ink-muted">Order <span class="font-mono text-sm text-ink">{{ $order->reference }}</span> was not completed.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('home') }}" class="btn-secondary">Return home</a>
                <a href="{{ route('checkout.show', $order->product?->slug ?? 'consultation-package') }}" class="btn-primary">Try again</a>
            </div>
        </div>
    </div>
@endsection
