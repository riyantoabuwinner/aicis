<?php

namespace App\Filament\Resources\OfficialPartnerResource\Pages;

use App\Filament\Resources\OfficialPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOfficialPartner extends CreateRecord
{
    protected static ?int $navigationSort = 7;
    protected static string $resource = OfficialPartnerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
