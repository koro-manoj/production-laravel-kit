@extends('layouts.app')

@section('title', 'Payment successful')

@section('content')
    <div class="mx-auto max-w-lg text-center">
        <div class="card-elevated px-8 py-12">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-moss/10">
                <svg class="h-8 w-8 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="mt-6 font-display text-3xl font-semibold text-ink">Thank you</h1>
            <p class="mt-3 text-ink-muted">Order <span class="font-mono text-sm text-ink">{{ $order->reference }}</span> is {{ $order->status }}.</p>
            <p class="mt-2 text-sm text-ink-faint">${{ number_format($order->amount_cents / 100, 2) }} {{ strtoupper($order->currency) }}</p>
            <a href="{{ route('home') }}" class="btn-secondary mt-8">
                Back to home
            </a>
        </div>
    </div>
@endsection
