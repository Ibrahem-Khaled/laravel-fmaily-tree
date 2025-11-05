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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('اسم الدورة');
            $table->text('description')->nullable()->comment('وصف الدورة');
            $table->string('image_path')->nullable()->comment('مسار صورة الدورة');
            $table->string('instructor')->nullable()->comment('اسم المدرب');
            $table->string('duration')->nullable()->comment('مدة الدورة');
            $table->integer('students')->default(0)->comment('عدد الطلاب');
            $table->string('link')->nullable()->comment('رابط الدورة');
            $table->integer('order')->default(0)->comment('ترتيب العرض');
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
        Schema::dropIfExists('courses');
    }
};
