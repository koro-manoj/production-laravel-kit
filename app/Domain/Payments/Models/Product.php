<?php

declare(strict_types=1);

namespace App\Domain\Payments\Models;

use App\Domain\Payments\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'image_url',
        'gallery',
        'category',
        'badge',
        'price_cents',
        'compare_at_price_cents',
        'currency',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'gallery' => 'array',
        ];
    }

    /** @return list<string> */
    public function images(): array
    {
        $images = [];

        if (is_string($this->image_url) && $this->image_url !== '') {
            $images[] = $this->resolveImageUrl($this->image_url);
        }

        foreach ($this->gallery ?? [] as $url) {
            if (is_string($url) && $url !== '') {
                $resolved = $this->resolveImageUrl($url);
                if (! in_array($resolved, $images, true)) {
                    $images[] = $resolved;
                }
            }
        }

        return $images;
    }

    public function primaryImageUrl(): ?string
    {
        return $this->images()[0] ?? null;
    }

    private function resolveImageUrl(string $url): string
    {
        if (str_starts_with($url, '/')) {
            return asset($url);
        }

        return $url;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
