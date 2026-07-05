@extends('layouts.store')

@section('title', $product->name.' — '.config('store.name'))

@section('content')
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-10 lg:py-16">
        <nav class="mb-8 text-sm text-ink-muted">
            <a href="{{ route('shop.index') }}" class="hover:text-moss">Shop</a>
            <span class="mx-2">/</span>
            <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="hover:text-moss">
                {{ $categories[$product->category] ?? $product->category }}
            </a>
            <span class="mx-2">/</span>
            <span class="text-ink">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
            @include('partials.product-gallery', ['product' => $product])

            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-ink-faint">
                    {{ $categories[$product->category] ?? $product->category }}
                </p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-ink">{{ $product->name }}</h1>

                <div class="mt-4 flex items-baseline gap-3">
                    <span class="font-display text-3xl font-semibold text-ink">{{ money_format_cents($product->price_cents) }}</span>
                    @if($product->compare_at_price_cents)
                        <span class="text-lg text-ink-faint line-through">{{ money_format_cents($product->compare_at_price_cents) }}</span>
                        @php
                            $savings = $product->compare_at_price_cents - $product->price_cents;
                        @endphp
                        @if($savings > 0)
                            <span class="rounded-full bg-clay/10 px-3 py-1 text-xs font-semibold text-clay">
                                Save {{ money_format_cents($savings) }}
                            </span>
                        @endif
                    @endif
                </div>

                <p class="mt-6 text-base leading-relaxed text-ink-muted">{{ $product->description }}</p>

                <ul class="mt-6 space-y-2 text-sm text-ink-muted">
                    @foreach(config('store.trust') as $item)
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8">
                    <livewire:add-to-cart :product="$product" />
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="mt-20 border-t border-ink/6 pt-16">
                <h2 class="font-display text-2xl font-semibold text-ink">You may also like</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    @foreach($related as $item)
                        @include('partials.product-card', ['product' => $item])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
