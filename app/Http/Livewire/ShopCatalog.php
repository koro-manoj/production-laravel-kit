<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShopCatalog extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $sort = 'featured';

    #[Url]
    public string $price = '';

    #[Url]
    public bool $onSale = false;

    public function updatedSearch(): void
    {
        //
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'sort', 'price', 'onSale']);
        $this->sort = 'featured';
    }

    /** @return Collection<int, Product> */
    protected function filteredProducts(): Collection
    {
        $query = Product::query()->where('is_active', true);

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($builder) use ($term): void {
                $builder
                    ->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($this->category !== '' && array_key_exists($this->category, config('store.categories'))) {
            $query->where('category', $this->category);
        }

        if ($this->onSale) {
            $query->whereNotNull('compare_at_price_cents')
                ->whereColumn('compare_at_price_cents', '>', 'price_cents');
        }

        match ($this->price) {
            'under_50' => $query->where('price_cents', '<', 5000),
            '50_100' => $query->whereBetween('price_cents', [5000, 10000]),
            'over_100' => $query->where('price_cents', '>', 10000),
            default => null,
        };

        match ($this->sort) {
            'price_asc' => $query->orderBy('price_cents'),
            'price_desc' => $query->orderByDesc('price_cents'),
            'name' => $query->orderBy('name'),
            'newest' => $query->orderByDesc('created_at'),
            default => $query->orderByRaw('CASE WHEN badge IS NOT NULL THEN 0 ELSE 1 END')->orderBy('name'),
        };

        return $query->get();
    }

    /** @return array<string, int> */
    protected function categoryCounts(): array
    {
        $counts = Product::query()
            ->where('is_active', true)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();

        $result = [];
        foreach (config('store.categories') as $slug => $label) {
            $result[$slug] = (int) ($counts[$slug] ?? 0);
        }

        return $result;
    }

    public function render(CartService $cart): View
    {
        return view('livewire.shop-catalog', [
            'products' => $this->filteredProducts(),
            'categories' => config('store.categories'),
            'categoryCounts' => $this->categoryCounts(),
            'priceRanges' => config('store.filters.price_ranges'),
            'sortOptions' => config('store.filters.sort'),
            'activeFilterCount' => collect([
                $this->search !== '',
                $this->category !== '',
                $this->price !== '',
                $this->onSale,
                $this->sort !== 'featured',
            ])->filter()->count(),
        ])->layout('layouts.store');
    }
}
