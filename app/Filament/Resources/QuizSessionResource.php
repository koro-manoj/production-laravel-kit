<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Auth\Enums\RoleName;
use App\Domain\Quiz\Models\QuizSession;
use App\Filament\Concerns\ChecksFilamentRoles;
use App\Filament\Resources\QuizSessionResource\Pages;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuizSessionResource extends Resource
{
    use ChecksFilamentRoles;

    protected static ?string $model = QuizSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationLabel = 'Sessions';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return self::userHasAnyRole([RoleName::Admin, RoleName::Clinic]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quiz.title')->label('Quiz')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->placeholder('Guest'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('outcome_label')->label('Outcome')->placeholder('—'),
                Tables\Columns\TextColumn::make('responses_count')->counts('responses')->label('Answers'),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y g:i A') ?? '—')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Session')
                ->schema([
                    Infolists\Components\TextEntry::make('quiz.title')->label('Quiz'),
                    Infolists\Components\TextEntry::make('user.name')->label('User')->placeholder('Guest'),
                    Infolists\Components\TextEntry::make('status')->badge(),
                    Infolists\Components\TextEntry::make('outcome_label')->label('Outcome')->placeholder('—'),
                    Infolists\Components\TextEntry::make('completed_at')
                        ->label('Completed')
                        ->formatStateUsing(fn ($state) => $state?->format('M j, Y g:i A') ?? '—'),
                ])->columns(2),
            Infolists\Components\Section::make('Responses')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('responses')
                        ->schema([
                            Infolists\Components\TextEntry::make('question.prompt')->label('Question'),
                            Infolists\Components\TextEntry::make('option.label')->label('Answer'),
                        ])
                        ->columns(2),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizSessions::route('/'),
            'view' => Pages\ViewQuizSession::route('/{record}'),
        ];
    }
}
