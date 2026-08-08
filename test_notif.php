<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$admins = \App\Models\User::whereIn('id', [1, 2])->get();
foreach ($admins as $admin) {
    \Filament\Notifications\Notification::make()
        ->title('System Update: Notifikasi Berhasil Diperbaiki!')
        ->body('Lonceng notifikasi Anda sekarang sudah aktif. Mulai sekarang, setiap ada pendaftar baru, notifikasinya akan muncul di sini.')
        ->icon('heroicon-o-check-circle')
        ->color('success')
        ->sendToDatabase($admin);
}
echo "Sent!";
