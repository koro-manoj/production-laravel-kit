<?php

declare(strict_types=1);

namespace App\Domain\Commerce\Services;

use App\Domain\Payments\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'northline.cart';

    /** @return array<int, int> product_id => quantity */
    public function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return (int) array_sum($this->items());
    }

    public function subtotalCents(): int
    {
        return $this->lines()->sum(fn (array $line): int => $line['product']->price_cents * $line['quantity']);
    }

    /** @return Collection<int, array{product: Product, quantity: int}> */
    public function lines(): Collection
    {
        $ids = array_keys($this->items());

        if ($ids === []) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', $ids)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        return collect($this->items())
            ->map(function (int $quantity, int $productId) use ($products): ?array {
                $product = $products->get($productId);

                if ($product === null) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->items();
        $cart[$product->id] = ($cart[$product->id] ?? 0) + max(1, $quantity);
        session([self::SESSION_KEY => $cart]);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->items();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function qualifiesForFreeShipping(): bool
    {
        return $this->subtotalCents() >= (int) config('store.free_shipping_threshold_cents');
    }
}
