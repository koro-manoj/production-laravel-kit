<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('entry_question_id')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('key')->nullable();
            $table->string('prompt');
            $table->text('help_text')->nullable();
            $table->string('type')->default('single_choice');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_terminal')->default(false);
            $table->string('outcome_label')->nullable();
            $table->text('outcome_summary')->nullable();
            $table->unsignedInteger('recommended_product_cents')->nullable();
            $table->timestamps();

            $table->unique(['quiz_id', 'key']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign('entry_question_id')->references('id')->on('quiz_questions')->nullOnDelete();
        });

        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->string('label');
            $table->string('value');
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('next_question_id')->nullable()->constrained('quiz_questions')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('in_progress');
            $table->foreignId('current_question_id')->nullable()->constrained('quiz_questions')->nullOnDelete();
            $table->foreignId('outcome_question_id')->nullable()->constrained('quiz_questions')->nullOnDelete();
            $table->string('outcome_label')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->uuid('quiz_session_id');
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('quiz_options')->nullOnDelete();
            $table->text('free_text')->nullable();
            $table->timestamps();

            $table->foreign('quiz_session_id')->references('id')->on('quiz_sessions')->cascadeOnDelete();
            $table->unique(['quiz_session_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_responses');
        Schema::dropIfExists('quiz_sessions');
        Schema::dropIfExists('quiz_options');
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['entry_question_id']);
        });
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
