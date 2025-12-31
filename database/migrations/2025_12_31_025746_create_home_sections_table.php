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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان القسم (للوحة التحكم)');
            $table->string('section_type')->default('custom')->comment('نوع القسم (custom, gallery, text_with_image, video_section, etc.)');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->boolean('is_active')->default(true)->comment('حالة التفعيل');
            $table->json('settings')->nullable()->comment('JSON للإعدادات (ألوان، خلفيات، padding, margin, etc.)');
            $table->string('css_classes')->nullable()->comment('فئات CSS مخصصة');
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
