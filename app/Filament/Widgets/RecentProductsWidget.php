<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Payments\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Catalog snapshot';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->latest('updated_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->formatStateUsing(fn (?string $state): string => config('store.categories')[$state] ?? $state ?? '—'),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label('Price')
                    ->formatStateUsing(fn (int $state, Product $record): string => money_format_cents($state, $record->currency)),
                Tables\Columns\IconColumn::make('is_active')->label('Live')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since(),
            ])
            ->paginated(false);
    }
}
