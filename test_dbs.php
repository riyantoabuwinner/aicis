<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dbs = \Illuminate\Support\Facades\DB::select('SHOW DATABASES');
print_r($dbs);
