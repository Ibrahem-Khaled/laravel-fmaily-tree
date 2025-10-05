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
            // إضافة فهارس لتحسين الأداء
            $table->index(['parent_id', 'from_outside_the_family'], 'idx_parent_outside');
            $table->index(['mother_id'], 'idx_mother_id');
            $table->index(['gender', 'parent_id'], 'idx_gender_parent');
            $table->index(['gender', 'mother_id'], 'idx_gender_mother');
            $table->index(['birth_date'], 'idx_birth_date');
            $table->index(['death_date'], 'idx_death_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropIndex('idx_parent_outside');
            $table->dropIndex('idx_mother_id');
            $table->dropIndex('idx_gender_parent');
            $table->dropIndex('idx_gender_mother');
            $table->dropIndex('idx_birth_date');
            $table->dropIndex('idx_death_date');
        });
    }
};
