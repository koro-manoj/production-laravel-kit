<?php

declare(strict_types=1);

namespace App\Domain\Commerce\Support;

use Database\Seeders\ProductSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CatalogBootstrap
{
    public static function seedIfEmpty(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        if (! Schema::hasTable('products')) {
            return;
        }

        if (\App\Domain\Payments\Models\Product::query()->where('is_active', true)->exists()) {
            return;
        }

        Artisan::call('db:seed', [
            '--class' => ProductSeeder::class,
            '--force' => true,
        ]);
    }
}
