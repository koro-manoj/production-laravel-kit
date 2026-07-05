<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Payments\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use RuntimeException;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/northline-catalog.json');

        if (! File::exists($path)) {
            throw new RuntimeException("Catalog file not found: {$path}");
        }

        $catalog = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

        foreach ($catalog as $row) {
            $slug = $row['slug'];
            $imagePaths = $this->localImagePaths($slug);

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'image_url' => $imagePaths['primary'] ?? $row['image_url'] ?? null,
                    'gallery' => $imagePaths['gallery'] !== [] ? $imagePaths['gallery'] : ($row['gallery'] ?? []),
                    'category' => $row['category'],
                    'badge' => $row['badge'] ?? null,
                    'price_cents' => (int) $row['price_cents'],
                    'compare_at_price_cents' => isset($row['compare_at_price_cents'])
                        ? (int) $row['compare_at_price_cents']
                        : null,
                    'currency' => 'USD',
                    'is_active' => true,
                ]
            );
        }

        Product::query()->updateOrCreate(
            ['slug' => 'consultation-package'],
            [
                'name' => 'Consultation Package',
                'description' => 'One-on-one strategy session with follow-up notes and action plan.',
                'price_cents' => 9900,
                'currency' => 'USD',
                'category' => 'services',
                'is_active' => true,
            ]
        );

        Product::query()
            ->whereIn('slug', ['wellness-plan', 'urgent-care-escalation'])
            ->update(['is_active' => false]);
    }

    /** @return array{primary: ?string, gallery: list<string>} */
    private function localImagePaths(string $slug): array
    {
        $primary = "/images/products/{$slug}/primary.jpg";
        $gallery = [];

        for ($i = 1; $i <= 3; $i++) {
            $gallery[] = "/images/products/{$slug}/gallery-{$i}.jpg";
        }

        $gallery = array_values(array_filter(
            $gallery,
            fn (string $path): bool => file_exists(public_path(ltrim($path, '/')))
        ));

        return [
            'primary' => file_exists(public_path(ltrim($primary, '/'))) ? $primary : null,
            'gallery' => $gallery,
        ];
    }
}
