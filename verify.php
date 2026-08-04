<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$post = \App\Models\Post::find(1);
if ($post) {
    echo "Post Title: " . $post->title . "\n";
    echo "Post Category: " . ($post->category ? $post->category->name : 'None') . "\n";
} else {
    echo "Post not found.\n";
}
