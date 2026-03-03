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
        Schema::table('quiz_competitions', function (Blueprint $table) {
            $table->integer('reveal_delay_seconds')->default(60)->after('end_at')->comment('Delay in seconds before questions are revealed after start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_competitions', function (Blueprint $table) {
            $table->dropColumn('reveal_delay_seconds');
        });
    }
};
