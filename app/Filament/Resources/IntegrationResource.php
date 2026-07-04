<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Integrations\Models\Integration;
use App\Filament\Resources\IntegrationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IntegrationResource extends Resource
{
    protected static ?string $model = Integration::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('provider')
                ->options([
                    'stripe' => 'Stripe',
                ])
                ->required()
                ->disabledOn('edit'),
            Forms\Components\TextInput::make('credentials.publishable_key')
                ->label('Publishable key')
                ->password()
                ->revealable(),
            Forms\Components\TextInput::make('credentials.secret_key')
                ->label('Secret key')
                ->password()
                ->revealable()
                ->required(),
            Forms\Components\TextInput::make('credentials.webhook_secret')
                ->label('Webhook secret')
                ->password()
                ->revealable(),
            Forms\Components\Toggle::make('is_sandbox')
                ->default(true),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('provider')->badge(),
                Tables\Columns\IconColumn::make('is_sandbox')->boolean()->label('Sandbox'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntegrations::route('/'),
            'create' => Pages\CreateIntegration::route('/create'),
            'edit' => Pages\EditIntegration::route('/{record}/edit'),
        ];
    }
}
