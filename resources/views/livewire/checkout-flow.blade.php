<div class="mx-auto max-w-xl">
    <div class="rounded-3xl border border-white/10 bg-slate-900/70 p-8 shadow-xl">
        <p class="text-sm uppercase tracking-wider text-indigo-300">Secure checkout</p>
        <h1 class="mt-2 text-3xl font-bold text-white">{{ $product->name }}</h1>
        <p class="mt-3 text-slate-300">{{ $product->description }}</p>
        <p class="mt-6 text-4xl font-bold text-white">${{ number_format($product->price_cents / 100, 2) }}</p>

        <form wire:submit="startCheckout" class="mt-8 space-y-4">
            <div>
                <label class="mb-1 block text-sm text-slate-400">Full name</label>
                <input type="text" wire:model="customerName"
                       class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-indigo-400 focus:outline-none">
                @error('customerName') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm text-slate-400">Email</label>
                <input type="email" wire:model="customerEmail"
                       class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-white focus:border-indigo-400 focus:outline-none">
                @error('customerEmail') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
            </div>

            @if($errorMessage)
                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                    {{ $errorMessage }}
                </div>
            @endif

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full rounded-xl bg-indigo-500 px-5 py-3 font-semibold text-white hover:bg-indigo-400 disabled:opacity-60">
                <span wire:loading.remove>Pay with Stripe</span>
                <span wire:loading>Redirecting…</span>
            </button>
        </form>
    </div>
</div>
