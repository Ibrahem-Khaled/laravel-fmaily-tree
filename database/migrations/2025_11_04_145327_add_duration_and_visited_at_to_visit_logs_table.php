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
        Schema::table('visit_logs', function (Blueprint $table) {
            $table->integer('duration')->nullable()->after('response_time')->comment('المدة بالثواني التي قضاها في الصفحة');
            $table->boolean('is_unique_visit')->default(true)->after('duration')->comment('هل هذه زيارة فريدة (ليست تحديث)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_logs', function (Blueprint $table) {
            $table->dropColumn(['duration', 'is_unique_visit']);
        });
    }
};
