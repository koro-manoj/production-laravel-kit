<div class="mx-auto max-w-5xl px-6 py-10 lg:px-10 lg:py-14">
    <h1 class="font-display text-4xl font-semibold text-ink">Your bag</h1>
    <p class="mt-2 text-ink-muted">{{ $lines->count() }} {{ $lines->count() === 1 ? 'item' : 'items' }}</p>

    @if($lines->isEmpty())
        <div class="card mt-10 px-8 py-16 text-center">
            <p class="text-lg text-ink-muted">Your bag is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary mt-6 inline-flex">Continue shopping</a>
        </div>
    @else
        <div class="mt-8 grid gap-8 lg:grid-cols-5 lg:gap-10">
            <div class="space-y-4 lg:col-span-3">
                @foreach($lines as $line)
                    @include('partials.cart-line-item', ['line' => $line])
                @endforeach
            </div>

            <div class="lg:col-span-2">
                <div class="checkout-summary card sticky top-24 p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Order summary</h2>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-ink-muted">Subtotal</dt>
                            <dd class="font-semibold tabular-nums text-ink">{{ money_format_cents($subtotalCents) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-ink-muted">Shipping</dt>
                            <dd class="font-medium tabular-nums {{ $freeShipping ? 'text-moss' : 'text-ink' }}">
                                @if($freeShipping)
                                    Free
                                @else
                                    {{ money_format_cents(800) }}
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if(! $freeShipping && $subtotalCents > 0)
                        <p class="mt-4 rounded-lg bg-cream px-3 py-2 text-xs leading-relaxed text-ink-muted">
                            Add {{ money_format_cents(max(0, $thresholdCents - $subtotalCents)) }} more for free shipping.
                        </p>
                    @endif

                    <div class="mt-5 flex justify-between border-t border-ink/6 pt-5">
                        <span class="font-display text-lg font-semibold text-ink">Total</span>
                        <span class="font-display text-lg font-semibold tabular-nums text-ink">
                            {{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}
                        </span>
                    </div>

                    <a href="{{ route('cart.checkout') }}" class="btn-add-bag mt-6 w-full">
                        <span class="btn-add-bag-main">Proceed to checkout</span>
                        <span class="btn-add-bag-price">{{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}</span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="btn-ghost mt-4 w-full justify-center">Continue shopping</a>
                </div>
            </div>
        </div>
    @endif
</div>
