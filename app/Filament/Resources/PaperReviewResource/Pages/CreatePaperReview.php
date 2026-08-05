<?php

namespace App\Filament\Resources\PaperReviewResource\Pages;

use App\Filament\Resources\PaperReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaperReview extends CreateRecord
{
    protected static ?int $navigationSort = 9;
    protected static string $resource = PaperReviewResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
