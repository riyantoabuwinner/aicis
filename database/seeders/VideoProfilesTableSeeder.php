<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('video_profiles')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 2,
    'title' => 'Profil 1',
    'youtube_url' => 'https://www.youtube.com/watch?v=1wmtTmSGxXI&t=43s',
    'is_active' => 1,
    'sort_order' => 2,
    'created_at' => '2026-07-29 01:40:21',
    'updated_at' => '2026-07-29 03:05:23',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('video_profiles')->insert($chunk);
        }
    }
}
