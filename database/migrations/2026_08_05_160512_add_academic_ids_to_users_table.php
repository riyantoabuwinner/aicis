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
        Schema::table('users', function (Blueprint $table) {
            $table->string('scopus_id')->nullable()->after('institution');
            $table->string('google_scholar_id')->nullable()->after('scopus_id');
            $table->string('sinta_id')->nullable()->after('google_scholar_id');
            $table->string('orcid_id')->nullable()->after('sinta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['scopus_id', 'google_scholar_id', 'sinta_id', 'orcid_id']);
        });
    }
};
