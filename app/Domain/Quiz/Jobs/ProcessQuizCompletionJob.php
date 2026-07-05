<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Jobs;

use App\Domain\Quiz\Models\QuizSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessQuizCompletionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $sessionId,
    ) {}

    public function handle(): void
    {
        $session = QuizSession::query()
            ->with(['quiz', 'responses.option', 'user'])
            ->find($this->sessionId);

        if ($session === null) {
            return;
        }

        $metadata = $session->metadata ?? [];
        $metadata['processed_at'] = now()->toIso8601String();
        $metadata['response_count'] = $session->responses->count();
        $metadata['outcome_summary'] = $session->outcomeQuestion?->outcome_summary;
        $metadata['recommended_product_cents'] = $session->outcomeQuestion?->recommended_product_cents;

        if ($session->outcomeQuestion?->recommended_product_cents) {
            $product = \App\Domain\Payments\Models\Product::query()
                ->where('is_active', true)
                ->where('price_cents', $session->outcomeQuestion->recommended_product_cents)
                ->first();

            if ($product) {
                $metadata['recommended_product_slug'] = $product->slug;
                $metadata['recommended_product_name'] = $product->name;
            }
        }

        $session->update([
            'metadata' => $metadata,
            'completed_at' => $session->completed_at ?? now(),
        ]);
    }
}
