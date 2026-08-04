<?php

namespace App\Filament\Resources\MyPaperSubmissionResource\Pages;

use App\Filament\Resources\MyPaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyPaperSubmissions extends ListRecords
{
    protected static string $resource = MyPaperSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
