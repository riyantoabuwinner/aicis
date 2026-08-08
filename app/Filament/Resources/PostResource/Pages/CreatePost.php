<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Hashtag;
use App\Models\Gallery;

class CreatePost extends CreateRecord
{
    protected static ?int $navigationSort = 12;
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle HTML Mode
        if (isset($data['is_html']) && $data['is_html']) {
            $data['content'] = $data['content_html'] ?? null;
        }
        
        unset($data['is_html']);
        unset($data['content_html']);
        

        // Handle new Hashtags (save them to suggestions)
        if (isset($data['hashtags']) && is_array($data['hashtags'])) {
            foreach ($data['hashtags'] as $tag) {
                Hashtag::firstOrCreate(['name' => $tag]);
            }
        }

        return $data;
    }

}
