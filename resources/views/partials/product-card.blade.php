@php
    $thumbClass = match ($product->category) {
        'desk' => 'product-thumb--desk',
        'outdoor' => 'product-thumb--outdoor',
        default => 'product-thumb--home',
    };
    $hasSale = $product->compare_at_price_cents && $product->compare_at_price_cents > $product->price_cents;
    $imageUrl = $product->primaryImageUrl();
@endphp

<article class="product-card group">
    <div class="relative">
        <a href="{{ route('shop.show', $product) }}" class="block">
            <div @class(['product-thumb', $thumbClass => ! $imageUrl])>
                @if($product->badge)
                    <span class="product-badge">{{ $product->badge }}</span>
                @endif
                @if($hasSale)
                    <span class="product-badge product-badge--sale">Sale</span>
                @endif

                @if($imageUrl)
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $product->name }}"
                        class="product-thumb-img"
                        loading="lazy"
                        onerror="this.closest('.product-thumb')?.classList.add('{{ $thumbClass }}'); this.remove();"
                    >
                @else
                    <span class="product-thumb-letter">{{ mb_substr($product->name, 0, 1) }}</span>
                @endif

                <div class="product-thumb-overlay">
                    <span class="product-thumb-cta">View product</span>
                </div>
            </div>
        </a>
    </div>

    <div class="p-4">
        <div class="flex items-center justify-between gap-2">
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-ink-faint">
                {{ config('store.categories')[$product->category] ?? $product->category }}
            </p>
            <div class="flex items-center gap-1 text-[11px] font-medium text-ink-muted" aria-label="4.8 out of 5 stars">
                @for($i = 0; $i < 5; $i++)
                    <svg @class(['h-3 w-3', 'text-clay' => $i < 4, 'text-ink/15' => $i >= 4]) fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
                <span class="ml-0.5">4.8</span>
            </div>
        </div>

        <h3 class="mt-2 font-display text-lg font-semibold leading-snug text-ink">
            <a href="{{ route('shop.show', $product) }}" class="transition hover:text-moss">{{ $product->name }}</a>
        </h3>

        <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-ink-muted">{{ $product->description }}</p>

        <div class="mt-3 flex items-end justify-between gap-3">
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="font-display text-xl font-semibold text-ink">{{ money_format_cents($product->price_cents) }}</span>
                    @if($hasSale)
                        <span class="text-sm text-ink-faint line-through">{{ money_format_cents($product->compare_at_price_cents) }}</span>
                    @endif
                </div>
                @if($hasSale)
                    <p class="mt-0.5 text-xs font-semibold text-clay">
                        Save {{ money_format_cents($product->compare_at_price_cents - $product->price_cents) }}
                    </p>
                @endif
            </div>
            <a href="{{ route('shop.show', $product) }}" class="product-quick-add" title="Add to bag">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </a>
        </div>
    </div>
</article>
