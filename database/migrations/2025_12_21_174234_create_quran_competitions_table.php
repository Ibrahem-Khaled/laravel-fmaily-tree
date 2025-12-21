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
        Schema::create('quran_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان المسابقة');
            $table->text('description')->nullable()->comment('وصف المسابقة');
            $table->string('hijri_year')->comment('السنة الهجرية');
            $table->date('start_date')->nullable()->comment('تاريخ البداية');
            $table->date('end_date')->nullable()->comment('تاريخ النهاية');
            $table->string('cover_image')->nullable()->comment('صورة الغلاف');
            $table->boolean('is_active')->default(true)->comment('حالة التفعيل');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index(['is_active', 'display_order']);
            $table->index('hijri_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_competitions');
    }
};
