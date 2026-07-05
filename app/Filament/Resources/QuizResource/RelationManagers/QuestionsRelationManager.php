<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Domain\Quiz\Models\QuizQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Questions & branching';

    public function form(Form $form): Form
    {
        $quizId = $this->getOwnerRecord()->getKey();

        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->maxLength(64)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('key', Str::slug($state ?? '', '_'))),
            Forms\Components\TextInput::make('prompt')
                ->required()
                ->columnSpanFull(),
            Forms\Components\Textarea::make('help_text')->columnSpanFull(),
            Forms\Components\Toggle::make('is_terminal')
                ->label('Terminal outcome')
                ->live(),
            Forms\Components\TextInput::make('outcome_label')
                ->visible(fn (Forms\Get $get): bool => (bool) $get('is_terminal')),
            Forms\Components\Textarea::make('outcome_summary')
                ->visible(fn (Forms\Get $get): bool => (bool) $get('is_terminal'))
                ->columnSpanFull(),
            Forms\Components\TextInput::make('recommended_product_cents')
                ->label('Recommended price (cents)')
                ->numeric()
                ->visible(fn (Forms\Get $get): bool => (bool) $get('is_terminal')),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
            Forms\Components\Repeater::make('options')
                ->relationship()
                ->visible(fn (Forms\Get $get): bool => ! (bool) $get('is_terminal'))
                ->schema([
                    Forms\Components\TextInput::make('label')->required(),
                    Forms\Components\TextInput::make('value')->required(),
                    Forms\Components\Select::make('next_question_id')
                        ->label('Next question')
                        ->options(fn () => QuizQuestion::query()
                            ->where('quiz_id', $quizId)
                            ->orderBy('sort_order')
                            ->pluck('prompt', 'id'))
                        ->searchable()
                        ->nullable(),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])
                ->columns(2)
                ->columnSpanFull()
                ->collapsible()
                ->defaultItems(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->badge(),
                Tables\Columns\TextColumn::make('prompt')->limit(50)->searchable(),
                Tables\Columns\IconColumn::make('is_terminal')->boolean()->label('Outcome'),
                Tables\Columns\TextColumn::make('options_count')->counts('options')->label('Options'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
