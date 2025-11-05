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
        Schema::table('persons', function (Blueprint $table) {
            if (!Schema::hasColumn('persons', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('location')->constrained('locations')->onDelete('set null');
                $table->index('location_id'); // فهرس لتحسين الأداء
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            if (Schema::hasColumn('persons', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropIndex(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};
