@php
    $product = $line['product'];
    $imageUrl = $product->primaryImageUrl();
    $thumbClass = match ($product->category) {
        'desk' => 'product-thumb--desk',
        'outdoor' => 'product-thumb--outdoor',
        default => 'product-thumb--home',
    };
@endphp

<div class="card flex gap-4 p-4 sm:gap-5 sm:p-5" wire:key="cart-line-{{ $product->id }}">
    <div @class(['checkout-line-thumb', $thumbClass => ! $imageUrl])>
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover object-center">
        @else
            <span class="product-thumb-letter text-lg">{{ mb_substr($product->name, 0, 1) }}</span>
        @endif
    </div>
    <div class="min-w-0 flex-1">
        <a href="{{ route('shop.show', $product) }}" class="font-display text-lg font-semibold text-ink hover:text-moss">
            {{ $product->name }}
        </a>
        <p class="mt-1 text-sm text-ink-muted">{{ money_format_cents($product->price_cents) }} each</p>
        <div class="mt-3 flex flex-wrap items-center gap-4">
            <select
                class="input-field w-20 py-2 text-sm"
                wire:change="updateQuantity({{ $product->id }}, $event.target.value)"
            >
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" @selected($line['quantity'] === $i)>{{ $i }}</option>
                @endfor
            </select>
            <button
                type="button"
                wire:click="remove({{ $product->id }})"
                class="text-sm font-medium text-ink-faint transition hover:text-clay"
            >
                Remove
            </button>
        </div>
    </div>
    <p class="shrink-0 self-start font-semibold tabular-nums text-ink">
        {{ money_format_cents($product->price_cents * $line['quantity']) }}
    </p>
</div>
