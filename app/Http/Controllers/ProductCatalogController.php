<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function __invoke(Request $request, CartService $cart): View
    {
        $category = $request->query('category');

        $products = Product::query()
            ->where('is_active', true)
            ->when(
                is_string($category) && $category !== '' && array_key_exists($category, config('store.categories')),
                fn ($query) => $query->where('category', $category)
            )
            ->orderBy('name')
            ->get();

        return view('store.catalog', [
            'products' => $products,
            'activeCategory' => $category,
            'categories' => config('store.categories'),
            'cartCount' => $cart->count(),
        ]);
    }
}
