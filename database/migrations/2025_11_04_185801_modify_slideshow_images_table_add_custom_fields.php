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
        Schema::table('slideshow_images', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->string('image_path')->nullable()->after('id')->comment('مسار الصورة المرفوعة');
            $table->string('title')->nullable()->after('image_path')->comment('عنوان السلايدشو');
            $table->text('description')->nullable()->after('title')->comment('وصف السلايدشو');
            $table->string('link')->nullable()->after('description')->comment('رابط اختياري');
            
            // جعل image_id nullable (لأننا سنستخدم image_path بدلاً منه)
            $table->foreignId('image_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slideshow_images', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'title', 'description', 'link']);
            $table->foreignId('image_id')->nullable(false)->change();
        });
    }
};
