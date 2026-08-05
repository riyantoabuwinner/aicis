<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DownloadTemplate extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.download-template';

    protected static ?string $navigationGroup = 'Information';
    
    protected static ?int $navigationSort = 2;
}
