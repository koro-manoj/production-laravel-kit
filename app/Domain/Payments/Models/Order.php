<?php

declare(strict_types=1);

namespace App\Domain\Payments\Models;

use App\Domain\Commerce\Models\OrderItem;
use App\Domain\Quiz\Models\QuizSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'quiz_session_id',
        'product_id',
        'amount_cents',
        'currency',
        'status',
        'customer_email',
        'customer_name',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function quizSession(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function itemsSummary(): string
    {
        $this->loadMissing('items', 'product');

        if ($this->items->isNotEmpty()) {
            return $this->items
                ->map(fn (OrderItem $item): string => $item->quantity > 1
                    ? "{$item->product_name} × {$item->quantity}"
                    : $item->product_name)
                ->join(', ');
        }

        return $this->product?->name ?? '—';
    }

    public function formattedAmount(): string
    {
        return money_format_cents($this->amount_cents, $this->currency);
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }
}
