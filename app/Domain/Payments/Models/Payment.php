<?php

declare(strict_types=1);

namespace App\Domain\Payments\Models;

use App\Domain\Payments\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',
        'status',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'amount_cents',
        'currency',
        'provider_payload',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'provider_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
