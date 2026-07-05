<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Commerce\Services\CartService;
use App\Domain\Payments\Services\StripeCheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartCheckout extends Component
{
    public string $customerName = '';

    public string $customerEmail = '';

    public ?string $errorMessage = null;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user !== null) {
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function checkout(CartService $cart, StripeCheckoutService $stripe): void
    {
        if ($cart->lines()->isEmpty()) {
            $this->errorMessage = 'Your bag is empty.';

            return;
        }

        $this->validate([
            'customerName' => ['required', 'string', 'max:255'],
            'customerEmail' => ['required', 'email', 'max:255'],
        ]);

        $this->errorMessage = null;

        try {
            $order = $stripe->createOrderFromCart(
                $cart->lines(),
                Auth::user(),
                $this->customerEmail,
                $this->customerName,
            );

            $payment = $stripe->startCheckout(
                $order,
                route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
                route('checkout.cancel', $order),
            );

            $url = $stripe->checkoutUrl($payment);

            if ($url === null) {
                $this->errorMessage = 'Unable to start checkout. Verify Stripe credentials in admin.';

                return;
            }

            $cart->clear();
            $this->redirect($url);
        } catch (\Throwable $exception) {
            report($exception);
            $this->errorMessage = 'Payment provider is not configured. Add Stripe sandbox keys under Admin → Integrations.';
        }
    }

    public function render(CartService $cart): View
    {
        return view('livewire.cart-checkout', [
            'lines' => $cart->lines(),
            'subtotalCents' => $cart->subtotalCents(),
            'freeShipping' => $cart->qualifiesForFreeShipping(),
        ])->layout('layouts.store');
    }
}
