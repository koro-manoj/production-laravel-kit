<?php

declare(strict_types=1);

if (! function_exists('money_format_cents')) {
    function money_format_cents(int $cents, string $currency = 'USD'): string
    {
        $symbol = match (strtoupper($currency)) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            default => strtoupper($currency).' ',
        };

        return $symbol.number_format($cents / 100, 2);
    }
}
