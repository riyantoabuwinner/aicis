<?php

namespace App\Filament\Resources\GuidelineResource\Pages;

use App\Filament\Resources\GuidelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuidelines extends ListRecords
{
    protected static string $resource = GuidelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
