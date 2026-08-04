<?php

namespace App\Filament\Resources\PresentationSessionResource\Pages;

use App\Filament\Resources\PresentationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPresentationSessions extends ListRecords
{
    protected static string $resource = PresentationSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
