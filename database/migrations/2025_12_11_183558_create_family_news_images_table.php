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
        Schema::create('family_news_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_news_id')->constrained('family_news')->onDelete('cascade')->comment('معرف الخبر');
            $table->string('image_path')->comment('مسار الصورة');
            $table->string('caption')->nullable()->comment('وصف الصورة');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();

            $table->index(['family_news_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_news_images');
    }
};
