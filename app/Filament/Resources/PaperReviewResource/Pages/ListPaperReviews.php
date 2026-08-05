<?php

namespace App\Filament\Resources\PaperReviewResource\Pages;

use App\Filament\Resources\PaperReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaperReviews extends ListRecords
{
    protected static ?int $navigationSort = 48;
    protected static string $resource = PaperReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
