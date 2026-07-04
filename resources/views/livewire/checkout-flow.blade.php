<div class="mx-auto max-w-lg">
    <div class="card-elevated overflow-hidden">
        <div class="border-b border-ink/6 bg-paper-warm/80 px-8 py-6">
            <p class="text-xs font-bold uppercase tracking-widest text-moss">Secure checkout</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-ink">{{ $product->name }}</h1>
            <p class="mt-2 text-ink-muted">{{ $product->description }}</p>
        </div>

        <div class="px-8 py-6">
            <div class="flex items-baseline justify-between border-b border-ink/6 pb-6">
                <span class="text-sm font-medium text-ink-muted">Total</span>
                <span class="font-display text-4xl font-semibold text-ink">${{ number_format($product->price_cents / 100, 2) }}</span>
            </div>

            <form wire:submit="startCheckout" class="mt-6 space-y-5">
                <div>
                    <label class="label-text">Full name</label>
                    <input type="text" wire:model="customerName" class="input-field" autocomplete="name">
                    @error('customerName') <p class="mt-1.5 text-sm text-clay">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label-text">Email</label>
                    <input type="email" wire:model="customerEmail" class="input-field" autocomplete="email">
                    @error('customerEmail') <p class="mt-1.5 text-sm text-clay">{{ $message }}</p> @enderror
                </div>

                @if($errorMessage)
                    <div class="rounded-xl border border-clay/20 bg-clay/5 px-4 py-3 text-sm text-clay-dark">
                        {{ $errorMessage }}
                    </div>
                @endif

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="btn-primary w-full disabled:opacity-60">
                    <span wire:loading.remove class="flex items-center justify-center gap-2">
                        Pay with Stripe
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <span wire:loading>Redirecting&hellip;</span>
                </button>

                <p class="text-center text-xs text-ink-faint">Payments processed securely via Stripe sandbox. Credentials stored encrypted in the database.</p>
            </form>
        </div>
    </div>
</div>
