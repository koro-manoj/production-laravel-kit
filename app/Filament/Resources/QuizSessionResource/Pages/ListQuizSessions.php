<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuizSessionResource\Pages;

use App\Filament\Resources\QuizSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListQuizSessions extends ListRecords
{
    protected static string $resource = QuizSessionResource::class;
}
