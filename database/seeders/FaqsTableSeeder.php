<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('faqs')->truncate();
        
        $data = [
  0 => 
  [
    'id' => 1,
    'question' => '1. What is AICIS 2026?',
    'answer' => '<p>The 25th Annual International Conference on Islamic Studies (AICIS] 2026 is an international academic conference hosted by UIN Siber Syekh Nurjati Cirebon. The conference serves as a global forum for scholars, researchers, practitioners, policymakers, and students to discuss contemporary issues in Islamic Studies and interdisciplinary research.</p>',
    'is_active' => 1,
    'sort_order' => 1,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  1 => 
  [
    'id' => 2,
    'question' => '2. Who organizes AICIS 2026?',
    'answer' => '<p>AICIS 2026 is organized by UIN Siber Syekh Nurjati Cirebon in collaboration with the Ministry of Religious Affairs of the Republic of Indonesia and national as well as international academic partners.</p>',
    'is_active' => 1,
    'sort_order' => 2,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  2 => 
  [
    'id' => 3,
    'question' => '3. When and where will AICIS 2026 be held?',
    'answer' => '<p>The conference will be held in Cirebon, West Java, Indonesia, hosted by UIN Siber Syekh Nurjati Cirebon. The detailed schedule, venue, and agenda will be announced through the official website.</p>',
    'is_active' => 1,
    'sort_order' => 3,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  3 => 
  [
    'id' => 4,
    'question' => '4. What is the official theme of AICIS 2026?',
    'answer' => '<p>The official conference theme will be announced through the Call for Papers.</p>',
    'is_active' => 1,
    'sort_order' => 4,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  4 => 
  [
    'id' => 5,
    'question' => '5. Who can participate?',
    'answer' => '<p>AICIS welcomes:</p><ul><li>Academics</li><li>Researchers</li><li>Lecturers</li><li>Students</li><li>Practitioners</li><li>Government officials</li><li>NGOs</li><li>International participants</li></ul>',
    'is_active' => 1,
    'sort_order' => 5,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  5 => 
  [
    'id' => 6,
    'question' => '6. Can I attend without presenting a paper?',
    'answer' => '<p>Yes.</p><p>Participants may register as:</p><ul><li>Presenter</li><li>Non-Presenter</li><li>Listener</li></ul>',
    'is_active' => 1,
    'sort_order' => 6,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  6 => 
  [
    'id' => 7,
    'question' => '7. How do I register?',
    'answer' => '<p>You can register online by:</p><ul><li>Creating an account.</li><li>Completing your profile.</li><li>Selecting your participation category.</li><li>Uploading your paper (for presenters].</li><li>Completing payment (if applicable].</li></ul>',
    'is_active' => 1,
    'sort_order' => 7,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  7 => 
  [
    'id' => 8,
    'question' => '8. Is there a registration fee?',
    'answer' => '<p>Yes.</p><p>Registration fees vary depending on participant category and nationality. Please refer to the Registration page for the latest information.</p>',
    'is_active' => 1,
    'sort_order' => 8,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  8 => 
  [
    'id' => 9,
    'question' => '9. What topics are accepted?',
    'answer' => '<p>The conference accepts papers related to Islamic Studies and interdisciplinary research, including:</p><ul><li>Qur\'anic Studies</li><li>Hadith</li><li>Islamic Law</li><li>Islamic Education</li><li>Islamic Economics</li><li>Digital Islam</li><li>Artificial Intelligence</li><li>Islamic Finance</li><li>Social Sciences</li><li>Humanities</li><li>Environmental Studies</li><li>Gender Studies</li><li>Peace Studies</li></ul>',
    'is_active' => 1,
    'sort_order' => 9,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  9 => 
  [
    'id' => 10,
    'question' => '10. What language should I use?',
    'answer' => '<p>Papers may be submitted in:</p><ul><li>English</li><li>Arabic</li><li>Indonesian (if permitted by the conference policy]</li></ul>',
    'is_active' => 1,
    'sort_order' => 10,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  10 => 
  [
    'id' => 11,
    'question' => '11. What file format should I submit?',
    'answer' => '<p>Normally:</p><ul><li>Microsoft Word (.docx]</li><li>PDF (optional]</li></ul><p>Please follow the official template.</p>',
    'is_active' => 1,
    'sort_order' => 11,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  11 => 
  [
    'id' => 12,
    'question' => '12. Is there a paper template?',
    'answer' => '<p>Yes.</p><p>The official template will be available on the Download page.</p>',
    'is_active' => 1,
    'sort_order' => 12,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  12 => 
  [
    'id' => 13,
    'question' => '13. What is the maximum similarity index?',
    'answer' => '<p>Submitted papers should comply with the conference plagiarism policy.</p><p>Similarity checking will be conducted before peer review.</p>',
    'is_active' => 1,
    'sort_order' => 13,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  13 => 
  [
    'id' => 14,
    'question' => '14. How are papers reviewed?',
    'answer' => '<p>All eligible submissions undergo a Double-Blind Peer Review conducted by expert reviewers.</p>',
    'is_active' => 1,
    'sort_order' => 14,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  14 => 
  [
    'id' => 15,
    'question' => '15. What are the possible review decisions?',
    'answer' => '<p>The review outcomes include:</p><ul><li>Accepted</li><li>Minor Revision</li><li>Major Revision</li><li>Rejected</li></ul>',
    'is_active' => 1,
    'sort_order' => 15,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  15 => 
  [
    'id' => 16,
    'question' => '16. How long does the review process take?',
    'answer' => '<p>Review duration depends on the number of submissions but generally takes several weeks.</p>',
    'is_active' => 1,
    'sort_order' => 16,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  16 => 
  [
    'id' => 17,
    'question' => '17. How long is each presentation?',
    'answer' => '<p>Generally:</p><ul><li>Presentation: 10–15 minutes</li><li>Discussion: 5–10 minutes</li></ul>',
    'is_active' => 1,
    'sort_order' => 17,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  17 => 
  [
    'id' => 18,
    'question' => '18. Can I present online?',
    'answer' => '<p>If hybrid participation is provided, presenters may choose either:</p><ul><li>On-site Presentation</li><li>Online Presentation</li></ul><p>Subject to conference policy.</p>',
    'is_active' => 1,
    'sort_order' => 18,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  18 => 
  [
    'id' => 19,
    'question' => '19. What presentation format should I use?',
    'answer' => '<p>PowerPoint (.pptx] is recommended.</p><p>A presentation template may be provided.</p>',
    'is_active' => 1,
    'sort_order' => 19,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  19 => 
  [
    'id' => 20,
    'question' => '20. Will all accepted papers be published?',
    'answer' => '<p>Accepted papers that meet publication requirements will be included in the conference proceedings.</p>',
    'is_active' => 1,
    'sort_order' => 20,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  20 => 
  [
    'id' => 21,
    'question' => '21. Will selected papers be recommended to journals?',
    'answer' => '<p>Yes.</p><p>Outstanding papers may be recommended to partner journals after an additional review process.</p><p>Publication is subject to each journal\'s editorial policy.</p>',
    'is_active' => 1,
    'sort_order' => 21,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  21 => 
  [
    'id' => 22,
    'question' => '22. Will papers receive a DOI?',
    'answer' => '<p>Proceedings published by the conference publisher are generally assigned Digital Object Identifiers (DOIs], subject to the publisher\'s policy.</p>',
    'is_active' => 1,
    'sort_order' => 22,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  22 => 
  [
    'id' => 23,
    'question' => '23. Will participants receive a certificate?',
    'answer' => '<p>Yes.</p><p>Electronic certificates (e-Certificates] will be issued for:</p><ul><li>Presenter</li><li>Participant</li><li>Reviewer</li><li>Committee</li><li>Keynote Speaker</li></ul>',
    'is_active' => 1,
    'sort_order' => 23,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  23 => 
  [
    'id' => 24,
    'question' => '24. When can I download my certificate?',
    'answer' => '<p>Certificates will be available after the conference through the participant dashboard.</p>',
    'is_active' => 1,
    'sort_order' => 24,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  24 => 
  [
    'id' => 25,
    'question' => '25. Can the committee provide an invitation letter?',
    'answer' => '<p>Yes.</p><p>Registered international participants may request an official invitation letter for visa purposes.</p>',
    'is_active' => 1,
    'sort_order' => 25,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  25 => 
  [
    'id' => 26,
    'question' => '26. Does the committee arrange accommodation?',
    'answer' => '<p>The organizing committee will provide information about recommended hotels near the conference venue.</p><p>Participants are responsible for their own accommodation unless otherwise specified.</p>',
    'is_active' => 1,
    'sort_order' => 26,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  26 => 
  [
    'id' => 27,
    'question' => '27. I forgot my password. What should I do?',
    'answer' => '<p>Use the Forgot Password feature on the login page.</p>',
    'is_active' => 1,
    'sort_order' => 27,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  27 => 
  [
    'id' => 28,
    'question' => '28. My paper upload failed.',
    'answer' => '<p>Please ensure:</p><ul><li>File size complies with the limit.</li><li>File format is correct.</li><li>Internet connection is stable.</li></ul><p>If the problem persists, contact the Help Desk.</p>',
    'is_active' => 1,
    'sort_order' => 28,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  28 => 
  [
    'id' => 29,
    'question' => '29. How do I update my profile?',
    'answer' => '<p>Login to your dashboard and select My Profile.</p>',
    'is_active' => 1,
    'sort_order' => 29,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
  29 => 
  [
    'id' => 30,
    'question' => '30. How can I contact the organizing committee?',
    'answer' => '<p>Please contact us through:</p><ul><li>Email</li><li>Contact Form</li><li>WhatsApp Helpdesk</li><li>Official Social Media</li></ul><p>Details will be published on the Contact Us page.</p>',
    'is_active' => 1,
    'sort_order' => 30,
    'created_at' => '2026-08-03 03:36:00',
    'updated_at' => '2026-08-03 03:36:00',
  ],
];
        
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table('faqs')->insert($chunk);
        }
    }
}
