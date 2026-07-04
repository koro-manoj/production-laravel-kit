<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles([RoleName::Admin->value]);

        $clinic = User::query()->updateOrCreate(
            ['email' => 'clinic@example.com'],
            [
                'name' => 'River Clinic',
                'password' => Hash::make('password'),
            ]
        );
        $clinic->syncRoles([RoleName::Clinic->value]);

        $pharmacy = User::query()->updateOrCreate(
            ['email' => 'pharmacy@example.com'],
            [
                'name' => 'North Pharmacy',
                'password' => Hash::make('password'),
            ]
        );
        $pharmacy->syncRoles([RoleName::Pharmacy->value]);

        $patient = User::query()->updateOrCreate(
            ['email' => 'patient@example.com'],
            [
                'name' => 'Alex Patient',
                'password' => Hash::make('password'),
            ]
        );
        $patient->syncRoles([RoleName::Patient->value]);
    }
}
