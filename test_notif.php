<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = \App\Models\User::first();
if ($u) {
    \Filament\Notifications\Notification::make()->title('Test')->sendToDatabase($u);
    echo "Notification sent. Total in DB: " . \Illuminate\Notifications\DatabaseNotification::count() . "\n";
} else {
    echo "No user found.\n";
}
