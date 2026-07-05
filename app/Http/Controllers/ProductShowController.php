<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Models\Product;
use Illuminate\Contracts\View\View;

class ProductShowController extends Controller
{
    public function __invoke(Product $product, CartService $cart): View
    {
        abort_unless($product->is_active, 404);

        $related = Product::query()
            ->where('is_active', true)
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(3)
            ->get();

        return view('store.product', [
            'product' => $product,
            'related' => $related,
            'categories' => config('store.categories'),
            'cartCount' => $cart->count(),
        ]);
    }
}
