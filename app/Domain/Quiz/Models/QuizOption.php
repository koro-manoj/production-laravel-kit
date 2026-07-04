<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizOption extends Model
{
    protected $fillable = [
        'question_id',
        'label',
        'value',
        'sort_order',
        'next_question_id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function nextQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'next_question_id');
    }
}
