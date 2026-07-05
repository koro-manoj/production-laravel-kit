<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidRevenue = (int) Order::query()->where('status', 'paid')->sum('amount_cents');
        $paidOrders = Order::query()->where('status', 'paid')->count();
        $pendingOrders = Order::query()->where('status', 'pending')->count();
        $activeProducts = Product::query()->where('is_active', true)->count();

        return [
            Stat::make('Revenue', money_format_cents($paidRevenue, config('store.currency', 'USD')))
                ->description($paidOrders.' paid '.str('order')->plural($paidOrders))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pending orders', (string) $pendingOrders)
                ->description('Awaiting checkout')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Active products', (string) $activeProducts)
                ->description('In storefront catalog')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Total orders', (string) Order::query()->count())
                ->description('All statuses')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('gray'),
        ];
    }
}
