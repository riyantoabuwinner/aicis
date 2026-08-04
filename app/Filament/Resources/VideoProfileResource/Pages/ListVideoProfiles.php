<?php

namespace App\Filament\Resources\VideoProfileResource\Pages;

use App\Filament\Resources\VideoProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoProfiles extends ListRecords
{
    protected static string $resource = VideoProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
