<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Auth\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleName::values() as $role) {
            Role::findOrCreate($role, 'web');
            Role::findOrCreate($role, 'sanctum');
        }
    }
}
