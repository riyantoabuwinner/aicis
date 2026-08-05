<?php

namespace App\Filament\Resources\PaperSubmissionResource\Pages;

use App\Filament\Resources\PaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaperSubmission extends CreateRecord
{
    protected static ?int $navigationSort = 10;
    protected static string $resource = PaperSubmissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
