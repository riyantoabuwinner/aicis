<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PaperSubmission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaperSubmissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Paper Submissions Over Time';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        
        $submissions = PaperSubmission::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = $submissions[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Submissions',
                    'data' => $data,
                    'borderColor' => '#ca8a04', // elegant gold/warning color
                    'backgroundColor' => 'rgba(202, 138, 4, 0.1)',
                    'fill' => true,
                    'tension' => 0.4, // smooth curve
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
