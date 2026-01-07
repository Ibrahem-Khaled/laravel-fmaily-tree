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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('available_all_week')->default(false)->after('is_rental');
            $table->time('all_week_start_time')->nullable()->after('available_all_week');
            $table->time('all_week_end_time')->nullable()->after('all_week_start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['available_all_week', 'all_week_start_time', 'all_week_end_time']);
        });
    }
};
