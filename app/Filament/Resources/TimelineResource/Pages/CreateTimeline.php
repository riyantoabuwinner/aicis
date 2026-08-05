<?php

namespace App\Filament\Resources\TimelineResource\Pages;

use App\Filament\Resources\TimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeline extends CreateRecord
{
    protected static ?int $navigationSort = 16;
    protected static string $resource = TimelineResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
