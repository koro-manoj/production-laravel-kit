<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'description',
        'is_active',
        'entry_question_id',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function entryQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'entry_question_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(QuizSession::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
