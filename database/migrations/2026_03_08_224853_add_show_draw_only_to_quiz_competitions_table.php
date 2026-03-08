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
        Schema::table('quiz_competitions', function (Blueprint $table) {
            $table->boolean('show_draw_only')->default(false)->after('reveal_delay_seconds')->comment('إظهار زر القرعة فقط ومنع الإجابة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_competitions', function (Blueprint $table) {
            $table->dropColumn('show_draw_only');
        });
    }
};
