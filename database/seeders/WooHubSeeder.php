<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\InternalRequest;
use App\Models\Question;
use App\Models\Submission;
use App\Models\User;
use App\Models\WooRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WooHubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $burger = User::firstOrCreate(
            ['email' => 'burger@example.com'],
            [
                'name' => 'Test Burger',
                'password' => Hash::make('password'),
                'role' => 'burger',
                'email_verified_at' => now(),
            ]
        );

        $caseManager = User::firstOrCreate(
            ['email' => 'casemanager@example.com'],
            [
                'name' => 'Test Case Manager',
                'password' => Hash::make('password'),
                'role' => 'case_manager',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'colleague@example.com'],
            [
                'name' => 'Test Colleague',
                'password' => Hash::make('password'),
                'role' => 'colleague',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Created test users:');
        $this->command->info('  - Burger: burger@example.com / password');
        $this->command->info('  - Case Manager: casemanager@example.com / password');
        $this->command->info('  - Colleague: colleague@example.com / password');

        // Create WOO requests
        $wooRequest1 = WooRequest::create([
            'user_id' => $burger->id,
            'case_manager_id' => $caseManager->id,
            'title' => 'Verzoek tot openbaarmaking van documenten over klimaatbeleid',
            'description' => 'Ik verzoek u op grond van de Wet openbaarheid van bestuur om mij de volgende informatie te verstrekken...',
            'original_file_path' => 'test/woo-request-1.pdf',
            'original_file_content_markdown' => "# WOO Verzoek\n\nHierbij verzoek ik...",
            'status' => 'in_progress',
            'submitted_at' => now()->subDays(5),
        ]);

        $wooRequest2 = WooRequest::create([
            'user_id' => $burger->id,
            'case_manager_id' => null,
            'title' => 'Informatieverzoek over verkeersbesluiten',
            'description' => 'Graag ontvang ik alle documenten met betrekking tot...',
            'original_file_path' => 'test/woo-request-2.pdf',
            'original_file_content_markdown' => "# WOO Verzoek Verkeer\n\nInformatie over...",
            'status' => 'submitted',
            'submitted_at' => now()->subDays(2),
        ]);

        $this->command->info("\nCreated 2 WOO requests");

        // Create questions for first WOO request
        $question1 = Question::create([
            'woo_request_id' => $wooRequest1->id,
            'question_text' => 'Welke maatregelen zijn er genomen om de CO2-uitstoot te verminderen in 2023?',
            'order' => 1,
            'status' => 'partially_answered',
        ]);

        $question2 = Question::create([
            'woo_request_id' => $wooRequest1->id,
            'question_text' => 'Wat is de totale investering in duurzame energie projecten?',
            'order' => 2,
            'status' => 'unanswered',
        ]);

        $question3 = Question::create([
            'woo_request_id' => $wooRequest1->id,
            'question_text' => 'Hoeveel ton CO2-reductie is er gerealiseerd per kwartaal?',
            'order' => 3,
            'status' => 'answered',
            'ai_summary' => "**Q1:** 12.500 ton\n**Q2:** 15.200 ton\n**Q3:** 14.800 ton\n**Q4:** 16.300 ton",
        ]);

        // Create questions for second WOO request
        Question::create([
            'woo_request_id' => $wooRequest2->id,
            'question_text' => 'Welke verkeersbesluiten zijn er genomen in de afgelopen 6 maanden?',
            'order' => 1,
            'status' => 'unanswered',
        ]);

        $this->command->info('Created 4 questions across both requests');

        // Create internal request
        $internalRequest = InternalRequest::create([
            'woo_request_id' => $wooRequest1->id,
            'case_manager_id' => $caseManager->id,
            'colleague_email' => 'colleague@example.com',
            'colleague_name' => 'Test Colleague',
            'description' => 'Graag de documenten over klimaatbeleid uit 2023 aanleveren.',
            'upload_token' => Str::random(64),
            'token_expires_at' => now()->addWeeks(4),
            'status' => 'submitted',
            'sent_at' => now()->subDays(3),
        ]);

        $this->command->info("\nCreated internal request with upload token");
        $this->command->info('  Upload URL: ' . route('upload.show', $internalRequest->upload_token));

        // Create submission
        $submission = Submission::create([
            'internal_request_id' => $internalRequest->id,
            'submitted_by_email' => 'colleague@example.com',
            'submitted_by_name' => 'Test Colleague',
            'submission_notes' => 'Hierbij de gevraagde documenten over het klimaatbeleid.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'documents_count' => 2,
        ]);

        $this->command->info('Created submission');

        // Create documents
        $document1 = Document::create([
            'woo_request_id' => $wooRequest1->id,
            'submission_id' => $submission->id,
            'file_path' => 'test/klimaatrapport-2023.pdf',
            'file_name' => 'Klimaatrapport 2023.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 2048576,
            'content_markdown' => "# Klimaatrapport 2023\n\nIn 2023 zijn diverse maatregelen genomen...",
            'ai_summary' => 'Dit rapport beschrijft de klimaatmaatregelen die in 2023 zijn genomen, inclusief een overzicht van CO2-reductie per kwartaal.',
            'processed_at' => now()->subDays(2),
        ]);

        $document2 = Document::create([
            'woo_request_id' => $wooRequest1->id,
            'submission_id' => $submission->id,
            'file_path' => 'test/investeringsoverzicht.xlsx',
            'file_name' => 'Investeringsoverzicht Duurzame Energie.xlsx',
            'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'file_size' => 524288,
            'content_markdown' => "# Investeringen\n\nProjecten, bedragen, status...",
            'ai_summary' => 'Overzicht van alle investeringen in duurzame energie projecten met totaalbedragen per categorie.',
            'processed_at' => now()->subDays(2),
        ]);

        $this->command->info('Created 2 documents');

        // Link documents to questions
        $document1->questions()->attach($question1->id, [
            'relevance_score' => 0.92,
            'confirmed_by_case_manager' => true,
        ]);

        $document1->questions()->attach($question3->id, [
            'relevance_score' => 0.88,
            'confirmed_by_case_manager' => true,
        ]);

        $document2->questions()->attach($question2->id, [
            'relevance_score' => 0.85,
            'confirmed_by_case_manager' => false,
        ]);

        $this->command->info('Linked documents to questions');

        $this->command->info("\n✅ WOO Hub seeding completed successfully!");
        $this->command->info("\nYou can now log in with:");
        $this->command->info('  burger@example.com / password (Burger)');
        $this->command->info('  casemanager@example.com / password (Case Manager)');
    }
}
