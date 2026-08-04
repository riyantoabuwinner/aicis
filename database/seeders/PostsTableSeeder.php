<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('posts')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 16,
    'category_id' => 1,
    'title' => 'AICIS 2026: Discussing Contemporary Issues in Global Islamic Studies',
    'slug' => 'aicis-2026-discussing-contemporary-issues-in-global-islamic-studies',
    'content' => '<img src="/storage/gallery/aicis_contemporary_issues.png" alt="Islamic Conference" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>The Annual International Conference on Islamic Studies (AICIS] 2026 is back, bringing together academics and researchers from various countries. This year\'s conference focuses on discussing contemporary issues relevant to the development of Islamic studies globally. It is hoped that this forum can produce resolutions and innovative thoughts to answer the challenges of the times.</p>',
    'featured_image' => 'gallery/aicis_contemporary_issues.png',
    'status' => 'Published',
    'published_at' => '2026-08-03 15:58:08',
    'hashtags' => '["#AICIS2026","#IslamicStudies","#Conference"]',
    'created_at' => '2026-08-03 15:58:08',
    'updated_at' => '2026-08-03 15:58:08',
  ],
  1 => 
  [
    'id' => 17,
    'category_id' => 1,
    'title' => 'Keynote Speakers for AICIS 2026 Officially Announced',
    'slug' => 'keynote-speakers-for-aicis-2026-officially-announced',
    'content' => '<img src="/storage/gallery/aicis_keynote_speakers.png" alt="Keynote Speakers" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>The organizing committee of AICIS 2026 has officially announced the list of keynote speakers who will enliven this year\'s conference. The speakers consist of religious figures, prominent professors, and practitioners who have made major contributions to the field of Islamic scholarship. Their presence is guaranteed to provide deep insights for all participants.</p>',
    'featured_image' => 'gallery/aicis_keynote_speakers.png',
    'status' => 'Published',
    'published_at' => '2026-08-03 15:58:08',
    'hashtags' => '["#KeynoteSpeaker","#AICIS2026","#Academics"]',
    'created_at' => '2026-08-03 15:58:08',
    'updated_at' => '2026-08-03 15:58:08',
  ],
  2 => 
  [
    'id' => 18,
    'category_id' => 1,
    'title' => 'Exploring New Paradigms in Islamic Education at AICIS',
    'slug' => 'exploring-new-paradigms-in-islamic-education-at-aicis',
    'content' => '<img src="/storage/gallery/aicis_islamic_education.png" alt="Panel Discussion" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>One of the most anticipated panel sessions at this year\'s AICIS is the discussion on new paradigms in Islamic education. This session will explore how modern technology and learning methods can be integrated with traditional Islamic values. Experts will share their experiences and case studies on educational innovation from various parts of the world.</p>',
    'featured_image' => 'gallery/aicis_islamic_education.png',
    'status' => 'Published',
    'published_at' => '2026-08-03 15:58:08',
    'hashtags' => '["#IslamicEducation","#Innovation","#AICIS2026"]',
    'created_at' => '2026-08-03 15:58:08',
    'updated_at' => '2026-08-03 15:58:08',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('posts')->insert($chunk);
        }
    }
}
