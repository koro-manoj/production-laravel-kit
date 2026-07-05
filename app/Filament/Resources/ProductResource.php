<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Auth\Enums\RoleName;
use App\Domain\Payments\Models\Product;
use App\Filament\Concerns\ChecksFilamentRoles;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    use ChecksFilamentRoles;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Commerce';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return self::userHasAnyRole([RoleName::Admin, RoleName::Pharmacy]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('image_url')
                ->label('Primary image URL')
                ->url()
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('gallery')
                ->label('Gallery image URLs')
                ->placeholder('Add URL and press Enter')
                ->columnSpanFull(),
            Forms\Components\Select::make('category')
                ->options(config('store.categories'))
                ->default('home')
                ->required(),
            Forms\Components\TextInput::make('badge')
                ->maxLength(50)
                ->placeholder('Bestseller, New, etc.'),
            Forms\Components\TextInput::make('price_cents')
                ->label('Price (cents)')
                ->numeric()
                ->required()
                ->minValue(0),
            Forms\Components\TextInput::make('compare_at_price_cents')
                ->label('Compare-at price (cents)')
                ->numeric()
                ->minValue(0),
            Forms\Components\TextInput::make('currency')
                ->default('USD')
                ->maxLength(3)
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->formatStateUsing(fn (?string $state): string => config('store.categories')[$state] ?? $state ?? '—'),
                Tables\Columns\TextColumn::make('badge'),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label('Price')
                    ->formatStateUsing(fn (int $state, Product $record): string => '$'.number_format($state / 100, 2).' '.strtoupper($record->currency)),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
