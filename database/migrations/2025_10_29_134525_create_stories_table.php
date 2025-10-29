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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();

            // العنوان
            $table->string('title');

            // صاحب القصة (علاقة مع persons)
            $table->foreignId('story_owner_id')->nullable()->constrained('persons')->onDelete('set null');

            // محتوى القصة (النص)
            $table->text('content')->nullable();

            // مسار الملف الصوتي
            $table->string('audio_path')->nullable();

            // رابط فيديو خارجي (يوتيوب أو منصة مشابهة)
            $table->string('video_url')->nullable();

            // مسار ملف الفيديو المرفوع
            $table->string('video_path')->nullable();

            $table->timestamps();

            // فهارس للأداء
            $table->index('story_owner_id');
            $table->index('title');
        });

        // جدول pivot للعلاقة many-to-many مع الرواة
        Schema::create('story_narrators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('stories')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->timestamps();

            // منع تكرار راوي لنفس القصة
            $table->unique(['story_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_narrators');
        Schema::dropIfExists('stories');
    }
};
