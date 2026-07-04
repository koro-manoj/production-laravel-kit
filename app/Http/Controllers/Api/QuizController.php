<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Quiz\Enums\QuizSessionStatus;
use App\Domain\Quiz\Jobs\ProcessQuizCompletionJob;
use App\Domain\Quiz\Models\Quiz;
use App\Domain\Quiz\Models\QuizOption;
use App\Domain\Quiz\Models\QuizQuestion;
use App\Domain\Quiz\Models\QuizSession;
use App\Domain\Quiz\Services\QuizFunnelService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class QuizController extends Controller
{
    public function index(): JsonResponse
    {
        $quizzes = Quiz::query()
            ->where('is_active', true)
            ->select(['id', 'slug', 'title', 'description'])
            ->orderBy('title')
            ->get();

        return response()->json(['data' => $quizzes]);
    }

    public function start(Quiz $quiz, QuizFunnelService $funnel, Request $request): JsonResponse
    {
        abort_unless($quiz->is_active, 404);

        $session = $funnel->startSession($quiz, $request->user());

        return response()->json($this->sessionPayload($session, $funnel), 201);
    }

    public function show(QuizSession $session, QuizFunnelService $funnel): JsonResponse
    {
        $this->authorizeSession($session, request());

        return response()->json($this->sessionPayload($session->load(['currentQuestion.options', 'outcomeQuestion']), $funnel));
    }

    public function answer(
        QuizSession $session,
        QuizQuestion $question,
        Request $request,
        QuizFunnelService $funnel,
    ): JsonResponse {
        $this->authorizeSession($session, $request);

        $validated = $request->validate([
            'option_id' => ['required', 'integer', 'exists:quiz_options,id'],
        ]);

        $option = QuizOption::query()->findOrFail($validated['option_id']);

        try {
            $session = $funnel->submitAnswer($session, $question, $option);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        if ($session->status === QuizSessionStatus::Completed) {
            ProcessQuizCompletionJob::dispatch($session->id);
        }

        return response()->json($this->sessionPayload($session->load(['currentQuestion.options', 'outcomeQuestion']), $funnel));
    }

    private function authorizeSession(QuizSession $session, Request $request): void
    {
        $user = $request->user();

        if ($user !== null && $session->user_id !== null && $session->user_id !== $user->id) {
            abort(403);
        }
    }

    /** @return array<string, mixed> */
    private function sessionPayload(QuizSession $session, QuizFunnelService $funnel): array
    {
        return [
            'id' => $session->id,
            'quiz_id' => $session->quiz_id,
            'status' => $session->status->value,
            'progress_percent' => $funnel->progressPercent($session),
            'current_question' => $session->currentQuestion ? [
                'id' => $session->currentQuestion->id,
                'prompt' => $session->currentQuestion->prompt,
                'help_text' => $session->currentQuestion->help_text,
                'options' => $session->currentQuestion->options->map(fn (QuizOption $option) => [
                    'id' => $option->id,
                    'label' => $option->label,
                    'value' => $option->value,
                ])->values(),
            ] : null,
            'outcome' => $session->outcomeQuestion ? [
                'label' => $session->outcome_label,
                'summary' => $session->outcomeQuestion->outcome_summary,
                'recommended_product_cents' => $session->outcomeQuestion->recommended_product_cents,
            ] : null,
        ];
    }
}
