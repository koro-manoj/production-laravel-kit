<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Jobs;

use App\Domain\Quiz\Models\QuizSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

        Log::info('quiz.session.completed', [
            'session_id' => $session->id,
            'quiz_id' => $session->quiz_id,
            'outcome' => $session->outcome_label,
            'user_id' => $session->user_id,
            'response_count' => $session->responses->count(),
        ]);
    }
}
