<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaperSubmission;
use App\Models\User;
use App\Models\Conference;
use App\Models\ConferenceTrack;
use Illuminate\Support\Facades\Hash;

class PaperSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => 'ryankhalifah@gmail.com'],
            [
                'name' => 'Ryan Khalifah',
                'password' => Hash::make('password'),
                // add other required fields if necessary
            ]
        );

        // Ensure there is at least one conference
        $conference = Conference::first();
        if (!$conference) {
            $conference = Conference::create([
                'name' => 'AICIS Conference ' . date('Y'),
                'theme' => 'Annual International Conference on Islamic Studies',
                'venue' => 'UIN Sunan Ampel Surabaya',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(1)->addDays(3),
                'is_active' => true,
            ]);
        }

        // Ensure there is at least one conference track
        $track = ConferenceTrack::where('conference_id', $conference->id)->first();
        if (!$track) {
            $track = ConferenceTrack::create([
                'conference_id' => $conference->id,
                'name' => 'Islamic Studies and Modernity',
                'description' => 'Track for Islamic Studies',
            ]);
        }

        // Create the paper submission
        PaperSubmission::create([
            'conference_id' => $conference->id,
            'conference_track_id' => $track->id,
            'user_id' => $user->id,
            'title' => 'The Role of Islamic Values in Modern Society',
            'abstract' => 'This paper explores the intersection of traditional Islamic values and modern societal norms, focusing on how integration can lead to harmonious development.',
            'keywords' => 'Islamic Values, Modernity, Society, Integration',
            'status' => 'submitted', // Or 'under_review', 'accepted', 'rejected'
            'publication_status' => 'pending',
            // 'full_paper_path' => null,
            // 'presentation_file_path' => null,
            // 'payment_proof_path' => null,
        ]);
    }
}
