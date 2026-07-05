<footer class="border-t border-ink/8 bg-white/60 backdrop-blur-sm">
    <div class="mx-auto max-w-7xl px-6 py-14 lg:px-10">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-moss font-display text-base font-semibold text-white">N</span>
                    <span class="font-display text-xl font-semibold text-ink">{{ config('store.legal_name') }}</span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-muted">{{ config('store.description') }}</p>
                <ul class="mt-5 flex flex-wrap gap-x-5 gap-y-2">
                    @foreach(config('store.trust') as $item)
                        <li class="flex items-center gap-1.5 text-xs font-medium text-ink-muted">
                            <svg class="h-3.5 w-3.5 text-moss" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-ink-faint">Shop</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('shop.index') }}" class="text-ink-muted transition hover:text-moss">All products</a></li>
                    @foreach(config('store.categories') as $slug => $label)
                        <li><a href="{{ route('shop.index', ['category' => $slug]) }}" class="text-ink-muted transition hover:text-moss">{{ $label }}</a></li>
                    @endforeach
                    <li><a href="{{ route('cart.index') }}" class="text-ink-muted transition hover:text-moss">Your bag</a></li>
                </ul>
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-ink-faint">Operations</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><strong class="text-ink-muted">Admin console</strong> — orders, inventory, Stripe</li>
                    <li class="text-ink-muted">CRM & ERP modules — coming soon</li>
                    <li><a href="mailto:{{ config('store.contact_email') }}" class="text-ink-muted transition hover:text-moss">{{ config('store.contact_email') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-ink/6 pt-8 text-xs text-ink-faint sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ config('store.legal_name') }}. All rights reserved.</p>
            <p>Built on Laravel · Stripe · Filament</p>
        </div>
    </div>
</footer>
