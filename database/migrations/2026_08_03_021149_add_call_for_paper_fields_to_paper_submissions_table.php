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
            $table->boolean('is_best_paper')->default(false);
            $table->string('payment_proof_path')->nullable();
            $table->string('publication_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_submissions', function (Blueprint $table) {
            $table->dropColumn(['is_best_paper', 'payment_proof_path', 'publication_status']);
        });
    }
};
