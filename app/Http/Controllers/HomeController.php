<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(CartService $cart): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderByRaw('CASE WHEN badge IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('name')
            ->get();

        $categoryCounts = Product::query()
            ->where('is_active', true)
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('store.home', [
            'featured' => $products->firstWhere('badge', 'Bestseller') ?? $products->first(),
            'products' => $products->take(6),
            'categories' => config('store.categories'),
            'categoryCounts' => $categoryCounts,
            'cartCount' => $cart->count(),
            'stats' => [
                'products' => $products->count(),
                'orders' => Order::query()->where('status', 'paid')->count(),
                'categories' => count(config('store.categories')),
            ],
        ]);
    }
}
