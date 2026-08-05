<?php

namespace App\Filament\Resources\TimelineResource\Pages;

use App\Filament\Resources\TimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeline extends EditRecord
{
    protected static ?int $navigationSort = 36;
    protected static string $resource = TimelineResource::class;

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
