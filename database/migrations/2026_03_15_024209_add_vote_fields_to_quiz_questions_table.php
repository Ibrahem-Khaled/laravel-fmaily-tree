<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('vote_max_selections')->nullable()->default(1)->after('groups_count');
            $table->boolean('require_prior_registration')->default(false)->after('vote_max_selections');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['vote_max_selections', 'require_prior_registration']);
        });
    }
};
