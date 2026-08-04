<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['is_html']) && $data['is_html']) {
            $data['content'] = $data['content_html'] ?? null;
        }
        
        unset($data['is_html']);
        unset($data['content_html']);
        
        return $data;
    }
}
