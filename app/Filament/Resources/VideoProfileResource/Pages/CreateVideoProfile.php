<?php

namespace App\Filament\Resources\VideoProfileResource\Pages;

use App\Filament\Resources\VideoProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVideoProfile extends CreateRecord
{
    protected static ?int $navigationSort = 18;
    protected static string $resource = VideoProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
