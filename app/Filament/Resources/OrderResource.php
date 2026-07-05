<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Auth\Enums\RoleName;
use App\Domain\Payments\Models\Order;
use App\Filament\Concerns\ChecksFilamentRoles;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    use ChecksFilamentRoles;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Payments';

    public static function canViewAny(): bool
    {
        return self::userHasAnyRole([RoleName::Admin, RoleName::Clinic, RoleName::Pharmacy]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')->searchable(),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('customer_email'),
                Tables\Columns\TextColumn::make('amount_cents')
                    ->label('Amount')
                    ->formatStateUsing(fn (int $state, Order $record): string => '$'.number_format($state / 100, 2).' '.strtoupper($record->currency)),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y g:i A'))
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
            Infolists\Components\Section::make('Order')
                ->schema([
                    Infolists\Components\TextEntry::make('reference'),
                    Infolists\Components\TextEntry::make('status')->badge(),
                    Infolists\Components\TextEntry::make('product.name')->label('Product'),
                    Infolists\Components\TextEntry::make('amount_cents')
                        ->label('Amount')
                        ->formatStateUsing(fn (int $state, Order $record): string => '$'.number_format($state / 100, 2).' '.strtoupper($record->currency)),
                    Infolists\Components\TextEntry::make('customer_name')->label('Customer'),
                    Infolists\Components\TextEntry::make('customer_email')->label('Email'),
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Created')
                        ->formatStateUsing(fn ($state) => $state?->format('M j, Y g:i A')),
                ])->columns(2),
            Infolists\Components\Section::make('Payment')
                ->schema([
                    Infolists\Components\TextEntry::make('latestPayment.status')
                        ->label('Payment status')
                        ->badge()
                        ->placeholder('No payment yet'),
                    Infolists\Components\TextEntry::make('latestPayment.stripe_checkout_session_id')
                        ->label('Stripe session')
                        ->placeholder('—'),
                ])->columns(2),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
