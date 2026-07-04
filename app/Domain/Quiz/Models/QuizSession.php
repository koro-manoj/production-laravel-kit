<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Models;

use App\Domain\Quiz\Enums\QuizSessionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSession extends Model
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'status',
        'current_question_id',
        'outcome_question_id',
        'outcome_label',
        'metadata',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => QuizSessionStatus::class,
            'metadata' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'current_question_id');
    }

    public function outcomeQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'outcome_question_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(QuizResponse::class);
    }
}
