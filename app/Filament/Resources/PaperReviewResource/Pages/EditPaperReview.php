<?php

namespace App\Filament\Resources\PaperReviewResource\Pages;

use App\Filament\Resources\PaperReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaperReview extends EditRecord
{
    protected static ?int $navigationSort = 28;
    protected static string $resource = PaperReviewResource::class;

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
