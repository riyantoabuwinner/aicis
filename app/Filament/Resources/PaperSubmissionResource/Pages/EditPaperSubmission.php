<?php

namespace App\Filament\Resources\PaperSubmissionResource\Pages;

use App\Filament\Resources\PaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaperSubmission extends EditRecord
{
    protected static ?int $navigationSort = 29;
    protected static string $resource = PaperSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
