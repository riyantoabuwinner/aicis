<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalleriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('galleries')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 8,
    'file_path' => 'gallery/aicis_contemporary_issues.png',
    'caption' => 'AICIS 2026: Discussing Contemporary Issues in Global Islamic Studies',
    'created_at' => '2026-08-03 15:49:56',
    'updated_at' => '2026-08-03 15:49:56',
  ],
  1 => 
  [
    'id' => 9,
    'file_path' => 'gallery/aicis_keynote_speakers.png',
    'caption' => 'Keynote Speakers for AICIS 2026 Officially Announced',
    'created_at' => '2026-08-03 15:58:08',
    'updated_at' => '2026-08-03 15:58:08',
  ],
  2 => 
  [
    'id' => 10,
    'file_path' => 'gallery/aicis_islamic_education.png',
    'caption' => 'Exploring New Paradigms in Islamic Education at AICIS',
    'created_at' => '2026-08-03 15:58:08',
    'updated_at' => '2026-08-03 15:58:08',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('galleries')->insert($chunk);
        }
    }
}
