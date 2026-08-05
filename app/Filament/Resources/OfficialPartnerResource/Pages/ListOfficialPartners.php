<?php

namespace App\Filament\Resources\OfficialPartnerResource\Pages;

use App\Filament\Resources\OfficialPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfficialPartners extends ListRecords
{
    protected static ?int $navigationSort = 46;
    protected static string $resource = OfficialPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(\App\Filament\Imports\OfficialPartnerImporter::class)
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\CreateAction::make(),
        ];
    }
}
