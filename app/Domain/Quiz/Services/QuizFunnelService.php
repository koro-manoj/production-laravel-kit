<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Services;

use App\Domain\Quiz\Enums\QuizSessionStatus;
use App\Domain\Quiz\Models\Quiz;
use App\Domain\Quiz\Models\QuizOption;
use App\Domain\Quiz\Models\QuizQuestion;
use App\Domain\Quiz\Models\QuizResponse;
use App\Domain\Quiz\Models\QuizSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class QuizFunnelService
{
    public function startSession(Quiz $quiz, ?User $user = null): QuizSession
    {
        if (! $quiz->is_active || $quiz->entry_question_id === null) {
            throw new InvalidArgumentException('Quiz is not available for new sessions.');
        }

        return QuizSession::query()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user?->id,
            'status' => QuizSessionStatus::InProgress,
            'current_question_id' => $quiz->entry_question_id,
        ]);
    }

    public function submitAnswer(QuizSession $session, QuizQuestion $question, QuizOption $option): QuizSession
    {
        if ($session->status !== QuizSessionStatus::InProgress) {
            throw new InvalidArgumentException('Session is no longer active.');
        }

        if ($session->current_question_id !== $question->id) {
            throw new InvalidArgumentException('Submitted question does not match the active step.');
        }

        if ($option->question_id !== $question->id) {
            throw new InvalidArgumentException('Option does not belong to the current question.');
        }

        return DB::transaction(function () use ($session, $question, $option): QuizSession {
            QuizResponse::query()->updateOrCreate(
                [
                    'quiz_session_id' => $session->id,
                    'question_id' => $question->id,
                ],
                [
                    'option_id' => $option->id,
                ]
            );

            if ($option->next_question_id !== null) {
                $nextQuestion = QuizQuestion::query()->findOrFail($option->next_question_id);

                if ($nextQuestion->is_terminal) {
                    return $this->completeWithOutcome($session, $nextQuestion);
                }

                $session->update(['current_question_id' => $nextQuestion->id]);

                return $session->fresh(['currentQuestion.options']);
            }

            if ($question->is_terminal) {
                return $this->completeWithOutcome($session, $question);
            }

            throw new InvalidArgumentException('Branch configuration is incomplete for this answer.');
        });
    }

    public function completeWithOutcome(QuizSession $session, QuizQuestion $terminalQuestion): QuizSession
    {
        $session->update([
            'status' => QuizSessionStatus::Completed,
            'current_question_id' => null,
            'outcome_question_id' => $terminalQuestion->id,
            'outcome_label' => $terminalQuestion->outcome_label,
            'completed_at' => now(),
        ]);

        return $session->fresh(['outcomeQuestion']);
    }

    public function progressPercent(QuizSession $session): int
    {
        $total = $session->quiz->questions()->count();
        $answered = $session->responses()->count();

        if ($total === 0) {
            return 0;
        }

        return (int) min(100, round(($answered / $total) * 100));
    }
}
