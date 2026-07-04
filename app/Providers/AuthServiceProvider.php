<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Auth\Enums\RoleName;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            if ($user->hasRole(RoleName::Admin->value)) {
                return true;
            }

            return null;
        });
    }
}
