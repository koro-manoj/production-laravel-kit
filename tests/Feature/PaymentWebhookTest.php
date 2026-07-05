<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Models\PaymentWebhookEvent;
use App\Domain\Payments\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_session_completed_marks_order_paid(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'consultation-package')->firstOrFail();
        $sessionId = 'cs_test_'.Str::random(12);

        $order = Order::query()->create([
            'reference' => 'ORD-'.Str::upper(Str::random(8)),
            'product_id' => $product->id,
            'amount_cents' => $product->price_cents,
            'currency' => 'USD',
            'status' => 'pending',
            'customer_email' => 'webhook@example.com',
            'customer_name' => 'Webhook Buyer',
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'stripe',
            'status' => PaymentStatus::Processing,
            'stripe_checkout_session_id' => $sessionId,
            'amount_cents' => $product->price_cents,
            'currency' => 'USD',
            'provider_payload' => ['id' => $sessionId],
        ]);

        $payload = [
            'id' => 'evt_test_webhook_1',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => $sessionId,
                    'payment_intent' => 'pi_test_123',
                    'payment_status' => 'paid',
                ],
            ],
        ];

        $this->postJson('/webhooks/stripe', $payload)->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => PaymentStatus::Succeeded->value,
        ]);
    }

    public function test_duplicate_webhook_event_is_idempotent(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'consultation-package')->firstOrFail();
        $sessionId = 'cs_test_'.Str::random(12);

        $order = Order::query()->create([
            'reference' => 'ORD-'.Str::upper(Str::random(8)),
            'product_id' => $product->id,
            'amount_cents' => $product->price_cents,
            'currency' => 'USD',
            'status' => 'pending',
            'customer_email' => 'dup@example.com',
            'customer_name' => 'Dup Buyer',
        ]);

        Payment::query()->create([
            'order_id' => $order->id,
            'provider' => 'stripe',
            'status' => PaymentStatus::Processing,
            'stripe_checkout_session_id' => $sessionId,
            'amount_cents' => $product->price_cents,
            'currency' => 'USD',
        ]);

        $payload = [
            'id' => 'evt_test_duplicate',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => $sessionId,
                    'payment_intent' => 'pi_test_dup',
                ],
            ],
        ];

        $this->postJson('/webhooks/stripe', $payload)->assertOk();
        $this->postJson('/webhooks/stripe', $payload)->assertOk();

        $this->assertSame(1, PaymentWebhookEvent::query()->where('event_id', 'evt_test_duplicate')->count());
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }
}
