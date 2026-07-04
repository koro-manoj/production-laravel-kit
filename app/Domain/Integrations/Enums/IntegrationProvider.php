<?php

declare(strict_types=1);

namespace App\Domain\Integrations\Enums;

enum IntegrationProvider: string
{
    case Stripe = 'stripe';
}
