<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Quiz\Models\Quiz;
use App\Domain\Quiz\Models\QuizOption;
use App\Domain\Quiz\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::query()->updateOrCreate(
            ['slug' => 'health-assessment'],
            [
                'title' => 'Gift Finder',
                'description' => 'A short product-matching quiz that routes shoppers to curated Northline picks.',
                'is_active' => true,
            ]
        );

        $q1 = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'recipient'],
            [
                'prompt' => 'Who are you shopping for?',
                'help_text' => 'We will tailor recommendations to the right collection.',
                'sort_order' => 1,
            ]
        );

        $q2Home = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'home_style'],
            [
                'prompt' => 'Which home ritual matters most?',
                'sort_order' => 2,
            ]
        );

        $q2Desk = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'desk_style'],
            [
                'prompt' => 'What does their workspace need most?',
                'sort_order' => 3,
            ]
        );

        $outcomeHome = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_home'],
            [
                'prompt' => 'Home & Living path',
                'is_terminal' => true,
                'outcome_label' => 'Cozy home essentials',
                'outcome_summary' => 'Soft textures and everyday rituals — start with throws, mugs, and warm lighting.',
                'recommended_product_cents' => null,
                'sort_order' => 10,
            ]
        );

        $outcomeDesk = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_desk'],
            [
                'prompt' => 'Desk & Office path',
                'is_terminal' => true,
                'outcome_label' => 'Focused desk setup',
                'outcome_summary' => 'Organizers, lamps, and tools that keep a workspace calm and intentional.',
                'recommended_product_cents' => null,
                'sort_order' => 11,
            ]
        );

        $outcomeTravel = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_travel'],
            [
                'prompt' => 'Travel & Outdoor path',
                'is_terminal' => true,
                'outcome_label' => 'Ready to go',
                'outcome_summary' => 'Packable, durable goods for commuters and weekend trips.',
                'recommended_product_cents' => null,
                'sort_order' => 12,
            ]
        );

        $this->seedOption($q1, 'Homebody or host', 'home', 1, $q2Home);
        $this->seedOption($q1, 'Remote worker or student', 'desk', 2, $q2Desk);
        $this->seedOption($q1, 'Traveler or commuter', 'travel', 3, $outcomeTravel);

        $this->seedOption($q2Home, 'Evening unwind', 'relax', 1, $outcomeHome);
        $this->seedOption($q2Home, 'Weekend hosting', 'host', 2, $outcomeHome);

        $this->seedOption($q2Desk, 'Clear the clutter', 'organize', 1, $outcomeDesk);
        $this->seedOption($q2Desk, 'Better lighting', 'light', 2, $outcomeDesk);

        $quiz->update(['entry_question_id' => $q1->id]);
    }

    private function seedOption(
        QuizQuestion $question,
        string $label,
        string $value,
        int $sort,
        QuizQuestion $next,
    ): void {
        QuizOption::query()->updateOrCreate(
            [
                'question_id' => $question->id,
                'value' => $value,
            ],
            [
                'label' => $label,
                'sort_order' => $sort,
                'next_question_id' => $next->id,
            ]
        );
    }
}
