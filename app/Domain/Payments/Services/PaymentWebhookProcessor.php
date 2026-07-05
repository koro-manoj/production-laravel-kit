<?php

declare(strict_types=1);

namespace App\Domain\Payments\Services;

use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Models\PaymentWebhookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Event;

class PaymentWebhookProcessor
{
    public function process(Event $event): PaymentWebhookEvent
    {
        return DB::transaction(function () use ($event): PaymentWebhookEvent {
            $record = PaymentWebhookEvent::query()->firstOrCreate(
                ['event_id' => $event->id],
                [
                    'provider' => 'stripe',
                    'event_type' => $event->type,
                    'payload' => $event->toArray(),
                ]
            );

            if ($record->processed_at !== null) {
                return $record;
            }

            match ($event->type) {
                'checkout.session.completed' => $this->handleCheckoutCompleted($event),
                'payment_intent.payment_failed' => $this->handlePaymentFailed($event),
                default => null,
            };

            $record->update(['processed_at' => now()]);

            return $record;
        });
    }

    private function handleCheckoutCompleted(Event $event): void
    {
        /** @var array<string, mixed> $session */
        $session = $event->data->object->toArray();
        $sessionId = $session['id'] ?? null;

        if (! is_string($sessionId)) {
            Log::warning('payments.webhook.checkout_completed_missing_session_id');

            return;
        }

        $payment = Payment::query()
            ->where('stripe_checkout_session_id', $sessionId)
            ->first();

        if ($payment === null) {
            Log::warning('payments.webhook.payment_not_found', ['session_id' => $sessionId]);

            return;
        }

        $payment->update([
            'status' => PaymentStatus::Succeeded,
            'stripe_payment_intent_id' => is_string($session['payment_intent'] ?? null) ? $session['payment_intent'] : null,
            'paid_at' => now(),
            'provider_payload' => array_merge($payment->provider_payload ?? [], ['webhook' => $session]),
        ]);

        $payment->order->update(['status' => 'paid']);
    }

    private function handlePaymentFailed(Event $event): void
    {
        /** @var array<string, mixed> $intent */
        $intent = $event->data->object->toArray();
        $intentId = $intent['id'] ?? null;

        if (! is_string($intentId)) {
            return;
        }

        $payment = Payment::query()
            ->where('stripe_payment_intent_id', $intentId)
            ->first();

        if ($payment === null) {
            return;
        }

        $payment->update([
            'status' => PaymentStatus::Failed,
            'provider_payload' => array_merge($payment->provider_payload ?? [], ['failure' => $intent]),
        ]);

        $payment->order->update(['status' => 'failed']);
    }
}
