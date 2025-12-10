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
        Schema::table('breastfeedings', function (Blueprint $table) {
            $table->unsignedBigInteger('breastfeeding_father_id')->nullable()->after('nursing_mother_id')->comment('الأب (زوج الأم)');
            $table->foreign('breastfeeding_father_id')->references('id')->on('persons')->onDelete('set null');
            $table->index('breastfeeding_father_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breastfeedings', function (Blueprint $table) {
            $table->dropForeign(['breastfeeding_father_id']);
            $table->dropIndex(['breastfeeding_father_id']);
            $table->dropColumn('breastfeeding_father_id');
        });
    }
};
