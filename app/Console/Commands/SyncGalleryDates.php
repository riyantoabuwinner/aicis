<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Gallery;

class SyncGalleryDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:sync-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize gallery image dates to match their origin posts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting gallery date synchronization...');
        $posts = Post::all();
        $updatedCount = 0;

        foreach ($posts as $post) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', (string) $post->content, $m);
            $images = $m[1] ?? [];
            foreach ($images as $src) {
                // Regex from Post.php to clean URL
                $src = preg_replace('/^(?:https?:\/\/[^\/]+)?\/storage\//i', '', $src);
                
                $gallery = Gallery::where('file_path', $src)->first();
                if ($gallery) {
                    $postDate = $post->published_at ?? $post->created_at;
                    if ($gallery->created_at != $postDate) {
                        $gallery->created_at = $postDate;
                        $gallery->updated_at = $post->updated_at;
                        $gallery->save();
                        $updatedCount++;
                    }
                }
            }
        }

        $this->info("Successfully synchronized {$updatedCount} gallery records.");
        return Command::SUCCESS;
    }
}
