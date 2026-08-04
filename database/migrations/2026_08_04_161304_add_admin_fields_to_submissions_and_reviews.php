<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paper_submissions', function (Blueprint $table) {
            $table->string('loa_path')->nullable()->after('status');
            $table->string('blind_manuscript_path')->nullable()->after('full_paper_path');
            $table->string('plagiarism_score')->nullable()->after('validation_notes');
        });

        Schema::table('paper_reviews', function (Blueprint $table) {
            $table->date('deadline')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('paper_submissions', function (Blueprint $table) {
            $table->dropColumn(['loa_path', 'blind_manuscript_path', 'plagiarism_score']);
        });

        Schema::table('paper_reviews', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
};