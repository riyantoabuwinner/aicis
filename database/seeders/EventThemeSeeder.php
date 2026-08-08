<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventTheme;

class EventThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $description = '
            <p style="font-size: 1.1rem; line-height: 1.8; color: #444; margin-bottom: 20px;">
                In an era defined by urgent global challenges, multidisciplinary collaboration is vital to crafting solutions rooted in justice and sustainability. Hosted by UIN Siber Syekh Nurjati Cirebon, the AICIS invites scholars, practitioners, and innovators to explore the intersections of ecotheology, technological advancements, and Islamic scholarship.
            </p>
            <h3 style="font-size: 1.5rem; color: #1b5e20; margin-top: 30px; margin-bottom: 15px; font-weight: 700;">
                <i class="fas fa-leaf" style="color: #dfb162; margin-right: 10px;"></i> Key Focus Areas
            </h3>
            <ul style="list-style-type: none; padding-left: 0;">
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <i class="fas fa-check-circle" style="color: #1b5e20; position: absolute; left: 0; top: 4px;"></i>
                    <strong>Ecotheology:</strong> Addressing environmental crises through Islamic perspectives.
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <i class="fas fa-check-circle" style="color: #1b5e20; position: absolute; left: 0; top: 4px;"></i>
                    <strong>Technological Innovations:</strong> Leveraging AI and digital ethics in Islamic studies.
                </li>
                <li style="margin-bottom: 12px; padding-left: 25px; position: relative;">
                    <i class="fas fa-check-circle" style="color: #1b5e20; position: absolute; left: 0; top: 4px;"></i>
                    <strong>Global Justice:</strong> Strategies for economic disparities and human rights.
                </li>
            </ul>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #444; margin-top: 25px;">
                Join us in shaping the future of global Islamic scholarship and contributing to a more sustainable and equitable world for generations to come.
            </p>
        ';

        EventTheme::updateOrCreate(
            ['title' => 'Ecotheology and Technological Advancements in Global Islamic Scholarship'],
            [
                'description' => $description,
                'is_active' => true,
                'sort_order' => 1
            ]
        );
    }
}
