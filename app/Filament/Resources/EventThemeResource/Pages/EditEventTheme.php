<?php

namespace App\Filament\Resources\EventThemeResource\Pages;

use App\Filament\Resources\EventThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventTheme extends EditRecord
{
    protected static string $resource = EventThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
