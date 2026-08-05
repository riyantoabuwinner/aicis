<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::select('select count(*) as c from users');
print_r($users);
$papers = \Illuminate\Support\Facades\DB::select('select count(*) as c from paper_submissions');
print_r($papers);
