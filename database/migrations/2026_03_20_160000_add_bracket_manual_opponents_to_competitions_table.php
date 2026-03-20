<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('competitions', 'bracket_manual_opponents')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->json('bracket_manual_opponents')->nullable()->comment('منافس يدوي عند غياب الطرف الثاني: [جولة][مباراة] => team_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('competitions', 'bracket_manual_opponents')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('bracket_manual_opponents');
            });
        }
    }
};
