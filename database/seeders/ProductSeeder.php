<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Payments\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->updateOrCreate(
            ['slug' => 'consultation-package'],
            [
                'name' => 'Telehealth Consultation',
                'description' => '30-minute clinician review with care plan summary.',
                'price_cents' => 7900,
                'currency' => 'USD',
                'is_active' => true,
            ]
        );

        Product::query()->updateOrCreate(
            ['slug' => 'wellness-plan'],
            [
                'name' => 'Wellness Plan',
                'description' => 'Personalized supplement and lifestyle recommendations.',
                'price_cents' => 4900,
                'currency' => 'USD',
                'is_active' => true,
            ]
        );

        Product::query()->updateOrCreate(
            ['slug' => 'urgent-care-escalation'],
            [
                'name' => 'Urgent Care Escalation',
                'description' => 'Priority routing to an on-call clinician within 2 hours.',
                'price_cents' => 12900,
                'currency' => 'USD',
                'is_active' => true,
            ]
        );
    }
}
