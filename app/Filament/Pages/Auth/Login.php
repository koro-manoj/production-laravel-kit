<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Support\Enums\MaxWidth;

class Login extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public function getMaxWidth(): MaxWidth
    {
        return MaxWidth::Medium;
    }
}
