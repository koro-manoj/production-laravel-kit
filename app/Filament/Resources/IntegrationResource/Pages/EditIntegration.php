<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntegrationResource\Pages;

use App\Domain\Integrations\Enums\IntegrationProvider;
use App\Domain\Integrations\Services\IntegrationCredentialService;
use App\Filament\Resources\IntegrationResource;
use Filament\Resources\Pages\EditRecord;

class EditIntegration extends EditRecord
{
    protected static string $resource = IntegrationResource::class;

    protected function afterSave(): void
    {
        $provider = $this->record->provider;

        if ($provider instanceof IntegrationProvider) {
            app(IntegrationCredentialService::class);
            \Illuminate\Support\Facades\Cache::forget("integration.active.{$provider->value}");
        }
    }
}
