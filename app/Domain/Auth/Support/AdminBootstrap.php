<?php

declare(strict_types=1);

namespace App\Domain\Auth\Support;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AdminBootstrap
{
    public static function seedIfMissing(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        if (User::query()->where('email', 'admin@example.com')->exists()) {
            return;
        }

        if (Schema::hasTable('roles') && ! \Spatie\Permission\Models\Role::query()->exists()) {
            Artisan::call('db:seed', [
                '--class' => RoleSeeder::class,
                '--force' => true,
            ]);
        }

        Artisan::call('db:seed', [
            '--class' => UserSeeder::class,
            '--force' => true,
        ]);
    }
}
