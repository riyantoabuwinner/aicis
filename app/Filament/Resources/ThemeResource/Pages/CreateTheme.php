<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTheme extends CreateRecord
{
    protected static ?int $navigationSort = 15;
    protected static string $resource = ThemeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
