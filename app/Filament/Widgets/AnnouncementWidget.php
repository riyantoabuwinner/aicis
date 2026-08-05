<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use Filament\Widgets\Widget;

class AnnouncementWidget extends Widget
{
    protected static string $view = 'filament.widgets.announcement-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getAnnouncements()
    {
        return Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
