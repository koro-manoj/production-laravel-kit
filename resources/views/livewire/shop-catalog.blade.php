<div class="mx-auto max-w-7xl px-6 py-10 lg:px-10 lg:py-14">
    {{-- Toolbar --}}
    <div class="flex flex-col gap-6 border-b border-ink/8 pb-8 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="section-label">Shop</p>
            <h1 class="mt-2 font-display text-4xl font-semibold text-ink">
                @if($category !== '' && isset($categories[$category]))
                    {{ $categories[$category] }}
                @else
                    All products
                @endif
            </h1>
            <p class="mt-2 text-sm text-ink-muted">
                {{ $products->count() }} {{ $products->count() === 1 ? 'item' : 'items' }}
                @if($activeFilterCount > 0)
                    · {{ $activeFilterCount }} {{ $activeFilterCount === 1 ? 'filter' : 'filters' }} active
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1 sm:flex-none sm:w-64">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-faint" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search products…"
                    class="input-field pl-10"
                >
            </div>
            <select wire:model.live="sort" class="input-field w-auto min-w-[180px] py-3">
                @foreach($sortOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-[240px_1fr]">
        {{-- Filter sidebar --}}
        <aside class="space-y-4">
            <div class="filter-panel lg:sticky lg:top-24">
                <div class="flex items-center justify-between border-b border-ink/6 px-5 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-ink">Filters</h2>
                    @if($activeFilterCount > 0)
                        <button type="button" wire:click="clearFilters" class="text-xs font-semibold text-clay hover:text-clay-dark">
                            Clear all
                        </button>
                    @endif
                </div>

                <div class="p-5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-faint">Collection</p>
                    <ul class="mt-3 space-y-1">
                        <li>
                            <label class="filter-option">
                                <input type="radio" wire:model.live="category" value="" class="filter-radio">
                                <span>All collections</span>
                                <span class="filter-count">{{ array_sum($categoryCounts) }}</span>
                            </label>
                        </li>
                        @foreach($categories as $slug => $label)
                            <li>
                                <label class="filter-option">
                                    <input type="radio" wire:model.live="category" value="{{ $slug }}" class="filter-radio">
                                    <span>{{ $label }}</span>
                                    <span class="filter-count">{{ $categoryCounts[$slug] ?? 0 }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border-t border-ink/6 p-5">
                    <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-faint">Price</p>
                    <ul class="mt-3 space-y-1">
                        @foreach($priceRanges as $value => $label)
                            <li>
                                <label class="filter-option">
                                    <input type="radio" wire:model.live="price" value="{{ $value }}" class="filter-radio">
                                    <span>{{ $label }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="border-t border-ink/6 p-5">
                    <label class="filter-option filter-option--toggle">
                        <span>On sale only</span>
                        <input type="checkbox" wire:model.live="onSale" class="filter-toggle">
                    </label>
                </div>
            </div>
        </aside>

        {{-- Product grid --}}
        <div>
            @if($activeFilterCount > 0)
                <div class="mb-6 flex flex-wrap gap-2">
                    @if($search !== '')
                        <button type="button" wire:click="$set('search', '')" class="filter-chip">
                            Search: "{{ $search }}" ×
                        </button>
                    @endif
                    @if($category !== '' && isset($categories[$category]))
                        <button type="button" wire:click="$set('category', '')" class="filter-chip">
                            {{ $categories[$category] }} ×
                        </button>
                    @endif
                    @if($price !== '' && isset($priceRanges[$price]))
                        <button type="button" wire:click="$set('price', '')" class="filter-chip">
                            {{ $priceRanges[$price] }} ×
                        </button>
                    @endif
                    @if($onSale)
                        <button type="button" wire:click="$set('onSale', false)" class="filter-chip">
                            On sale ×
                        </button>
                    @endif
                    @if($sort !== 'featured' && isset($sortOptions[$sort]))
                        <button type="button" wire:click="$set('sort', 'featured')" class="filter-chip">
                            Sort: {{ $sortOptions[$sort] }} ×
                        </button>
                    @endif
                </div>
            @endif

            <div wire:loading.class="opacity-50" wire:target="search,category,sort,price,onSale,clearFilters" class="transition-opacity">
                @if($products->isEmpty())
                    <div class="card px-8 py-16 text-center">
                        <p class="font-display text-xl font-semibold text-ink">No products match your filters.</p>
                        <button type="button" wire:click="clearFilters" class="btn-primary mt-6 inline-flex">Clear filters</button>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
