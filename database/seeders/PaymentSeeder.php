<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        Order::query()
            ->where('status', 'paid')
            ->each(function (Order $order): void {
                Payment::query()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'provider' => 'stripe',
                        'status' => PaymentStatus::Succeeded,
                        'stripe_checkout_session_id' => 'cs_demo_'.$order->reference,
                        'stripe_payment_intent_id' => 'pi_demo_'.$order->reference,
                        'amount_cents' => $order->amount_cents,
                        'currency' => $order->currency,
                        'paid_at' => $order->created_at,
                        'provider_payload' => ['mode' => 'sandbox'],
                    ]
                );
            });
    }
}
