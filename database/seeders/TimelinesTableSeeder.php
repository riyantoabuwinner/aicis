<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimelinesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('timelines')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 1,
    'title' => 'Abstract Submission',
    'description' => 'Abstract Submission Period',
    'date_from' => '2026-08-10',
    'date_until' => '2026-08-30',
    'is_active' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-04 09:45:58',
    'updated_at' => '2026-08-04 09:45:58',
  ],
  1 => 
  [
    'id' => 2,
    'title' => 'Abstract Acceptance Announcement',
    'description' => 'Abstract Acceptance Announcement',
    'date_from' => '2026-09-10',
    'date_until' => '2026-09-10',
    'is_active' => 1,
    'sort_order' => 2,
    'created_at' => '2026-08-04 09:45:58',
    'updated_at' => '2026-08-04 09:45:58',
  ],
  2 => 
  [
    'id' => 3,
    'title' => 'Full Paper Submission Deadline',
    'description' => 'Final Deadline for Full Paper Submission',
    'date_from' => '2026-10-20',
    'date_until' => '2026-10-20',
    'is_active' => 1,
    'sort_order' => 3,
    'created_at' => '2026-08-04 09:45:58',
    'updated_at' => '2026-08-04 09:45:58',
  ],
  3 => 
  [
    'id' => 4,
    'title' => 'Parallel Session Distribution Announcement',
    'description' => 'Announcement for Parallel Session Distribution',
    'date_from' => '2026-10-30',
    'date_until' => '2026-10-30',
    'is_active' => 1,
    'sort_order' => 4,
    'created_at' => '2026-08-04 09:45:58',
    'updated_at' => '2026-08-04 09:45:58',
  ],
  4 => 
  [
    'id' => 5,
    'title' => 'Complete Guidebook Distribution',
    'description' => 'Distribution of the Complete Guidebook',
    'date_from' => '2026-11-10',
    'date_until' => '2026-11-10',
    'is_active' => 1,
    'sort_order' => 5,
    'created_at' => '2026-08-04 09:45:58',
    'updated_at' => '2026-08-04 09:45:58',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('timelines')->insert($chunk);
        }
    }
}
