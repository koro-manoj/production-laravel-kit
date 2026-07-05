<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Auth\Enums\RoleName;
use App\Domain\Payments\Models\Payment;
use App\Filament\Concerns\ChecksFilamentRoles;
use App\Filament\Resources\PaymentResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    use ChecksFilamentRoles;

    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Payments';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return self::userHasAnyRole([RoleName::Admin, RoleName::Pharmacy]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.reference')->label('Order')->searchable(),
                Tables\Columns\TextColumn::make('provider')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('amount_cents')
                    ->label('Amount')
                    ->formatStateUsing(fn (int $state, Payment $record): string => '$'.number_format($state / 100, 2).' '.strtoupper($record->currency)),
                Tables\Columns\TextColumn::make('stripe_checkout_session_id')->label('Session')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Paid')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y g:i A') ?? '—'),
            ])
            ->defaultSort('paid_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
        ];
    }
}
