<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => database_path('database.sqlite')
]);

try {
    $papers = \App\Models\PaperSubmission::all()->toArray();
    echo "Count: " . count($papers) . "\n";
    if (count($papers) > 0) {
        print_r($papers[0]);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
