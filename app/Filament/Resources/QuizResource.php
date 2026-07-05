<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Auth\Enums\RoleName;
use App\Domain\Quiz\Models\Quiz;
use App\Filament\Concerns\ChecksFilamentRoles;
use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuizResource extends Resource
{
    use ChecksFilamentRoles;

    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Quiz';

    public static function canViewAny(): bool
    {
        return self::userHasAnyRole([RoleName::Admin, RoleName::Clinic]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Quiz details')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(2),
            Forms\Components\Section::make('Funnel entry')
                ->description('Set after adding questions in the tab below.')
                ->schema([
                    Forms\Components\Select::make('entry_question_id')
                        ->label('First question')
                        ->options(fn (?Quiz $record): array => $record
                            ? $record->questions()->orderBy('sort_order')->pluck('prompt', 'id')->all()
                            : [])
                        ->searchable()
                        ->nullable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions'),
                Tables\Columns\TextColumn::make('sessions_count')->counts('sessions')->label('Sessions'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
