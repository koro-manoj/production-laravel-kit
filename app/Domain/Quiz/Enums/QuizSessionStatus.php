<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Enums;

enum QuizSessionStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Abandoned = 'abandoned';
}
