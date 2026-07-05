<div class="product-add-panel">
    <div class="product-add-panel-row">
        <span class="product-add-label">Quantity</span>
        <div class="qty-stepper">
            <button
                type="button"
                wire:click="decrementQuantity"
                class="qty-stepper-btn"
                aria-label="Decrease quantity"
                @disabled($quantity <= 1)
            >−</button>
            <span class="qty-stepper-value">{{ $quantity }}</span>
            <button
                type="button"
                wire:click="incrementQuantity"
                class="qty-stepper-btn"
                aria-label="Increase quantity"
                @disabled($quantity >= 10)
            >+</button>
        </div>
    </div>

    <button
        type="button"
        wire:click="addToCart"
        wire:loading.attr="disabled"
        class="btn-add-bag"
    >
        <span class="btn-add-bag-main" wire:loading.remove wire:target="addToCart">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            Add to bag
        </span>
        <span class="btn-add-bag-main" wire:loading wire:target="addToCart">
            Adding to bag…
        </span>
        <span class="btn-add-bag-price" wire:loading.remove wire:target="addToCart">
            {{ money_format_cents($lineTotalCents) }}
        </span>
    </button>

    @if($added)
        <div class="product-add-success">
            <svg class="h-4 w-4 shrink-0 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-sm font-medium text-moss">Added to your bag</p>
            <a href="{{ route('cart.index') }}" class="ml-auto text-sm font-semibold text-moss hover:underline">View bag</a>
        </div>
    @endif
</div>
