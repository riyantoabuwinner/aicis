<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 1,
    'logo' => 'settings/Logo AICIS (primary-logo only].png',
    'dark_logo' => 'settings/Logo AICIS (white-logo only].png',
    'favicon' => 'settings/Logo AICIS (primary-logo only] (1].png',
    'created_at' => '2026-07-27 01:39:05',
    'updated_at' => '2026-08-03 03:12:09',
    'site_title' => 'AICIS 2026',
    'site_subtitle' => 'UIN SIBER SYEKH NURJATI CIREBON',
    'address' => 'Perjuangan Street, Sunyaragi, Kesambi District, Cirebon City, West Java 45132',
    'email' => 'aicisofficial@uinssc.ac.id',
    'phone' => '08557590909',
    'google_maps_url' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.2828493082916!2d108.53108767401466!3d-6.735307265853971!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f1dfa0caae141%3A0x3b66fb4b842e1140!2sUniversitas%20Islam%20Negeri%20Siber%20Syekh%20Nurjati%20Cirebon%20(UINSSC]!5e0!3m2!1sid!2sid!4v1785234102108!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>',
    'about_title' => NULL,
    'about_content' => '<p><strong>The Annual International Conference on Islamic Studies (AICIS]</strong> is a flagship academic event organized by the Ministry of Religious Affairs of the Republic of Indonesia through the Directorate General of Islamic Education. Established in 2001, AICIS has evolved into one of the most distinguished global platforms for intellectual discourse on Islam, science, and society.&nbsp;</p><p>In an era defined by urgent global challenges—environmental crises, armed conflicts, economic disparities, and public health concerns—multidisciplinary collaboration is vital to crafting solutions rooted in justice and sustainability.</p><p>Hosted by UIN Siber Syekh Nurjati Cirebon, and proudly sponsored by the Directorate General of Islamic Education, Ministry of Religious Affairs of the Republic of Indonesia, the AICIS invites scholars, practitioners, and innovators to explore the intersections of ecotheology, technological advancements, and Islamic scholarship.</p>',
    'about_button_url' => NULL,
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => '587',
    'smtp_username' => 'aicisofficial@uinssc.ac.id',
    'smtp_password' => 'puqv zydq mmaq qryq',
    'smtp_encryption' => 'tls',
    'mail_from_address' => 'aicisofficial@uinssc.ac.id',
    'mail_from_name' => 'AICIS Official UINSSC 2026',
    'whatsapp_number' => '08557590909',
    'whatsapp_api_key' => NULL,
    'facebook_url' => NULL,
    'twitter_url' => NULL,
    'instagram_url' => NULL,
    'youtube_url' => NULL,
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('settings')->insert($chunk);
        }
    }
}
