<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Payments\Models\Product;
use App\Domain\Payments\Services\StripeCheckoutService;
use App\Domain\Quiz\Models\QuizSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function products(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'slug', 'name', 'description', 'price_cents', 'currency']);

        return response()->json(['data' => $products]);
    }

    public function create(
        Product $product,
        Request $request,
        StripeCheckoutService $checkout,
    ): JsonResponse {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quiz_session_id' => ['nullable', 'uuid', 'exists:quiz_sessions,id'],
            'customer_email' => ['nullable', 'email'],
            'customer_name' => ['nullable', 'string', 'max:255'],
        ]);

        $session = isset($validated['quiz_session_id'])
            ? QuizSession::query()->find($validated['quiz_session_id'])
            : null;

        try {
            $order = $checkout->createOrderFromProduct(
                $product,
                $request->user(),
                $session,
                $validated['customer_email'] ?? $request->user()?->email,
                $validated['customer_name'] ?? $request->user()?->name,
            );

            $payment = $checkout->startCheckout(
                $order,
                url('/checkout/success/'.$order->reference),
                url('/checkout/cancel/'.$order->reference),
            );

            return response()->json([
                'order_reference' => $order->reference,
                'checkout_url' => $checkout->checkoutUrl($payment),
                'amount_cents' => $order->amount_cents,
                'currency' => $order->currency,
            ], 201);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Stripe integration is not configured. Add sandbox credentials in admin.',
            ], 503);
        }
    }
}
