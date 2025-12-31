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
        Schema::create('home_section_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_section_id')->constrained('home_sections')->onDelete('cascade');
            $table->string('item_type')->comment('نوع العنصر (text, image, video, button, gallery_item, etc.)');
            $table->json('content')->nullable()->comment('JSON للمحتوى (نص، روابط، إعدادات)');
            $table->string('media_path')->nullable()->comment('مسار الصورة/الفيديو');
            $table->string('youtube_url')->nullable()->comment('رابط يوتيوب');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض داخل القسم');
            $table->json('settings')->nullable()->comment('JSON لإعدادات العنصر (حجم، محاذاة، ألوان)');
            $table->timestamps();

            $table->index(['home_section_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_section_items');
    }
};
