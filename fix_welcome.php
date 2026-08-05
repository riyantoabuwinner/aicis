<?php
$content = file_get_contents('resources/views/welcome.blade.php');
$index = strpos($content, '<!-- Floating Bubble Menu -->');
if ($index !== false) {
    $newContent = substr($content, 0, $index);
    $newContent .= "@include('partials.floating-bubble')\n@endsection\n";
    file_put_contents('resources/views/welcome.blade.php', $newContent);
    echo "Successfully replaced in welcome.blade.php\n";
} else {
    echo "Could not find Floating Bubble Menu\n";
}
