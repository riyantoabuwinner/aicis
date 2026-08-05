<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $url = 'https://raw.githubusercontent.com/mtegarsantosa/json-nama-daerah-indonesia/master/regions.json';
        
        $this->command->info("Fetching region data from $url...");

        $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ])
            ->get($url);

        if (!$response->successful()) {
            $this->command->error("Failed to download region data. HTTP Status: " . $response->status());
            return;
        }

        $data = $response->json();

        if (empty($data)) {
            $this->command->error("Failed to parse JSON or JSON is empty.");
            return;
        }

        $this->command->info('Parsing and inserting region data...');

        foreach ($data as $item) {
            $provName = trim($item['provinsi'] ?? '');
            
            if (empty($provName)) continue;
            
            $province = \App\Models\Province::firstOrCreate(['name' => $provName]);
            
            if (!empty($item['kota']) && is_array($item['kota'])) {
                foreach ($item['kota'] as $cityName) {
                    $cityName = trim($cityName);
                    if (empty($cityName)) continue;
                    
                    \App\Models\City::firstOrCreate([
                        'province_id' => $province->id,
                        'name' => $cityName
                    ]);
                }
            }
        }

        $this->command->info('Provinces and Cities seeded successfully.');
    }
}
