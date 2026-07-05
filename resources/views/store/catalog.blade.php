@extends('layouts.store')

@section('title', 'Shop — '.config('store.name'))

@section('content')
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-10 lg:py-16">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <p class="section-label">Shop</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-ink">
                    @if($activeCategory && isset($categories[$activeCategory]))
                        {{ $categories[$activeCategory] }}
                    @else
                        All products
                    @endif
                </h1>
                <p class="mt-2 text-ink-muted">{{ $products->count() }} {{ $products->count() === 1 ? 'item' : 'items' }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('shop.index') }}" @class([
                    'rounded-full px-4 py-2 text-sm font-medium transition',
                    'bg-moss text-white' => ! $activeCategory,
                    'bg-white text-ink-muted ring-1 ring-ink/10 hover:text-ink' => $activeCategory,
                ])>All</a>
                @foreach($categories as $slug => $label)
                    <a href="{{ route('shop.index', ['category' => $slug]) }}" @class([
                        'rounded-full px-4 py-2 text-sm font-medium transition',
                        'bg-moss text-white' => $activeCategory === $slug,
                        'bg-white text-ink-muted ring-1 ring-ink/10 hover:text-ink' => $activeCategory !== $slug,
                    ])>{{ $label }}</a>
                @endforeach
            </div>
        </div>

        @if($products->isEmpty())
            <div class="card mt-12 px-8 py-16 text-center">
                <p class="font-display text-xl font-semibold text-ink">No products in this collection yet.</p>
                <a href="{{ route('shop.index') }}" class="btn-ghost mt-4 inline-flex">Browse all products</a>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </div>
@endsection
