<?php

namespace App\Filament\Resources\GuidelineResource\Pages;

use App\Filament\Resources\GuidelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuideline extends EditRecord
{
    protected static string $resource = GuidelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
