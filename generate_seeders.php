<?php

require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$tablesToSeed = [
    "settings",
    "official_partners",
    "sliders",
    "galleries",
    "posts",
    "categories",
    "video_profiles",
    "faqs",
    "timelines"
];

foreach ($tablesToSeed as $table) {
    echo "Generating seeder for table: $table\n";
    $data = DB::table($table)->get()->map(function($item) {
        return (array) $item;
    })->toArray();
    
    $className = str_replace(" ", "", ucwords(str_replace("_", " ", $table))) . "TableSeeder";
    
    $dataString = var_export($data, true);
    // Format the var_export to look nicer
    $dataString = str_replace(["array (", ")"], ["[", "]"], $dataString);

    $stub = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse Illuminate\Support\Facades\DB;\n\nclass $className extends Seeder\n{\n    public function run()\n    {\n        DB::table('$table')->truncate();\n        \n        \$data = $dataString;\n        \n        foreach (array_chunk(\$data, 500) as \$chunk) {\n            DB::table('$table')->insert(\$chunk);\n        }\n    }\n}\n";

    File::put(database_path("seeders/$className.php"), $stub);
}

echo "Seeder generation complete.\n";