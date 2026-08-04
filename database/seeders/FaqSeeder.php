<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            ['question' => '1. What is AICIS 2026?', 'answer' => '<p>The 25th Annual International Conference on Islamic Studies (AICIS) 2026 is an international academic conference hosted by UIN Siber Syekh Nurjati Cirebon. The conference serves as a global forum for scholars, researchers, practitioners, policymakers, and students to discuss contemporary issues in Islamic Studies and interdisciplinary research.</p>'],
            ['question' => '2. Who organizes AICIS 2026?', 'answer' => '<p>AICIS 2026 is organized by UIN Siber Syekh Nurjati Cirebon in collaboration with the Ministry of Religious Affairs of the Republic of Indonesia and national as well as international academic partners.</p>'],
            ['question' => '3. When and where will AICIS 2026 be held?', 'answer' => '<p>The conference will be held in Cirebon, West Java, Indonesia, hosted by UIN Siber Syekh Nurjati Cirebon. The detailed schedule, venue, and agenda will be announced through the official website.</p>'],
            ['question' => '4. What is the official theme of AICIS 2026?', 'answer' => '<p>The official conference theme will be announced through the Call for Papers.</p>'],
            ['question' => '5. Who can participate?', 'answer' => '<p>AICIS welcomes:</p><ul><li>Academics</li><li>Researchers</li><li>Lecturers</li><li>Students</li><li>Practitioners</li><li>Government officials</li><li>NGOs</li><li>International participants</li></ul>'],
            ['question' => '6. Can I attend without presenting a paper?', 'answer' => '<p>Yes.</p><p>Participants may register as:</p><ul><li>Presenter</li><li>Non-Presenter</li><li>Listener</li></ul>'],
            ['question' => '7. How do I register?', 'answer' => '<p>You can register online by:</p><ul><li>Creating an account.</li><li>Completing your profile.</li><li>Selecting your participation category.</li><li>Uploading your paper (for presenters).</li><li>Completing payment (if applicable).</li></ul>'],
            ['question' => '8. Is there a registration fee?', 'answer' => '<p>Yes.</p><p>Registration fees vary depending on participant category and nationality. Please refer to the Registration page for the latest information.</p>'],
            ['question' => '9. What topics are accepted?', 'answer' => '<p>The conference accepts papers related to Islamic Studies and interdisciplinary research, including:</p><ul><li>Qur\'anic Studies</li><li>Hadith</li><li>Islamic Law</li><li>Islamic Education</li><li>Islamic Economics</li><li>Digital Islam</li><li>Artificial Intelligence</li><li>Islamic Finance</li><li>Social Sciences</li><li>Humanities</li><li>Environmental Studies</li><li>Gender Studies</li><li>Peace Studies</li></ul>'],
            ['question' => '10. What language should I use?', 'answer' => '<p>Papers may be submitted in:</p><ul><li>English</li><li>Arabic</li><li>Indonesian (if permitted by the conference policy)</li></ul>'],
            ['question' => '11. What file format should I submit?', 'answer' => '<p>Normally:</p><ul><li>Microsoft Word (.docx)</li><li>PDF (optional)</li></ul><p>Please follow the official template.</p>'],
            ['question' => '12. Is there a paper template?', 'answer' => '<p>Yes.</p><p>The official template will be available on the Download page.</p>'],
            ['question' => '13. What is the maximum similarity index?', 'answer' => '<p>Submitted papers should comply with the conference plagiarism policy.</p><p>Similarity checking will be conducted before peer review.</p>'],
            ['question' => '14. How are papers reviewed?', 'answer' => '<p>All eligible submissions undergo a Double-Blind Peer Review conducted by expert reviewers.</p>'],
            ['question' => '15. What are the possible review decisions?', 'answer' => '<p>The review outcomes include:</p><ul><li>Accepted</li><li>Minor Revision</li><li>Major Revision</li><li>Rejected</li></ul>'],
            ['question' => '16. How long does the review process take?', 'answer' => '<p>Review duration depends on the number of submissions but generally takes several weeks.</p>'],
            ['question' => '17. How long is each presentation?', 'answer' => '<p>Generally:</p><ul><li>Presentation: 10–15 minutes</li><li>Discussion: 5–10 minutes</li></ul>'],
            ['question' => '18. Can I present online?', 'answer' => '<p>If hybrid participation is provided, presenters may choose either:</p><ul><li>On-site Presentation</li><li>Online Presentation</li></ul><p>Subject to conference policy.</p>'],
            ['question' => '19. What presentation format should I use?', 'answer' => '<p>PowerPoint (.pptx) is recommended.</p><p>A presentation template may be provided.</p>'],
            ['question' => '20. Will all accepted papers be published?', 'answer' => '<p>Accepted papers that meet publication requirements will be included in the conference proceedings.</p>'],
            ['question' => '21. Will selected papers be recommended to journals?', 'answer' => '<p>Yes.</p><p>Outstanding papers may be recommended to partner journals after an additional review process.</p><p>Publication is subject to each journal\'s editorial policy.</p>'],
            ['question' => '22. Will papers receive a DOI?', 'answer' => '<p>Proceedings published by the conference publisher are generally assigned Digital Object Identifiers (DOIs), subject to the publisher\'s policy.</p>'],
            ['question' => '23. Will participants receive a certificate?', 'answer' => '<p>Yes.</p><p>Electronic certificates (e-Certificates) will be issued for:</p><ul><li>Presenter</li><li>Participant</li><li>Reviewer</li><li>Committee</li><li>Keynote Speaker</li></ul>'],
            ['question' => '24. When can I download my certificate?', 'answer' => '<p>Certificates will be available after the conference through the participant dashboard.</p>'],
            ['question' => '25. Can the committee provide an invitation letter?', 'answer' => '<p>Yes.</p><p>Registered international participants may request an official invitation letter for visa purposes.</p>'],
            ['question' => '26. Does the committee arrange accommodation?', 'answer' => '<p>The organizing committee will provide information about recommended hotels near the conference venue.</p><p>Participants are responsible for their own accommodation unless otherwise specified.</p>'],
            ['question' => '27. I forgot my password. What should I do?', 'answer' => '<p>Use the Forgot Password feature on the login page.</p>'],
            ['question' => '28. My paper upload failed.', 'answer' => '<p>Please ensure:</p><ul><li>File size complies with the limit.</li><li>File format is correct.</li><li>Internet connection is stable.</li></ul><p>If the problem persists, contact the Help Desk.</p>'],
            ['question' => '29. How do I update my profile?', 'answer' => '<p>Login to your dashboard and select My Profile.</p>'],
            ['question' => '30. How can I contact the organizing committee?', 'answer' => '<p>Please contact us through:</p><ul><li>Email</li><li>Contact Form</li><li>WhatsApp Helpdesk</li><li>Official Social Media</li></ul><p>Details will be published on the Contact Us page.</p>'],
        ];

        \App\Models\Faq::truncate();
        foreach ($faqs as $index => $faq) {
            \App\Models\Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
