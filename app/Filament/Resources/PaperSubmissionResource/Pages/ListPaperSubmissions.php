<?php

namespace App\Filament\Resources\PaperSubmissionResource\Pages;

use App\Filament\Resources\PaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaperSubmissions extends ListRecords
{
    protected static ?int $navigationSort = 49;
    protected static string $resource = PaperSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
