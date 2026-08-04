<?php
$files = glob('app/Filament/Resources/*.php');
foreach($files as $f) {
    $c = file_get_contents($f);
    preg_match_all('/->navigationLabel\([\'"](.*?)[\'"]\)/', $c, $m1);
    preg_match_all('/->pluralLabel\([\'"](.*?)[\'"]\)/', $c, $m2);
    preg_match_all('/->navigationGroup\([\'"](.*?)[\'"]\)/', $c, $m3);
    preg_match_all('/->placeholder\([\'"](.*?)[\'"]\)/', $c, $m4);
    
    $all = array_merge($m1[1], $m2[1], $m3[1], $m4[1]);
    if(!empty($all)) {
        echo basename($f) . ": " . implode(', ', $all) . "\n";
    }
}
