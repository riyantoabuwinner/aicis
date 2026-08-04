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
        Schema::table('official_partners', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->change();
            $table->string('logo_url')->nullable()->after('logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_partners', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
    }
};
