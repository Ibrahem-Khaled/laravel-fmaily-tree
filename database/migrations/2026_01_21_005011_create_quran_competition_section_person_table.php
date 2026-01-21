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
        Schema::dropIfExists('quran_competition_section_person');

        Schema::create('quran_competition_section_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('quran_competition_sections')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->integer('sort_order')->default(0)->comment('ترتيب الشخص داخل القسم');
            $table->timestamps();

            $table->unique(['section_id', 'person_id']);
            $table->index(['section_id', 'sort_order']);
            $table->index(['person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_competition_section_person');
    }
};

