<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Jobs\SendOrderReceiptJob;
use App\Domain\Payments\Services\StripeCheckoutService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function success(Order $order, StripeCheckoutService $checkout, Request $request): View
    {
        $sessionId = $request->query('session_id');

        if (is_string($sessionId)) {
            $payment = $order->latestPayment;

            if ($payment !== null && $payment->stripe_checkout_session_id === $sessionId) {
                try {
                    $session = $checkout->retrieveSession($sessionId);

                    if ($session->payment_status === 'paid') {
                        $order->update(['status' => 'paid']);
                        SendOrderReceiptJob::dispatch($order->id);
                    }
                } catch (\Throwable $exception) {
                    report($exception);
                }
            }
        }

        return view('checkout.success', compact('order'));
    }

    public function cancel(Order $order): View
    {
        return view('checkout.cancel', compact('order'));
    }
}
