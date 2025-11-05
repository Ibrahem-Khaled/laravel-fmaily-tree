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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المكان (مثل: جدة، الرياض)
            $table->string('normalized_name')->index(); // اسم موحد للبحث (بدون مسافات إضافية، أحرف صغيرة)
            $table->string('description')->nullable(); // وصف اختياري
            $table->string('country')->nullable(); // الدولة (اختياري)
            $table->string('city')->nullable(); // المدينة (اختياري)
            $table->integer('persons_count')->default(0); // عدد الأشخاص المرتبطين (لتحسين الأداء)
            $table->timestamps();
            
            // فهرس فريد على normalized_name لمنع التكرار
            $table->unique('normalized_name');
            // فهرس على name للبحث السريع
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
