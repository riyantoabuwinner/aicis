<?php

namespace App\Filament\Resources\ConferenceResource\Pages;

use App\Filament\Resources\ConferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConference extends CreateRecord
{
    protected static ?int $navigationSort = 2;
    protected static string $resource = ConferenceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
