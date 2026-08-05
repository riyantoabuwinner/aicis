<?php

namespace App\Filament\Resources\PresentationSessionResource\Pages;

use App\Filament\Resources\PresentationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePresentationSession extends CreateRecord
{
    protected static ?int $navigationSort = 13;
    protected static string $resource = PresentationSessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
