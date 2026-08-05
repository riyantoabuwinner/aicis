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
        $url = 'https://raw.githubusercontent.com/Kristories/kodepos/master/kodepos.json';
        $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
            ->withHeaders(['User-Agent' => 'Laravel'])
            ->get($url);

        if (!$response->successful()) {
            $this->command->error('Failed to download region data: ' . $response->status());
            return;
        }

        $json = $response->body();

        $data = json_decode($json, true);
        if (!is_array($data)) {
            $this->command->error('Invalid JSON data.');
            return;
        }

        $this->command->info('Parsing and inserting region data...');

        // Extract unique provinces
        $provinces = [];

        foreach ($data as $kodepos => $item) {
            $provName = trim($item['provinsi'] ?? '');
            $cityName = trim($item['kabupaten'] ?? '');
            $kodepos = trim((string)$kodepos);

            if (empty($provName) || empty($cityName)) continue;

            if (!isset($provinces[$provName])) {
                $provinces[$provName] = [];
            }
            if (!isset($provinces[$provName][$cityName])) {
                $provinces[$provName][$cityName] = [];
            }
            if (!in_array($kodepos, $provinces[$provName][$cityName])) {
                $provinces[$provName][$cityName][] = $kodepos;
            }
        }

        foreach ($provinces as $provName => $provCities) {
            $province = \App\Models\Province::firstOrCreate(['name' => $provName]);
            
            foreach ($provCities as $cityName => $codes) {
                $city = \App\Models\City::firstOrCreate([
                    'province_id' => $province->id,
                    'name' => $cityName
                ]);
                
                foreach ($codes as $code) {
                    \App\Models\PostalCode::firstOrCreate([
                        'city_id' => $city->id,
                        'postal_code' => $code
                    ]);
                }
            }
        }

        $this->command->info('Region data seeded successfully.');
    }
}
