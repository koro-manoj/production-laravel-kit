<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::get('/quizzes', [QuizController::class, 'index']);
        Route::post('/quizzes/{quiz:slug}/sessions', [QuizController::class, 'start']);
        Route::get('/quiz-sessions/{session}', [QuizController::class, 'show']);
        Route::post('/quiz-sessions/{session}/questions/{question}/answer', [QuizController::class, 'answer']);

        Route::get('/products', [CheckoutController::class, 'products']);
        Route::post('/checkout/{product:slug}', [CheckoutController::class, 'create']);
    });
});
