<?php

namespace App\Filament\Resources\MyPaperSubmissionResource\Pages;

use App\Filament\Resources\MyPaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyPaperSubmission extends EditRecord
{
    protected static string $resource = MyPaperSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If they upload full paper during edit and status was Abstract Submitted or Revision Required
        if (!empty($data['full_paper_path'])) {
            if (in_array($this->record->status, ['Draft', 'Abstract Submitted'])) {
                $data['status'] = 'Full Paper Submitted';
            } elseif ($this->record->status === 'Revision Required') {
                $data['status'] = 'Revision Submitted';
            }
        }
        
        return $data;
    }
}
