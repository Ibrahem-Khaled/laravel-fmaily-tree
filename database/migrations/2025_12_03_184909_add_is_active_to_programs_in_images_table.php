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
        Schema::table('images', function (Blueprint $table) {
            if (!Schema::hasColumn('images', 'program_is_active')) {
                $table->boolean('program_is_active')->default(true)->after('program_order')->comment('حالة تفعيل البرنامج');
                $table->index(['is_program', 'program_is_active', 'program_order']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            if (Schema::hasColumn('images', 'program_is_active')) {
                $table->dropIndex(['is_program', 'program_is_active', 'program_order']);
                $table->dropColumn('program_is_active');
            }
        });
    }
};
