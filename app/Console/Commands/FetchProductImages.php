<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class FetchProductImages extends Command
{
    protected $signature = 'products:fetch-images';

    protected $description = 'Download product images into public/images/products';

    public function handle(): int
    {
        /** @var array<string, array{primary: string, gallery: list<string>}> $sources */
        $sources = require database_path('data/product-image-sources.php');

        foreach ($sources as $slug => $images) {
            $dir = public_path("images/products/{$slug}");
            File::ensureDirectoryExists($dir);

            $this->download($slug, 'primary.jpg', $images['primary']);

            foreach ($images['gallery'] as $index => $url) {
                $this->download($slug, 'gallery-'.($index + 1).'.jpg', $url);
            }
        }

        $this->info('Product images downloaded.');

        return self::SUCCESS;
    }

    private function download(string $slug, string $filename, string $source): void
    {
        $path = public_path("images/products/{$slug}/{$filename}");

        $url = str_starts_with($source, 'http')
            ? $source
            : "https://images.unsplash.com/photo-{$source}?auto=format&fit=crop&w=900&q=85";

        $response = Http::timeout(45)
            ->withHeaders(['User-Agent' => 'NorthlineCatalog/1.0'])
            ->get($url);

        if (! $response->successful()) {
            $this->warn("Failed {$slug}/{$filename} ({$response->status()})");

            return;
        }

        File::put($path, $response->body());
        $this->line("Saved {$slug}/{$filename}");
    }
}
