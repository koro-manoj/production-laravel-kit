<div class="mx-auto max-w-4xl px-6 py-12 lg:px-10 lg:py-16">
    <h1 class="font-display text-4xl font-semibold text-ink">Your bag</h1>

    @if($lines->isEmpty())
        <div class="card mt-10 px-8 py-16 text-center">
            <p class="text-lg text-ink-muted">Your bag is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary mt-6 inline-flex">Continue shopping</a>
        </div>
    @else
        <div class="mt-10 grid gap-10 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-3">
                @foreach($lines as $line)
                    @php $product = $line['product']; @endphp
                    <div class="card flex gap-4 p-4 sm:gap-6 sm:p-5" wire:key="cart-line-{{ $product->id }}">
                        <div @class([
                            'product-thumb h-20 w-20 shrink-0 rounded-xl',
                            'product-thumb--'.$product->category => true,
                        ])>
                            <span class="product-thumb-letter text-xl">{{ mb_substr($product->name, 0, 1) }}</span>
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
                        <p class="shrink-0 font-semibold text-ink">
                            {{ money_format_cents($product->price_cents * $line['quantity']) }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-2">
                <div class="card sticky top-24 p-6">
                    <h2 class="font-display text-xl font-semibold text-ink">Order summary</h2>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink-muted">Subtotal</dt>
                            <dd class="font-semibold text-ink">{{ money_format_cents($subtotalCents) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-muted">Shipping</dt>
                            <dd class="font-medium {{ $freeShipping ? 'text-moss' : 'text-ink' }}">
                                @if($freeShipping)
                                    Free
                                @else
                                    {{ money_format_cents(800) }}
                                    <span class="block text-xs font-normal text-ink-faint">Free over {{ money_format_cents($thresholdCents) }}</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if(! $freeShipping && $subtotalCents > 0)
                        <p class="mt-4 rounded-lg bg-cream px-3 py-2 text-xs text-ink-muted">
                            Add {{ money_format_cents(max(0, $thresholdCents - $subtotalCents)) }} more for free shipping.
                        </p>
                    @endif

                    <div class="mt-5 flex justify-between border-t border-ink/6 pt-5">
                        <span class="font-display text-lg font-semibold text-ink">Total</span>
                        <span class="font-display text-lg font-semibold text-ink">
                            {{ money_format_cents($subtotalCents + ($freeShipping ? 0 : 800)) }}
                        </span>
                    </div>

                    <a href="{{ route('cart.checkout') }}" class="btn-primary mt-6 w-full">Proceed to checkout</a>
                    <a href="{{ route('shop.index') }}" class="btn-ghost mt-4 w-full justify-center">Continue shopping</a>
                </div>
            </div>
        </div>
    @endif
</div>
