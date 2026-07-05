<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;

    public int $quantity = 1;

    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function addToCart(CartService $cart): void
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cart->add($this->product, $this->quantity);
        $this->added = true;
        $this->dispatch('cart-updated', count: $cart->count());
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < 10) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function render(): View
    {
        return view('livewire.add-to-cart', [
            'lineTotalCents' => $this->product->price_cents * $this->quantity,
        ]);
    }
}
