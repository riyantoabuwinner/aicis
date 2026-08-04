<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'published_at',
        'hashtags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'hashtags' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::saving(function ($post) {
            // Auto set featured image if empty
            if (empty($post->featured_image) && !empty($post->content)) {
                preg_match('/<img[^>]+src="([^">]+)"/i', $post->content, $matches);
                if (isset($matches[1])) {
                    $src = $matches[1];
                    $src = self::cleanStorageUrl($src);
                    $post->featured_image = $src;
                }
            }
        });

        static::saved(function ($post) {
            // Extract all images from content
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $post->content, $matches);
            $images = $matches[1] ?? [];
            
            // Add featured image if it exists and not already in array
            if (!empty($post->featured_image) && !in_array($post->featured_image, $images)) {
                $images[] = $post->featured_image;
            }

            foreach ($images as $src) {
                $src = self::cleanStorageUrl($src);
                
                // Add to galleries if not exists
                \App\Models\Gallery::firstOrCreate([
                    'file_path' => $src,
                ], [
                    'caption' => $post->title,
                ]);
            }
        });
    }

    private static function cleanStorageUrl($src)
    {
        // Regex to match optional protocol + hostname, followed by /storage/
        // E.g. "http://127.0.0.1:8000/storage/gallery/img.jpg" -> "gallery/img.jpg"
        // E.g. "/storage/gallery/img.jpg" -> "gallery/img.jpg"
        $pattern = '/^(?:https?:\/\/[^\/]+)?\/storage\//i';
        
        if (preg_match($pattern, $src)) {
            return preg_replace($pattern, '', $src);
        }
        
        return $src;
    }
}
