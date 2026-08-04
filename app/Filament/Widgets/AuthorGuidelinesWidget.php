<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class AuthorGuidelinesWidget extends Widget
{
    protected static string $view = 'filament.widgets.author-guidelines-widget';
    protected int | string | array $columnSpan = 'full';
    
    public static function canView(): bool
    {
        return Auth::check() && (!Auth::user()->hasRole(['admin', 'superadmin']) || Auth::user()->hasRole('author'));
    }
}