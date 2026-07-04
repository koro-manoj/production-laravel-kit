<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Integrations\Enums\IntegrationProvider;
use App\Domain\Integrations\Services\IntegrationCredentialService;
use App\Domain\Payments\Services\PaymentWebhookProcessor;
use App\Domain\Payments\Jobs\SendOrderReceiptJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        IntegrationCredentialService $credentials,
        PaymentWebhookProcessor $processor,
    ): Response {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = $credentials->webhookSecret(IntegrationProvider::Stripe);

        try {
            $event = $secret !== null && is_string($signature)
                ? Webhook::constructEvent($payload, $signature, $secret)
                : Event::constructFrom(json_decode($payload, true) ?? []);
        } catch (SignatureVerificationException|\UnexpectedValueException $exception) {
            report($exception);

            return response('Invalid payload', 400);
        }

        $record = $processor->process($event);

        if ($event->type === 'checkout.session.completed') {
            $orderId = $record->payload['data']['object']['metadata']['order_id'] ?? null;

            if (is_numeric($orderId)) {
                SendOrderReceiptJob::dispatch((int) $orderId);
            }
        }

        return response('ok', 200);
    }
}
