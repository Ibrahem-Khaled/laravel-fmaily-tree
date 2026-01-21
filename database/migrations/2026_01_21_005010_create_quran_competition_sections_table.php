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
        // حماية في حال فشل ترحيل سابق وترك الجدول موجوداً
        Schema::dropIfExists('quran_competition_sections');

        Schema::create('quran_competition_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('quran_competitions')->onDelete('cascade');
            $table->string('name')->comment('اسم القسم داخل المسابقة');
            $table->integer('sort_order')->default(0)->comment('ترتيب عرض القسم');
            $table->timestamps();

            $table->index(['competition_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_competition_sections');
    }
};
