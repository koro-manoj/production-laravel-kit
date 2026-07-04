<?php

declare(strict_types=1);

namespace App\Domain\Integrations\Services;

use App\Domain\Integrations\Enums\IntegrationProvider;
use App\Domain\Integrations\Models\Integration;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class IntegrationCredentialService
{
    private const CACHE_TTL = 300;

    public function get(IntegrationProvider $provider): ?Integration
    {
        return Cache::remember(
            $this->cacheKey($provider),
            self::CACHE_TTL,
            fn () => Integration::query()
                ->where('provider', $provider)
                ->where('is_active', true)
                ->first()
        );
    }

    /** @param array<string, mixed> $credentials */
    public function store(
        IntegrationProvider $provider,
        string $label,
        array $credentials,
        bool $isSandbox = true,
        bool $isActive = true,
    ): Integration {
        $integration = Integration::query()->updateOrCreate(
            ['provider' => $provider],
            [
                'label' => $label,
                'credentials' => $credentials,
                'is_sandbox' => $isSandbox,
                'is_active' => $isActive,
            ]
        );

        Cache::forget($this->cacheKey($provider));

        return $integration;
    }

    public function secretKey(IntegrationProvider $provider): string
    {
        $integration = $this->get($provider);

        if ($integration === null) {
            throw new RuntimeException("No active integration configured for {$provider->value}.");
        }

        $secret = $integration->credentials['secret_key'] ?? null;

        if (! is_string($secret) || $secret === '') {
            throw new RuntimeException("Stripe secret key missing in integration credentials.");
        }

        return $secret;
    }

    public function publishableKey(IntegrationProvider $provider): string
    {
        $integration = $this->get($provider);

        if ($integration === null) {
            throw new RuntimeException("No active integration configured for {$provider->value}.");
        }

        $key = $integration->credentials['publishable_key'] ?? null;

        if (! is_string($key) || $key === '') {
            throw new RuntimeException("Stripe publishable key missing in integration credentials.");
        }

        return $key;
    }

    public function webhookSecret(IntegrationProvider $provider): ?string
    {
        $integration = $this->get($provider);

        if ($integration === null) {
            return null;
        }

        $secret = $integration->credentials['webhook_secret'] ?? null;

        return is_string($secret) && $secret !== '' ? $secret : null;
    }

    private function cacheKey(IntegrationProvider $provider): string
    {
        return "integration.active.{$provider->value}";
    }
}
