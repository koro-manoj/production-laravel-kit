<?php

declare(strict_types=1);

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Livewire\CheckoutFlow;
use App\Http\Livewire\QuizFunnel;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::get('/quiz/{quiz:slug}', QuizFunnel::class)->name('quiz.show');

Route::get('/checkout/{product:slug}', CheckoutFlow::class)->name('checkout.show');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');
