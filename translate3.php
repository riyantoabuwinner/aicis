<?php

$replacements = [
    "Login / Daftar" => "Login / Register",
    "Kategori" => "Categories",
    "Baca Selengkapnya" => "Read More",
    "Lihat Detail" => "View Details",
];

$directories = [
    'resources/views',
    'resources/views/partials',
    'resources/views/layouts',
];

function processDirectory($dir, $replacements) {
    if (!is_dir($dir)) return;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php'])) {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
                echo "Translated: $path\n";
            }
        }
    }
}

foreach ($directories as $dir) {
    processDirectory($dir, $replacements);
}
echo "Done.\n";
