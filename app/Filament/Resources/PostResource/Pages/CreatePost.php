<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Hashtag;
use App\Models\Gallery;

class CreatePost extends CreateRecord
{
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

    protected function afterCreate(): void
    {
        $post = $this->record;

        // Process RichEditor Images from Content
        if ($post->content) {
            preg_match_all('/src="([^"]+)"/i', $post->content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $url) {
                    if (str_contains($url, '/storage/')) {
                        $path = explode('/storage/', $url)[1];
                        Gallery::firstOrCreate(['file_path' => $path]);
                    }
                }
            }
        }

        // Process Featured Image
        if ($post->featured_image) {
            Gallery::firstOrCreate(['file_path' => $post->featured_image]);
        }
    }
}
