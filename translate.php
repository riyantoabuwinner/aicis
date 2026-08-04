<?php

$replacements = [
    "'Keputusan'" => "'Decision'",
    '"Keputusan"' => '"Decision"',
    "'Target Waktu'" => "'Target Time'",
    '"Target Waktu"' => '"Target Time"',
    
    // welcome.blade.php / layouts / etc
    "Beranda" => "Home",
    "Tentang Kami" => "About Us",
    "Jadwal Acara" => "Event Schedule",
    "Berita & Artikel" => "News & Articles",
    "Galeri" => "Gallery",
    "Kategori" => "Categories",
    "Lihat Detail" => "View Details",
    "Baca Selengkapnya" => "Read More",
    "Tanggal Pelaksanaan" => "Event Date",
    "Sisa Waktu" => "Time Remaining",
    "Hubungi Kami" => "Contact Us",
    "Kirim Pesan" => "Send Message",
    "Tulis Pesan Anda" => "Write Your Message",
    "Tuliskan pesan Anda di sini" => "Write your message here",
    "Nama Lengkap" => "Full Name",
    "Kirim Sekarang" => "Send Now",
    "Alamat" => "Address",
    "Telepon" => "Phone",
    "Buka" => "Open",
    "Bagikan:" => "Share:",
    "Pos Terbaru" => "Latest Posts",
    
    // contact.blade.php
    "Hubungi Kami" => "Contact Us",
    "Kami siap mendengar dari Anda" => "We are ready to hear from you",
    
    // form inputs
    "Masukkan nama lengkap Anda" => "Enter your full name",
    "Masukkan alamat email Anda" => "Enter your email address",
    "Masukkan subjek pesan" => "Enter message subject",
    
    // registration-success.blade.php
    "Pendaftaran Berhasil" => "Registration Successful",
    "Terima kasih telah mendaftar" => "Thank you for registering",
    "Kembali ke Beranda" => "Back to Home",
    "Tunggu konfirmasi admin" => "Please wait for admin confirmation",
    "Pendaftaran Anda sedang ditinjau" => "Your registration is being reviewed",
];

$directories = [
    'app/Filament/Resources',
    'app/Filament/Pages',
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
