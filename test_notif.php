<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admins = \App\Models\User::role(['superadmin', 'admin'])->get();
echo "Found " . $admins->count() . " admins.\n";
foreach ($admins as $admin) {
    echo "Admin: " . $admin->email . "\n";
    \Filament\Notifications\Notification::make()
        ->title('New')
        ->sendToDatabase($admin);
}
echo "Notif Count: " . \Illuminate\Notifications\DatabaseNotification::count() . "\n";
