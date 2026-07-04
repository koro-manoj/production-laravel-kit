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
                'title' => 'Health Assessment',
                'description' => 'A branching intake funnel that routes patients to the right care path.',
                'is_active' => true,
            ]
        );

        $q1 = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'symptoms'],
            [
                'prompt' => 'What best describes your current symptoms?',
                'help_text' => 'Choose the option that matches your primary concern today.',
                'sort_order' => 1,
            ]
        );

        $q2Routine = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'routine_duration'],
            [
                'prompt' => 'How long have you noticed these routine symptoms?',
                'sort_order' => 2,
            ]
        );

        $q2Urgent = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'urgent_severity'],
            [
                'prompt' => 'Are you experiencing severe pain, chest pressure, or difficulty breathing?',
                'sort_order' => 3,
            ]
        );

        $outcomeWellness = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_wellness'],
            [
                'prompt' => 'Wellness path',
                'is_terminal' => true,
                'outcome_label' => 'Wellness support recommended',
                'outcome_summary' => 'Your responses suggest lifestyle adjustments and a structured wellness plan would be a strong first step.',
                'recommended_product_cents' => 4900,
                'sort_order' => 10,
            ]
        );

        $outcomeConsult = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_consult'],
            [
                'prompt' => 'Consultation path',
                'is_terminal' => true,
                'outcome_label' => 'Clinician consultation recommended',
                'outcome_summary' => 'Based on duration and symptom pattern, a telehealth consultation will help clarify next steps.',
                'recommended_product_cents' => 7900,
                'sort_order' => 11,
            ]
        );

        $outcomeUrgent = QuizQuestion::query()->updateOrCreate(
            ['quiz_id' => $quiz->id, 'key' => 'outcome_urgent'],
            [
                'prompt' => 'Urgent path',
                'is_terminal' => true,
                'outcome_label' => 'Priority escalation recommended',
                'outcome_summary' => 'Your answers indicate symptoms that should be reviewed urgently by a clinician.',
                'recommended_product_cents' => 12900,
                'sort_order' => 12,
            ]
        );

        $this->seedOption($q1, 'Mild or routine symptoms', 'routine', 1, $q2Routine);
        $this->seedOption($q1, 'Concerning or worsening symptoms', 'urgent', 2, $q2Urgent);

        $this->seedOption($q2Routine, 'Less than two weeks', 'short', 1, $outcomeWellness);
        $this->seedOption($q2Routine, 'More than two weeks', 'long', 2, $outcomeConsult);

        $this->seedOption($q2Urgent, 'Yes — severe or alarming symptoms', 'yes', 1, $outcomeUrgent);
        $this->seedOption($q2Urgent, 'No — manageable but concerning', 'no', 2, $outcomeConsult);

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
