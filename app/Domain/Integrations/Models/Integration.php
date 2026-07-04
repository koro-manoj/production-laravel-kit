<?php

declare(strict_types=1);

namespace App\Domain\Integrations\Models;

use App\Domain\Integrations\Enums\IntegrationProvider;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $fillable = [
        'provider',
        'label',
        'credentials',
        'is_active',
        'is_sandbox',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'provider' => IntegrationProvider::class,
            'credentials' => 'encrypted:array',
            'is_active' => 'boolean',
            'is_sandbox' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
