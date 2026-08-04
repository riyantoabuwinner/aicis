<?php

$replacements = [
    "'Kategori'" => "'Category'",
    '"Kategori"' => '"Category"',
    "'Judul'" => "'Title'",
    '"Judul"' => '"Title"',
    "'Gambar'" => "'Image'",
    '"Gambar"' => '"Image"',
    "'Deskripsi'" => "'Description'",
    '"Deskripsi"' => '"Description"',
    "'Isi'" => "'Content'",
    '"Isi"' => '"Content"',
    "'Acara'" => "'Event'",
    '"Acara"' => '"Event"',
    "'Sub Judul'" => "'Subtitle'",
    '"Sub Judul"' => '"Subtitle"',
    "'Dibuat pada'" => "'Created at'",
    '"Dibuat pada"' => '"Created at"',
    "'Terakhir diubah'" => "'Last updated'",
    '"Terakhir diubah"' => '"Last updated"',
    "'Tanggal'" => "'Date'",
    '"Tanggal"' => '"Date"',
    "'Unggah Gambar'" => "'Upload Image'",
    '"Unggah Gambar"' => '"Upload Image"',
    "'Pilih Kategori'" => "'Select Category'",
    '"Pilih Kategori"' => '"Select Category"',
    "'Pesan'" => "'Message'",
    '"Pesan"' => '"Message"',
    "'Tulis'" => "'Write'",
    '"Tulis"' => '"Write"',
    "'Tuliskan'" => "'Write'",
    '"Tuliskan"' => '"Write"',
    "'Pilih'" => "'Select'",
    '"Pilih"' => '"Select"',
    "'Kembali'" => "'Back'",
    '"Kembali"' => '"Back"',
    "'Batal'" => "'Cancel'",
    '"Batal"' => '"Cancel"',
    "'Simpan'" => "'Save'",
    '"Simpan"' => '"Save"',
    "'Konten'" => "'Content'",
    '"Konten"' => '"Content"',
    "'Kontak'" => "'Contact'",
    '"Kontak"' => '"Contact"',
    "'Nama Lengkap'" => "'Full Name'",
    '"Nama Lengkap"' => '"Full Name"',
    "'Login / Daftar'" => "'Login / Register'",
    "'Daftar'" => "'Register'",
    '"Daftar"' => '"Register"',
];

$directories = [
    'app/Filament/Resources',
    'app/Filament/Pages',
    'resources/views',
    'resources/views/partials',
    'resources/views/layouts',
    'resources/views/filament',
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
