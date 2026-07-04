<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Integrations\Services\IntegrationCredentialService;
use App\Domain\Payments\Services\PaymentWebhookProcessor;
use App\Domain\Payments\Services\StripeCheckoutService;
use App\Domain\Quiz\Services\QuizFunnelService;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IntegrationCredentialService::class);
        $this->app->singleton(QuizFunnelService::class);
        $this->app->singleton(StripeCheckoutService::class);
        $this->app->singleton(PaymentWebhookProcessor::class);
    }
}
