<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndUserSeeder::class,
            NewsSeeder::class,
            TimelineSeeder::class,
            SettingsTableSeeder::class,
            OfficialPartnersTableSeeder::class,
            SlidersTableSeeder::class,
            GalleriesTableSeeder::class,
            PostsTableSeeder::class,
            CategoriesTableSeeder::class,
            VideoProfilesTableSeeder::class,
            FaqsTableSeeder::class,
            TimelinesTableSeeder::class,
        ]);
    }
}
