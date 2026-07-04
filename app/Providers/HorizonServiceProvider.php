<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class HorizonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Horizon::auth(function ($request) {
            $user = $request->user();

            return $user !== null && $user->hasRole('Admin');
        });
    }
}
