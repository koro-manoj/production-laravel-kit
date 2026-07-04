<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Quiz\Models\Quiz;
use App\Domain\Quiz\Services\QuizFunnelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizFunnelTest extends TestCase
{
    use RefreshDatabase;

    public function test_branching_quiz_reaches_terminal_outcome(): void
    {
        $this->seed();

        $quiz = Quiz::query()->where('slug', 'health-assessment')->firstOrFail();
        $funnel = app(QuizFunnelService::class);

        $session = $funnel->startSession($quiz);
        $question = $session->currentQuestion;
        $routineOption = $question->options()->where('value', 'routine')->firstOrFail();

        $session = $funnel->submitAnswer($session, $question, $routineOption);
        $question = $session->currentQuestion;
        $shortOption = $question->options()->where('value', 'short')->firstOrFail();

        $session = $funnel->submitAnswer($session, $question, $shortOption);

        $this->assertSame('completed', $session->status->value);
        $this->assertSame('Wellness support recommended', $session->outcome_label);
    }
}
