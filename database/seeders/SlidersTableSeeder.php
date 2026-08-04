<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlidersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sliders')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 2,
    'title' => NULL,
    'subtitle' => NULL,
    'media_type' => 'image',
    'media_path' => 'sliders/01KYNZM2856A1TA2VJHRSHQF23.webp',
    'button_text' => NULL,
    'button_url' => NULL,
    'is_active' => 1,
    'sort_order' => 0,
    'created_at' => '2026-07-29 03:45:18',
    'updated_at' => '2026-07-29 03:46:02',
  ],
  1 => 
  [
    'id' => 3,
    'title' => NULL,
    'subtitle' => NULL,
    'media_type' => 'image',
    'media_path' => 'sliders/01KYNZMRN4XWCJSJYJ7BG1921K.webp',
    'button_text' => NULL,
    'button_url' => NULL,
    'is_active' => 1,
    'sort_order' => 0,
    'created_at' => '2026-07-29 03:45:41',
    'updated_at' => '2026-07-29 03:46:11',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('sliders')->insert($chunk);
        }
    }
}
