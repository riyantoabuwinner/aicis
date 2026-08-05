<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Guideline extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.guideline';

    protected static ?string $navigationGroup = 'Information';
    
    protected static ?int $navigationSort = 1;
}
