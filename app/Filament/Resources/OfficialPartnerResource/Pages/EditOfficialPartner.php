<?php

namespace App\Filament\Resources\OfficialPartnerResource\Pages;

use App\Filament\Resources\OfficialPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfficialPartner extends EditRecord
{
    protected static ?int $navigationSort = 26;
    protected static string $resource = OfficialPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
