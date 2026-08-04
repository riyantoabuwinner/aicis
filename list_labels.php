<?php
$files = glob('app/Filament/Resources/*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    preg_match_all('/->label\([\'"](.*?)[\'"]\)/', $c, $m);
    if(!empty($m[1])) {
        echo basename($f) . ": " . implode(', ', $m[1]) . "\n";
    }
}
