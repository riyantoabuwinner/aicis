<?php

namespace App\Filament\Pages;

use App\Models\Announcement;
use Filament\Pages\Page;

class ParticipantAnnouncement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static string $view = 'filament.pages.participant-announcement';

    protected static ?string $navigationGroup = 'Information';
    
    protected static ?string $navigationLabel = 'Announcements';

    protected static ?string $title = 'Announcements';

    protected static ?int $navigationSort = 0;

    public static function canAccess(): bool
    {
        return !auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public function getAnnouncements()
    {
        return Announcement::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
