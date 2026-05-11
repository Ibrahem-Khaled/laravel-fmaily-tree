<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Upgrade `description` from TEXT to LONGTEXT on the two tables that now
     * carry Summernote-edited rich-text. A single base64-embedded image can
     * already exceed the 64 KB limit of TEXT, so we mirror the precedent set
     * by `change_description_type_in_quiz_competitions_table`.
     */
    public function up(): void
    {
        Schema::table('family_events', function (Blueprint $table) {
            $table->longText('description')->nullable()->comment('وصف المناسبة (HTML)')->change();
        });

        Schema::table('family_councils', function (Blueprint $table) {
            $table->longText('description')->nullable()->comment('وصف المكان (HTML)')->change();
        });
    }

    public function down(): void
    {
        Schema::table('family_events', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('family_councils', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }
};
