<?php

declare(strict_types=1);

namespace App\Domain\Payments\Services;

use App\Domain\Integrations\Enums\IntegrationProvider;
use App\Domain\Integrations\Services\IntegrationCredentialService;
use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Models\Product;
use App\Domain\Quiz\Models\QuizSession;
use App\Models\User;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function __construct(
        private readonly IntegrationCredentialService $credentials,
    ) {}

    public function createOrderFromProduct(
        Product $product,
        ?User $user = null,
        ?QuizSession $session = null,
        ?string $customerEmail = null,
        ?string $customerName = null,
    ): Order {
        return Order::query()->create([
            'reference' => 'ORD-'.Str::upper(Str::random(10)),
            'user_id' => $user?->id,
            'quiz_session_id' => $session?->id,
            'product_id' => $product->id,
            'amount_cents' => $product->price_cents,
            'currency' => $product->currency,
            'status' => 'pending',
            'customer_email' => $customerEmail ?? $user?->email,
            'customer_name' => $customerName ?? $user?->name,
            'metadata' => [
                'product_slug' => $product->slug,
            ],
        ]);
    }

    public function startCheckout(Order $order, string $successUrl, string $cancelUrl): Payment
    {
        $stripe = new StripeClient($this->credentials->secretKey(IntegrationProvider::Stripe));

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'customer_email' => $order->customer_email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => strtolower($order->currency),
                    'unit_amount' => $order->amount_cents,
                    'product_data' => [
                        'name' => $order->product->name,
                        'description' => $order->product->description,
                    ],
                ],
            ]],
            'metadata' => [
                'order_reference' => $order->reference,
                'order_id' => (string) $order->id,
            ],
        ]);

        return Payment::query()->create([
            'order_id' => $order->id,
            'provider' => IntegrationProvider::Stripe->value,
            'status' => PaymentStatus::Processing,
            'stripe_checkout_session_id' => $session->id,
            'amount_cents' => $order->amount_cents,
            'currency' => $order->currency,
            'provider_payload' => $session->toArray(),
        ]);
    }

    public function checkoutUrl(Payment $payment): ?string
    {
        $url = $payment->provider_payload['url'] ?? null;

        return is_string($url) ? $url : null;
    }

    public function retrieveSession(string $sessionId): StripeCheckoutSession
    {
        $stripe = new StripeClient($this->credentials->secretKey(IntegrationProvider::Stripe));

        return $stripe->checkout->sessions->retrieve($sessionId);
    }
}
