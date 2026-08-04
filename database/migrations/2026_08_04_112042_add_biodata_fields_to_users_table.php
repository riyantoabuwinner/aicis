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
            $table->string('front_title')->nullable()->after('name');
            $table->string('back_title')->nullable()->after('front_title');
            $table->string('highest_education')->nullable()->after('whatsapp_number');
            $table->string('study_program')->nullable()->after('highest_education');
            $table->string('university')->nullable()->after('study_program');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'front_title',
                'back_title',
                'highest_education',
                'study_program',
                'university',
            ]);
        });
    }
};
