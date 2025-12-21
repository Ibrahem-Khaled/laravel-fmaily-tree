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
        Schema::create('quran_competition_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('quran_competitions')->onDelete('cascade');
            $table->enum('media_type', ['image', 'video', 'youtube'])->default('image')->comment('نوع الوسائط');
            $table->string('file_path')->nullable()->comment('مسار الملف (للصور والفيديوهات المحلية)');
            $table->string('youtube_url')->nullable()->comment('رابط يوتيوب');
            $table->string('caption')->nullable()->comment('وصف الصورة/الفيديو');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index(['competition_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_competition_media');
    }
};
