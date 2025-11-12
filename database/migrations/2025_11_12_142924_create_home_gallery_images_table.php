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
        Schema::create('home_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->comment('مسار الصورة المرفوعة');
            $table->string('name')->nullable()->comment('اسم الصورة');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->comment('الفئة المرتبطة');
            $table->integer('order')->default(0)->comment('ترتيب الصورة');
            $table->boolean('is_active')->default(true)->comment('حالة التفعيل');
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_gallery_images');
    }
};
