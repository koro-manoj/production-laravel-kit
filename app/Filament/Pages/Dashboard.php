<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\OverviewStatsWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\RecentQuizSessionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public function getWidgets(): array
    {
        return [
            OverviewStatsWidget::class,
            RecentOrdersWidget::class,
            RecentQuizSessionsWidget::class,
        ];
    }
}
