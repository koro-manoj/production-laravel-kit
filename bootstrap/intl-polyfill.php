<?php

declare(strict_types=1);

if (extension_loaded('intl')) {
    return;
}

spl_autoload_register(
    static function (string $class): bool {
        if ($class !== Illuminate\Support\Number::class) {
            return false;
        }

        require __DIR__.'/../app/Support/Polyfill/Number.php';

        return true;
    },
    prepend: true,
);
