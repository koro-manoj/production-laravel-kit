@php
    $product = $line['product'];
    $imageUrl = $product->primaryImageUrl();
    $thumbClass = match ($product->category) {
        'desk' => 'product-thumb--desk',
        'outdoor' => 'product-thumb--outdoor',
        default => 'product-thumb--home',
    };
@endphp

<li class="checkout-line" wire:key="checkout-line-{{ $product->id }}">
    <div @class(['checkout-line-thumb', $thumbClass => ! $imageUrl])>
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover object-center">
        @else
            <span class="product-thumb-letter text-lg">{{ mb_substr($product->name, 0, 1) }}</span>
        @endif
    </div>
    <div class="min-w-0 flex-1">
        <p class="font-display font-semibold text-ink">{{ $product->name }}</p>
        <p class="mt-0.5 text-sm text-ink-muted">
            {{ money_format_cents($product->price_cents) }} · Qty {{ $line['quantity'] }}
        </p>
    </div>
    <p class="shrink-0 font-semibold tabular-nums text-ink">
        {{ money_format_cents($product->price_cents * $line['quantity']) }}
    </p>
</li>
