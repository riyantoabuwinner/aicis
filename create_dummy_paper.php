<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure we have a user
$user = \App\Models\User::first();
if (!$user) {
    $user = \App\Models\User::factory()->create([
        'name' => 'Ryan Khalifah',
        'email' => 'ryan@example.com',
        'password' => bcrypt('password')
    ]);
}

// Ensure we have a conference
$conference = \App\Models\Conference::first();
if (!$conference) {
    $conference = \App\Models\Conference::create([
        'name' => 'AICIS Conference 2026',
        'description' => 'Test',
        'start_date' => now(),
        'end_date' => now()->addDays(3),
        'location' => 'Test',
        'is_active' => true
    ]);
}

// Ensure we have a track
$track = \App\Models\ConferenceTrack::first();
if (!$track) {
    $track = \App\Models\ConferenceTrack::create([
        'conference_id' => $conference->id,
        'name' => 'Islamic Studies and Modernity',
        'description' => 'Test'
    ]);
}

// Create a dummy paper submission
$paper = \App\Models\PaperSubmission::create([
    'user_id' => $user->id,
    'conference_id' => $conference->id,
    'conference_track_id' => $track->id,
    'title' => 'The Role of Islamic Values in Modern Society',
    'abstract' => 'This is a test abstract.',
    'keywords' => 'Islamic, Modernity, Society',
    'status' => 'submitted',
]);

echo "Created PaperSubmission ID: " . $paper->id . "\n";
