<?php

declare(strict_types=1);

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductShowController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Livewire\CartCheckout;
use App\Http\Livewire\CheckoutFlow;
use App\Http\Livewire\QuizFunnel;
use App\Http\Livewire\ShopCatalog;
use App\Http\Livewire\StoreCart;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', ShopCatalog::class)->name('shop.index');
Route::get('/shop/{product:slug}', ProductShowController::class)->name('shop.show');

Route::get('/cart', StoreCart::class)->name('cart.index');
Route::get('/checkout', CartCheckout::class)->name('cart.checkout');

Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Legacy single-product checkout (API compatibility)
Route::get('/checkout/{product:slug}', CheckoutFlow::class)->name('checkout.show');

// Product finder — SCOPE demo path /quiz/{slug}; /finder kept as alias
Route::get('/quiz/{quiz:slug}', QuizFunnel::class)->name('quiz.show');
Route::get('/finder/{quiz:slug}', QuizFunnel::class)->name('quiz.finder');

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');
