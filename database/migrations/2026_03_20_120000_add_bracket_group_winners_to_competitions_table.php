<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('competitions', 'bracket_group_winners')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->json('bracket_group_winners')->nullable()->comment('فائز كل مجموعة: رقم المجموعة => team_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('competitions', 'bracket_group_winners')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('bracket_group_winners');
            });
        }
    }
};
