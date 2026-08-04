<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paper_submissions', function (Blueprint $table) {
            $table->json('co_authors')->nullable()->after('keywords');
            $table->string('supplementary_file_path')->nullable()->after('presentation_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_submissions', function (Blueprint $table) {
            $table->dropColumn(['co_authors', 'supplementary_file_path']);
        });
    }
};
