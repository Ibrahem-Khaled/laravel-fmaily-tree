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
        Schema::create('family_news', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان الخبر');
            $table->text('content')->comment('محتوى الخبر');
            $table->text('summary')->nullable()->comment('ملخص الخبر');
            $table->string('main_image_path')->nullable()->comment('مسار الصورة الرئيسية');
            $table->dateTime('published_at')->nullable()->comment('تاريخ النشر');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->boolean('is_active')->default(true)->comment('حالة التفعيل');
            $table->integer('views_count')->default(0)->comment('عدد المشاهدات');
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_news');
    }
};
