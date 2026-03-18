<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_survey_items', function (Blueprint $table) {
            $table->text('youtube_url')->nullable()->after('media_path');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_survey_items', function (Blueprint $table) {
            $table->dropColumn('youtube_url');
        });
    }
};

