<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate([
            'name' => 'News and Information',
        ], [
            'slug' => Str::slug('News and Information'),
        ]);

        $news = [
            [
                'title' => 'AICIS 2026: Discussing Contemporary Issues in Global Islamic Studies',
                'content' => '<img src="/storage/gallery/aicis_contemporary_issues.png" alt="Islamic Conference" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>The Annual International Conference on Islamic Studies (AICIS) 2026 is back, bringing together academics and researchers from various countries. This year\'s conference focuses on discussing contemporary issues relevant to the development of Islamic studies globally. It is hoped that this forum can produce resolutions and innovative thoughts to answer the challenges of the times.</p>',
                'hashtags' => ['#AICIS2026', '#IslamicStudies', '#Conference'],
            ],
            [
                'title' => 'Keynote Speakers for AICIS 2026 Officially Announced',
                'content' => '<img src="/storage/gallery/aicis_keynote_speakers.png" alt="Keynote Speakers" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>The organizing committee of AICIS 2026 has officially announced the list of keynote speakers who will enliven this year\'s conference. The speakers consist of religious figures, prominent professors, and practitioners who have made major contributions to the field of Islamic scholarship. Their presence is guaranteed to provide deep insights for all participants.</p>',
                'hashtags' => ['#KeynoteSpeaker', '#AICIS2026', '#Academics'],
            ],
            [
                'title' => 'Exploring New Paradigms in Islamic Education at AICIS',
                'content' => '<img src="/storage/gallery/aicis_islamic_education.png" alt="Panel Discussion" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;"> <p>One of the most anticipated panel sessions at this year\'s AICIS is the discussion on new paradigms in Islamic education. This session will explore how modern technology and learning methods can be integrated with traditional Islamic values. Experts will share their experiences and case studies on educational innovation from various parts of the world.</p>',
                'hashtags' => ['#IslamicEducation', '#Innovation', '#AICIS2026'],
            ],
        ];

        // Delete existing posts to recreate them properly
        Post::where('category_id', $category->id)->delete();

        foreach ($news as $item) {
            $post = new Post();
            $post->category_id = $category->id;
            $post->title = $item['title'];
            $post->slug = Str::slug($item['title']);
            $post->content = $item['content'];
            // Notice: We intentionally do NOT set featured_image here!
            $post->status = 'Published';
            $post->published_at = now();
            $post->hashtags = $item['hashtags'];
            $post->save();
        }
    }
}
