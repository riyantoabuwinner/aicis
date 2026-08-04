<?php

namespace App\Filament\Resources\MyPaperSubmissionResource\Pages;

use App\Filament\Resources\MyPaperSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyPaperSubmission extends CreateRecord
{
    protected static string $resource = MyPaperSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['full_paper_path'])) {
            $data['status'] = 'Full Paper Submitted';
        } else {
            $data['status'] = 'Abstract Submitted';
        }
        
        return $data;
    }
}
