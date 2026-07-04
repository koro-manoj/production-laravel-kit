<?php

declare(strict_types=1);

namespace App\Domain\Payments\Jobs;

use App\Domain\Payments\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderReceiptMail;
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

        Mail::to($order->customer_email)->send(new OrderReceiptMail($order));
    }
}
