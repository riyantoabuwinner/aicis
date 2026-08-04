<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Timeline;
use Carbon\Carbon;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Timeline::truncate();

        $timelines = [
            [
                'title' => 'Abstract Submission',
                'description' => 'Abstract Submission Period',
                'date_from' => Carbon::create(date('Y'), 8, 10)->format('Y-m-d'),
                'date_until' => Carbon::create(date('Y'), 8, 30)->format('Y-m-d'),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Abstract Acceptance Announcement',
                'description' => 'Abstract Acceptance Announcement',
                'date_from' => Carbon::create(date('Y'), 9, 10)->format('Y-m-d'),
                'date_until' => Carbon::create(date('Y'), 9, 10)->format('Y-m-d'),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Full Paper Submission Deadline',
                'description' => 'Final Deadline for Full Paper Submission',
                'date_from' => Carbon::create(date('Y'), 10, 20)->format('Y-m-d'),
                'date_until' => Carbon::create(date('Y'), 10, 20)->format('Y-m-d'),
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Parallel Session Distribution Announcement',
                'description' => 'Announcement for Parallel Session Distribution',
                'date_from' => Carbon::create(date('Y'), 10, 30)->format('Y-m-d'),
                'date_until' => Carbon::create(date('Y'), 10, 30)->format('Y-m-d'),
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Complete Guidebook Distribution',
                'description' => 'Distribution of the Complete Guidebook',
                'date_from' => Carbon::create(date('Y'), 11, 10)->format('Y-m-d'),
                'date_until' => Carbon::create(date('Y'), 11, 10)->format('Y-m-d'),
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($timelines as $timeline) {
            Timeline::create($timeline);
        }
    }
}
