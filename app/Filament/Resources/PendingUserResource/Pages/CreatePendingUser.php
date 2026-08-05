<?php

namespace App\Filament\Resources\PendingUserResource\Pages;

use App\Filament\Resources\PendingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePendingUser extends CreateRecord
{
    protected static ?int $navigationSort = 11;
    protected static string $resource = PendingUserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
