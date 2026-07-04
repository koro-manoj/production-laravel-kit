<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Integrations\Enums\IntegrationProvider;
use App\Domain\Integrations\Services\IntegrationCredentialService;
use Illuminate\Database\Seeder;

class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        app(IntegrationCredentialService::class)->store(
            IntegrationProvider::Stripe,
            'Stripe Sandbox',
            [
                'publishable_key' => 'pk_test_replace_me',
                'secret_key' => 'sk_test_replace_me',
                'webhook_secret' => 'whsec_replace_me',
            ],
            isSandbox: true,
            isActive: true,
        );
    }
}
