<?php

declare(strict_types=1);

namespace App\Domain\Payments\Jobs;

use App\Domain\Payments\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderReceiptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int $orderId,
    ) {}

    public function handle(): void
    {
        $order = Order::query()->with(['product', 'latestPayment'])->find($this->orderId);

        if ($order === null || $order->customer_email === null) {
            return;
        }

        Log::info('order.receipt.queued', [
            'order_reference' => $order->reference,
            'email' => $order->customer_email,
            'amount_cents' => $order->amount_cents,
        ]);

        // Mail delivery is intentionally logged in this showcase; wire Mail::send in production.
    }
}
