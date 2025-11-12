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
        Schema::table('family_councils', function (Blueprint $table) {
            $table->string('working_days_from')->nullable()->after('google_map_url'); // من يوم
            $table->string('working_days_to')->nullable()->after('working_days_from'); // إلى يوم
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_councils', function (Blueprint $table) {
            $table->dropColumn(['working_days_from', 'working_days_to']);
        });
    }
};
