<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DownloadTemplate extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.download-template';

    protected static ?string $navigationGroup = 'Information';
    
    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return !auth()->user()->hasRole(['superadmin', 'admin']);
    }

    public function getDownloads()
    {
        return \App\Models\Download::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
