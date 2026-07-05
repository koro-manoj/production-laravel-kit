<?php

declare(strict_types=1);

namespace Illuminate\Support;

use Illuminate\Support\Traits\Macroable;

/**
 * Drop-in replacement for Illuminate\Support\Number when ext-intl is unavailable.
 * Homebrew PHP 8.4 is built without intl; Filament tables and pagination need format().
 */
class Number
{
    use Macroable;

    protected static string $locale = 'en';

    protected static string $currency = 'USD';

    public static function format(int|float $number, ?int $precision = null, ?int $maxPrecision = null, ?string $locale = null): string
    {
        $decimals = $maxPrecision ?? $precision ?? 0;

        return number_format($number, $decimals, '.', ',');
    }

    public static function spell(int|float $number, ?string $locale = null, ?int $after = null, ?int $until = null): string
    {
        return (string) $number;
    }

    public static function ordinal(int|float $number, ?string $locale = null): string
    {
        return (string) $number;
    }

    public static function spellOrdinal(int|float $number, ?string $locale = null): string
    {
        return (string) $number;
    }

    public static function percentage(int|float $number, int $precision = 0, ?int $maxPrecision = null, ?string $locale = null): string
    {
        $decimals = $maxPrecision ?? $precision;

        return number_format($number, $decimals, '.', ',').'%';
    }

    public static function currency(int|float $number, string $in = '', ?string $locale = null, ?int $precision = null): string
    {
        $currency = $in !== '' ? $in : static::$currency;
        $decimals = $precision ?? 2;

        $symbol = match (strtoupper($currency)) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            default => strtoupper($currency).' ',
        };

        return $symbol.number_format($number, $decimals, '.', ',');
    }

    public static function fileSize(int|float $bytes, int $precision = 0, ?int $maxPrecision = null): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        for ($i = 0; ($bytes / 1024) > 0.9 && ($i < count($units) - 1); $i++) {
            $bytes /= 1024;
        }

        return sprintf('%s %s', static::format($bytes, $precision, $maxPrecision), $units[$i]);
    }

    public static function abbreviate(int|float $number, int $precision = 0, ?int $maxPrecision = null): string
    {
        return static::forHumans($number, $precision, $maxPrecision, abbreviate: true);
    }

    public static function forHumans(int|float $number, int $precision = 0, ?int $maxPrecision = null, bool $abbreviate = false): string
    {
        return static::summarize($number, $precision, $maxPrecision, $abbreviate ? [
            3 => 'K',
            6 => 'M',
            9 => 'B',
            12 => 'T',
            15 => 'Q',
        ] : [
            3 => ' thousand',
            6 => ' million',
            9 => ' billion',
            12 => ' trillion',
            15 => ' quadrillion',
        ]);
    }

    protected static function summarize(int|float $number, int $precision = 0, ?int $maxPrecision = null, array $units = []): string
    {
        if ($units === []) {
            $units = [
                3 => 'K',
                6 => 'M',
                9 => 'B',
                12 => 'T',
                15 => 'Q',
            ];
        }

        if (floatval($number) === 0.0) {
            return $precision > 0 ? static::format(0, $precision, $maxPrecision) : '0';
        }

        if ($number < 0) {
            return sprintf('-%s', static::summarize(abs($number), $precision, $maxPrecision, $units));
        }

        if ($number >= 1e15) {
            return sprintf('%s'.end($units), static::summarize($number / 1e15, $precision, $maxPrecision, $units));
        }

        $numberExponent = floor(log10($number));
        $displayExponent = $numberExponent - ($numberExponent % 3);
        $number /= pow(10, $displayExponent);

        return trim(sprintf('%s%s', static::format($number, $precision, $maxPrecision), $units[$displayExponent] ?? ''));
    }

    public static function clamp(int|float $number, int|float $min, int|float $max): int|float
    {
        return min(max($number, $min), $max);
    }

    public static function pairs(int|float $to, int|float $by, int|float $offset = 1): array
    {
        $output = [];

        for ($lower = 0; $lower < $to; $lower += $by) {
            $upper = $lower + $by;

            if ($upper > $to) {
                $upper = $to;
            }

            $output[] = [$lower + $offset, $upper];
        }

        return $output;
    }

    public static function trim(int|float $number): int|float
    {
        return json_decode(json_encode($number));
    }

    public static function withLocale(string $locale, callable $callback): mixed
    {
        $previousLocale = static::$locale;

        static::useLocale($locale);

        return tap($callback(), fn () => static::useLocale($previousLocale));
    }

    public static function withCurrency(string $currency, callable $callback): mixed
    {
        $previousCurrency = static::$currency;

        static::useCurrency($currency);

        return tap($callback(), fn () => static::useCurrency($previousCurrency));
    }

    public static function useLocale(string $locale): void
    {
        static::$locale = $locale;
    }

    public static function useCurrency(string $currency): void
    {
        static::$currency = $currency;
    }

    public static function defaultLocale(): string
    {
        return static::$locale;
    }

    public static function defaultCurrency(): string
    {
        return static::$currency;
    }
}
