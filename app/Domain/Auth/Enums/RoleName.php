<?php

declare(strict_types=1);

namespace App\Domain\Auth\Enums;

enum RoleName: string
{
    case Admin = 'Admin';
    case Clinic = 'Clinic';
    case Pharmacy = 'Pharmacy';
    case Patient = 'Patient';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
