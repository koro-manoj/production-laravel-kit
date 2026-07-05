<div class="mx-auto max-w-5xl px-6 py-10 lg:px-10 lg:py-14">
    <nav class="mb-6 text-sm text-ink-muted">
        <a href="{{ route('cart.index') }}" class="hover:text-moss">Bag</a>
        <span class="mx-2">/</span>
        <span class="text-ink">Checkout</span>
    </nav>

    <h1 class="font-display text-4xl font-semibold text-ink">Checkout</h1>
    <p class="mt-2 text-ink-muted">Secure payment powered by Stripe.</p>

    @if($lines->isEmpty())
        <div class="card mt-10 px-8 py-16 text-center">
            <p class="text-lg text-ink-muted">Your bag is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary mt-6 inline-flex">Shop now</a>
        </div>
    @else
        <form wire:submit.prevent="checkout" class="mt-8 grid gap-8 lg:grid-cols-5 lg:gap-10">
            <div class="space-y-6 lg:col-span-3">
                <div class="card p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Contact</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="customerName" class="label-text">Full name</label>
                            <input id="customerName" type="text" wire:model="customerName" class="input-field" autocomplete="name" placeholder="Alex Rivera">
                            @error('customerName') <p class="mt-1 text-sm text-clay">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="customerEmail" class="label-text">Email</label>
                            <input id="customerEmail" type="email" wire:model="customerEmail" class="input-field" autocomplete="email" placeholder="you@example.com">
                            @error('customerEmail') <p class="mt-1 text-sm text-clay">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-xl font-semibold text-ink">Your order</h2>
                        <span class="text-sm text-ink-muted">{{ $lines->count() }} {{ $lines->count() === 1 ? 'item' : 'items' }}</span>
                    </div>
                    <ul class="mt-4 divide-y divide-ink/6">
                        @foreach($lines as $line)
                            @include('partials.checkout-line-item', ['line' => $line])
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="checkout-summary card sticky top-24 p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Payment</h2>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">You'll be redirected to Stripe to complete payment securely.</p>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-ink-muted">Subtotal</dt>
                            <dd class="font-semibold tabular-nums text-ink">{{ money_format_cents($subtotalCents) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-ink-muted">Shipping</dt>
                            <dd class="font-medium tabular-nums {{ $freeShipping ? 'text-moss' : 'text-ink' }}">
                                {{ $freeShipping ? 'Free' : money_format_cents(800) }}
                            </dd>
                        </div>
                    </dl>

                    @if(! $freeShipping)
                        <p class="mt-4 rounded-lg bg-cream px-3 py-2 text-xs leading-relaxed text-ink-muted">
                            Free shipping on orders over {{ money_format_cents((int) config('store.free_shipping_threshold_cents')) }}.
                        </p>
                    @endif

                    <div class="mt-5 flex justify-between border-t border-ink/6 pt-5">
                        <span class="font-display text-lg font-semibold text-ink">Total</span>
                        <span class="font-display text-lg font-semibold tabular-nums text-ink">
                            {{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}
                        </span>
                    </div>

                    @if($errorMessage)
                        <p class="mt-4 rounded-lg bg-clay/10 px-3 py-2 text-sm text-clay-dark">{{ $errorMessage }}</p>
                    @endif

                    <button type="submit" class="btn-add-bag mt-6 w-full" wire:loading.attr="disabled">
                        <span class="btn-add-bag-main" wire:loading.remove wire:target="checkout">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Pay with Stripe
                        </span>
                        <span class="btn-add-bag-main" wire:loading wire:target="checkout">Redirecting…</span>
                        <span class="btn-add-bag-price" wire:loading.remove wire:target="checkout">
                            {{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}
                        </span>
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn-ghost mt-4 w-full justify-center">← Back to bag</a>

                    <ul class="mt-5 space-y-2 border-t border-ink/6 pt-5 text-xs text-ink-muted">
                        @foreach(config('store.trust') as $item)
                            <li class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 shrink-0 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </form>
    @endif
</div>
