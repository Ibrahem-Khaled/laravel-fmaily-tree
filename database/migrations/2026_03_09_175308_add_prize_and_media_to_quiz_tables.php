<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->string('prize')->nullable()->after('winners_count');
        });

        Schema::table('quiz_question_choices', function (Blueprint $table) {
            $table->string('image')->nullable()->after('choice_text');
            $table->string('video')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('prize');
        });

        Schema::table('quiz_question_choices', function (Blueprint $table) {
            $table->dropColumn(['image', 'video']);
        });
    }
};
