<?php

declare(strict_types=1);

if (! function_exists('money_format_cents')) {
    function money_format_cents(int $cents, string $currency = 'USD'): string
    {
        return number_format($cents / 100, 2).' '.$currency;
    }
}
