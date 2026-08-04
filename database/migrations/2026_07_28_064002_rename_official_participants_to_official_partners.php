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
        Schema::rename('official_participants', 'official_partners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('official_partners', 'official_participants');
    }
};
