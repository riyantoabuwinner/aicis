<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Post;

// Translate Categories
$categories = Category::all();
foreach ($categories as $category) {
    if ($category->name === 'Berita dan Informasi') {
        $category->update([
            'name' => 'News and Information',
            'slug' => 'news-and-information'
        ]);
        echo "Translated Category: {$category->id}\n";
    }
}

// Translate Posts
$posts = Post::all();
foreach ($posts as $post) {
    if (strpos($post->title, 'Tes Berita') !== false) {
        $post->update([
            'title' => 'Test News Update',
            'slug' => 'test-news-update',
            'content' => 'This is an example of news content for testing purposes.',
        ]);
        echo "Translated Post: {$post->id}\n";
    }
}
echo "Database translation complete.\n";
