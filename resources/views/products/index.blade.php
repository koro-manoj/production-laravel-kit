@extends('layouts.app')

@section('title', 'Care packages — Koro Kit')

@section('content')
    <section class="border-b border-ink/6 bg-hero-gradient py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-clay">Commerce</p>
            <h1 class="mt-4 font-display text-4xl font-semibold text-white sm:text-5xl">Care packages</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/65">Outcome-matched offerings linked to assessment flows. Secure Stripe checkout with credentials stored encrypted in the database.</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-3">
            @foreach($products as $i => $product)
                <article @class(['pricing-card', 'pricing-card-featured' => $i === 1])>
                    @if($i === 1)
                        <span class="pricing-badge">Recommended</span>
                    @endif
                    <h2 class="font-display text-2xl font-semibold text-ink">{{ $product->name }}</h2>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-muted">{{ $product->description }}</p>
                    <div class="mt-8 border-t border-ink/6 pt-6">
                        <span class="font-display text-4xl font-semibold text-moss">${{ number_format($product->price_cents / 100, 2) }}</span>
                        <span class="ml-2 text-sm text-ink-faint">{{ strtoupper($product->currency) }}</span>
                    </div>
                    <ul class="mt-6 space-y-2 text-sm text-ink-muted">
                        <li class="flex items-center gap-2"><span class="text-moss">✓</span> Stripe sandbox checkout</li>
                        <li class="flex items-center gap-2"><span class="text-moss">✓</span> Email receipt queued</li>
                        <li class="flex items-center gap-2"><span class="text-moss">✓</span> Linked to quiz outcomes</li>
                    </ul>
                    <a href="{{ route('checkout.show', $product) }}" @class([
                        'mt-8 block w-full rounded-full py-3.5 text-center text-sm font-semibold transition',
                        'bg-moss text-white hover:bg-moss-light' => $i === 1,
                        'border border-ink/12 text-ink hover:border-moss hover:text-moss' => $i !== 1,
                    ])>Continue to checkout</a>
                </article>
            @endforeach
        </div>
    </section>
@endsection
