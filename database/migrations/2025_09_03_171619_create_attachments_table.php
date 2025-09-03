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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // مسار الملف المخزن
            $table->string('file_name'); // اسم الملف الأصلي
            $table->string('mime_type')->nullable(); // نوع الملف (e.g., 'image/jpeg', 'application/pdf')

            // هذا هو الجزء الأهم في الـ Polymorphic Relationship
            // attachable_id سيحتوي على ID المقال (أو أي موديل آخر)
            // attachable_type سيحتوي على اسم الموديل (e.g., 'App\Models\Article')
            $table->morphs('attachable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
