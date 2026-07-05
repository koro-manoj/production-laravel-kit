<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Payments\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent orders';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('reference')->searchable(),
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('customer_email')->label('Email'),
                Tables\Columns\TextColumn::make('amount_cents')
                    ->label('Amount')
                    ->formatStateUsing(fn (int $state, Order $record): string => '$'.number_format($state / 100, 2)),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, g:i A')),
            ])
            ->paginated(false);
    }
}
