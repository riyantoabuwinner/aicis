<?php
$dir = __DIR__ . "/app/Filament/Resources";
$files = scandir($dir);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        $path = $dir . "/" . $file;
        $content = file_get_contents($path);
        if (strpos($content, "public static function canAccess(): bool") !== false) continue;
        $roles = "[\"superadmin\", \"admin\"]";
        if ($file === "MyPaperSubmissionResource.php") $roles = "[\"superadmin\", \"admin\", \"author\"]";
        elseif ($file === "PaperReviewResource.php") $roles = "[\"superadmin\", \"admin\", \"reviewer\"]";
        $injection = "\n    public static function canAccess(): bool\n    {\n        return auth()->user()->hasRole($roles);\n    }\n";
        $pattern = "/(protected static \?string \\$model = [^;]+;)/";
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, "$1" . $injection, $content);
            file_put_contents($path, $content);
            echo "Patched $file\n";
        }
    }
}

