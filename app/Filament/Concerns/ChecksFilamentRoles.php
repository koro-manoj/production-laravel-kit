<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Domain\Auth\Enums\RoleName;

trait ChecksFilamentRoles
{
    /** @param  list<RoleName>  $roles */
    protected static function userHasAnyRole(array $roles): bool
    {
        $user = auth()->user();

        if ($user === null) {
            return false;
        }

        return $user->hasAnyRole(array_map(
            static fn (RoleName $role): string => $role->value,
            $roles,
        ));
    }
}
