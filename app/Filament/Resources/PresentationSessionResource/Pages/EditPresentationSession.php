<?php

namespace App\Filament\Resources\PresentationSessionResource\Pages;

use App\Filament\Resources\PresentationSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPresentationSession extends EditRecord
{
    protected static string $resource = PresentationSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
