<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Quiz\Models\QuizSession;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentQuizSessionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent quiz sessions';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return QuizSession::query()->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                QuizSession::query()
                    ->with(['quiz', 'user'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('quiz.title')->label('Quiz'),
                Tables\Columns\TextColumn::make('user.name')->label('User')->placeholder('Guest'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('outcome_label')->label('Outcome')->placeholder('—'),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, g:i A') ?? '—'),
            ])
            ->paginated(false);
    }
}
