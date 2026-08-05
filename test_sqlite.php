<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config(['database.default' => 'sqlite']);
$papers = \App\Models\PaperSubmission::all()->toArray();
print_r($papers);
