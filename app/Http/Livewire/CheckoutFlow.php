<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Payments\Models\Product;
use App\Domain\Payments\Services\StripeCheckoutService;
use App\Domain\Quiz\Models\QuizSession;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutFlow extends Component
{
    public Product $product;

    public ?QuizSession $quizSession = null;

    public string $customerName = '';

    public string $customerEmail = '';

    public ?string $checkoutUrl = null;

    public ?string $errorMessage = null;

    public function mount(Product $product): void
    {
        abort_unless($product->is_active, 404);

        $this->product = $product;

        $sessionId = request()->query('session');

        if (is_string($sessionId) && $sessionId !== '') {
            $this->quizSession = QuizSession::query()->find($sessionId);
        }

        $user = Auth::user();

        if ($user !== null) {
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
    }

    public function startCheckout(StripeCheckoutService $checkout): void
    {
        $this->validate([
            'customerName' => ['required', 'string', 'max:255'],
            'customerEmail' => ['required', 'email', 'max:255'],
        ]);

        $this->errorMessage = null;

        try {
            $order = $checkout->createOrderFromProduct(
                $this->product,
                Auth::user(),
                $this->quizSession,
                $this->customerEmail,
                $this->customerName,
            );

            $payment = $checkout->startCheckout(
                $order,
                route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
                route('checkout.cancel', $order),
            );

            $this->checkoutUrl = $checkout->checkoutUrl($payment);

            if ($this->checkoutUrl === null) {
                $this->errorMessage = 'Unable to start checkout. Verify Stripe integration credentials in admin.';

                return;
            }

            $this->redirect($this->checkoutUrl);
        } catch (\Throwable $exception) {
            report($exception);
            $this->errorMessage = 'Payment provider is not configured. Add Stripe sandbox keys under Admin → Integrations.';
        }
    }

    public function render(): View
    {
        return view('livewire.checkout-flow');
    }
}
