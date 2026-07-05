<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';

    public function getHeading(): string
    {
        return 'Welcome back';
    }

    public function getSubheading(): ?string
    {
        return 'Sign in to manage quizzes, orders, and integrations.';
    }
}
