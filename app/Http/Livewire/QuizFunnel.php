<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Domain\Payments\Models\Product;
use App\Domain\Payments\Services\StripeCheckoutService;
use App\Domain\Quiz\Enums\QuizSessionStatus;
use App\Domain\Quiz\Jobs\ProcessQuizCompletionJob;
use App\Domain\Quiz\Models\Quiz;
use App\Domain\Quiz\Models\QuizOption;
use App\Domain\Quiz\Models\QuizQuestion;
use App\Domain\Quiz\Models\QuizSession;
use App\Domain\Quiz\Services\QuizFunnelService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Livewire\Component;

class QuizFunnel extends Component
{
    public Quiz $quiz;

    public ?QuizSession $session = null;

    public ?QuizQuestion $currentQuestion = null;

    public int $progress = 0;

    public ?string $selectedOptionId = null;

    public function mount(Quiz $quiz, QuizFunnelService $funnel): void
    {
        abort_unless($quiz->is_active, 404);

        $this->quiz = $quiz->load('entryQuestion.options');

        $existingId = session('quiz_session_'.$quiz->slug);

        if (is_string($existingId)) {
            $existing = QuizSession::query()
                ->with(['currentQuestion.options', 'outcomeQuestion'])
                ->find($existingId);

            if ($existing !== null && $existing->status === QuizSessionStatus::InProgress) {
                $this->session = $existing;
                $this->currentQuestion = $existing->currentQuestion;
                $this->progress = $funnel->progressPercent($existing);

                return;
            }
        }

        $this->session = $funnel->startSession($quiz, Auth::user());
        session(['quiz_session_'.$quiz->slug => $this->session->id]);
        $this->currentQuestion = $this->session->currentQuestion;
        $this->progress = $funnel->progressPercent($this->session);
    }

    public function submitAnswer(QuizFunnelService $funnel): void
    {
        if ($this->session === null || $this->currentQuestion === null || $this->selectedOptionId === null) {
            return;
        }

        $option = QuizOption::query()->findOrFail((int) $this->selectedOptionId);

        try {
            $this->session = $funnel->submitAnswer($this->session, $this->currentQuestion, $option);
        } catch (InvalidArgumentException $exception) {
            $this->addError('selectedOptionId', $exception->getMessage());

            return;
        }

        $this->progress = $funnel->progressPercent($this->session);
        $this->selectedOptionId = null;

        if ($this->session->status === QuizSessionStatus::Completed) {
            ProcessQuizCompletionJob::dispatch($this->session->id);

            return;
        }

        $this->currentQuestion = $this->session->currentQuestion;
    }

    public function recommendedProduct(): ?Product
    {
        $cents = $this->session?->outcomeQuestion?->recommended_product_cents;

        if ($cents === null) {
            return Product::query()->where('is_active', true)->orderBy('id')->first();
        }

        return Product::query()
            ->where('is_active', true)
            ->where('price_cents', $cents)
            ->first()
            ?? Product::query()->where('is_active', true)->orderBy('id')->first();
    }

    public function render(): View
    {
        return view('livewire.quiz-funnel');
    }
}
