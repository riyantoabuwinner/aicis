<?php

namespace App\Filament\Resources\PendingUserResource\Pages;

use App\Filament\Resources\PendingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendingUser extends EditRecord
{
    protected static ?int $navigationSort = 30;
    protected static string $resource = PendingUserResource::class;

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
