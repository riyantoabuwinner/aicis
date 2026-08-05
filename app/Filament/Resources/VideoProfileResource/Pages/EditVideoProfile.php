<?php

namespace App\Filament\Resources\VideoProfileResource\Pages;

use App\Filament\Resources\VideoProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoProfile extends EditRecord
{
    protected static ?int $navigationSort = 38;
    protected static string $resource = VideoProfileResource::class;

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
