<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Payments\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Quiz\Models\QuizSession;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidRevenue = Order::query()->where('status', 'paid')->sum('amount_cents');
        $pendingOrders = Order::query()->where('status', 'pending')->count();
        $completedSessions = QuizSession::query()->where('status', 'completed')->count();
        $successfulPayments = Payment::query()->where('status', 'succeeded')->count();

        return [
            Stat::make('Revenue', '$'.number_format($paidRevenue / 100, 2))
                ->description('Paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Pending orders', (string) $pendingOrders)
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Quiz completions', (string) $completedSessions)
                ->description('Finished assessments')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),
            Stat::make('Payments', (string) $successfulPayments)
                ->description('Stripe succeeded')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('gray'),
        ];
    }
}
