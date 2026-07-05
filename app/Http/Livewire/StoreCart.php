<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Commerce\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class StoreCart extends Component
{
    #[On('cart-updated')]
    public function refreshCart(): void
    {
        // Re-render
    }

    public function updateQuantity(int $productId, int $quantity, CartService $cart): void
    {
        $cart->updateQuantity($productId, $quantity);
        $this->dispatch('cart-updated', count: $cart->count());
    }

    public function remove(int $productId, CartService $cart): void
    {
        $cart->remove($productId);
        $this->dispatch('cart-updated', count: $cart->count());
    }

    public function render(CartService $cart): View
    {
        return view('livewire.store-cart', [
            'lines' => $cart->lines(),
            'subtotalCents' => $cart->subtotalCents(),
            'freeShipping' => $cart->qualifiesForFreeShipping(),
            'thresholdCents' => (int) config('store.free_shipping_threshold_cents'),
        ])->layout('layouts.store');
    }
}
