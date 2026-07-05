<div class="mx-auto max-w-lg animate-fade-up">
    <div class="card-elevated overflow-hidden">
        <div class="border-b border-ink/6 bg-gradient-to-r from-moss/8 to-transparent px-8 py-7">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-moss">Secure checkout</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-ink">{{ $product->name }}</h1>
            <p class="mt-2 text-ink-muted">{{ $product->description }}</p>
        </div>

        <div class="px-8 py-7">
            <div class="flex items-end justify-between rounded-xl bg-cream px-5 py-4">
                <span class="text-sm font-medium text-ink-muted">Total due</span>
                <span class="font-display text-4xl font-semibold text-moss">${{ number_format($product->price_cents / 100, 2) }}</span>
            </div>

            <form wire:submit="startCheckout" class="mt-7 space-y-5">
                <div>
                    <label class="label-text">Full name</label>
                    <input type="text" wire:model="customerName" class="input-field" autocomplete="name">
                    @error('customerName') <p class="mt-1.5 text-sm text-clay">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label-text">Email address</label>
                    <input type="email" wire:model="customerEmail" class="input-field" autocomplete="email">
                    @error('customerEmail') <p class="mt-1.5 text-sm text-clay">{{ $message }}</p> @enderror
                </div>

                @if($errorMessage)
                    <div class="rounded-xl border border-clay/25 bg-clay/5 px-4 py-3 text-sm text-clay-dark">{{ $errorMessage }}</div>
                @endif

                <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full disabled:opacity-60">
                    <span wire:loading.remove>Pay with Stripe</span>
                    <span wire:loading>Redirecting…</span>
                </button>

                <p class="text-center text-xs text-ink-faint">256-bit encryption · Sandbox mode · Keys stored in database</p>
            </form>
        </div>
    </div>
</div>
