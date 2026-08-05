<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Datlechin\FilamentMenuBuilder\Models\Menu;
use Datlechin\FilamentMenuBuilder\Models\MenuLocation;
use Datlechin\FilamentMenuBuilder\Models\MenuItem;

// The plugin datlechin/filament-menu-builder stores locations in a different way or in the menu model directly depending on version.
// Let's just create menus and use locations if available, otherwise just use handles/slugs.

try {
    $mainMenu = Menu::create([
        'name' => 'Main Menu',
        'slug' => 'main-menu', // Usually it has slug or handle
    ]);

    $mainMenuItems = [
        ['title' => 'Home', 'url' => '/', 'type' => 'custom', 'menu_id' => $mainMenu->id, 'order' => 1],
        ['title' => 'About', 'url' => '/#about', 'type' => 'custom', 'menu_id' => $mainMenu->id, 'order' => 2],
        ['title' => 'History', 'url' => '/#history', 'type' => 'custom', 'menu_id' => $mainMenu->id, 'order' => 3],
        ['title' => 'Contact', 'url' => '/contact', 'type' => 'custom', 'menu_id' => $mainMenu->id, 'order' => 4],
    ];
    foreach($mainMenuItems as $item) {
        MenuItem::create($item);
    }
    
    Menu::create(['name' => 'Top Menu', 'slug' => 'top-menu']);
    Menu::create(['name' => 'Secondary Menu', 'slug' => 'secondary-menu']);
    Menu::create(['name' => 'Footer Menu', 'slug' => 'footer-menu']);

    echo "Default menus created successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // Let's try raw DB insert if models fail or have different structure
    try {
        $id = \Illuminate\Support\Facades\DB::table('menus')->insertGetId([
            'name' => 'Main Menu',
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \Illuminate\Support\Facades\DB::table('menus')->insert([
            ['name' => 'Top Menu', 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Secondary Menu', 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Footer Menu', 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        \Illuminate\Support\Facades\DB::table('menu_items')->insert([
            ['menu_id' => $id, 'title' => 'Home', 'url' => '/', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['menu_id' => $id, 'title' => 'About', 'url' => '/#about', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['menu_id' => $id, 'title' => 'History', 'url' => '/#history', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['menu_id' => $id, 'title' => 'Contact', 'url' => '/contact', 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
        echo "Default menus created via DB facade.\n";
    } catch (\Exception $e2) {
        echo "DB Error: " . $e2->getMessage() . "\n";
    }
}
