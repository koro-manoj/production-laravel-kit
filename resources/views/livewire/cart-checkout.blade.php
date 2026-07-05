<div class="mx-auto max-w-4xl px-6 py-12 lg:px-10 lg:py-16">
    <h1 class="font-display text-4xl font-semibold text-ink">Checkout</h1>
    <p class="mt-2 text-ink-muted">Secure payment powered by Stripe.</p>

    @if($lines->isEmpty())
        <div class="card mt-10 px-8 py-16 text-center">
            <p class="text-lg text-ink-muted">Your bag is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary mt-6 inline-flex">Shop now</a>
        </div>
    @else
        <form wire:submit.prevent="checkout" class="mt-10 grid gap-10 lg:grid-cols-5">
            <div class="space-y-6 lg:col-span-3">
                <div class="card p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Contact</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="customerName" class="label-text">Full name</label>
                            <input id="customerName" type="text" wire:model="customerName" class="input-field" autocomplete="name">
                            @error('customerName') <p class="mt-1 text-sm text-clay">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="customerEmail" class="label-text">Email</label>
                            <input id="customerEmail" type="email" wire:model="customerEmail" class="input-field" autocomplete="email">
                            @error('customerEmail') <p class="mt-1 text-sm text-clay">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Items</h2>
                    <ul class="mt-4 divide-y divide-ink/6">
                        @foreach($lines as $line)
                            <li class="flex justify-between py-3 text-sm" wire:key="checkout-line-{{ $line['product']->id }}">
                                <span class="text-ink-muted">
                                    {{ $line['product']->name }}
                                    <span class="text-ink-faint">× {{ $line['quantity'] }}</span>
                                </span>
                                <span class="font-medium text-ink">
                                    {{ money_format_cents($line['product']->price_cents * $line['quantity']) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="card sticky top-24 p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Payment</h2>
                    <p class="mt-2 text-sm text-ink-muted">You'll be redirected to Stripe to complete payment.</p>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-muted">Subtotal</dt>
                            <dd class="font-semibold">{{ money_format_cents($subtotalCents) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-muted">Shipping</dt>
                            <dd class="font-medium {{ $freeShipping ? 'text-moss' : 'text-ink' }}">
                                {{ $freeShipping ? 'Free' : money_format_cents(800) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex justify-between border-t border-ink/6 pt-5">
                        <span class="font-display text-lg font-semibold">Total</span>
                        <span class="font-display text-lg font-semibold">
                            {{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}
                        </span>
                    </div>

                    @if($errorMessage)
                        <p class="mt-4 rounded-lg bg-clay/10 px-3 py-2 text-sm text-clay-dark">{{ $errorMessage }}</p>
                    @endif

                    <button type="submit" class="btn-primary mt-6 w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="checkout">Pay with Stripe</span>
                        <span wire:loading wire:target="checkout">Redirecting…</span>
                    </button>

                    <a href="{{ route('cart.index') }}" class="btn-ghost mt-4 w-full justify-center">← Back to bag</a>
                </div>
            </div>
        </form>
    @endif
</div>
