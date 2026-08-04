<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 1,
    'name' => 'News and Information',
    'slug' => 'news-and-information',
    'created_at' => '2026-07-27 18:49:37',
    'updated_at' => '2026-07-29 12:36:01',
  ],
  1 => 
  [
    'id' => 2,
    'name' => 'Pengumuman',
    'slug' => 'pengumuman',
    'created_at' => '2026-07-27 18:53:12',
    'updated_at' => '2026-07-27 18:53:12',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('categories')->insert($chunk);
        }
    }
}
