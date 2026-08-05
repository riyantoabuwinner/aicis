<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\PaperSubmission;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
                
            Stat::make('Total Papers Submitted', PaperSubmission::count())
                ->description('Pending and reviewed papers')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([3, 5, 8, 12, 18, 22, 28])
                ->color('primary'),
                
            Stat::make('Approved Papers', PaperSubmission::where('status', 'accepted')->count())
                ->description('Papers accepted for conference')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([0, 1, 1, 2, 4, 8, 12])
                ->color('warning'),
        ];
    }
}
