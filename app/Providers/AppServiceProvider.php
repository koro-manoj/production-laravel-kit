<?php

namespace App\Providers;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Commerce\Support\CatalogBootstrap;
use App\Http\Livewire\AddToCart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CatalogBootstrap::seedIfEmpty();

        Livewire::component('add-to-cart', AddToCart::class);

        View::composer(['partials.store-nav', 'partials.store-footer'], function ($view): void {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
