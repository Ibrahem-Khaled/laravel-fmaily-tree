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
        Schema::create('quran_competition_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('quran_competitions')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->integer('position')->comment('المركز (1, 2, 3, إلخ)');
            $table->string('category')->nullable()->comment('الفئة (مثل: حفظ كامل، حفظ جزئي)');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
            
            $table->index(['competition_id', 'position']);
            $table->unique(['competition_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_competition_winners');
    }
};
