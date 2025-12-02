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
        Schema::create('family_events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // اسم المناسبة
            $table->text('description')->nullable(); // وصف المناسبة
            $table->string('city')->nullable(); // المدينة
            $table->string('location')->nullable(); // الموقع
            $table->dateTime('event_date'); // تاريخ المناسبة
            $table->boolean('show_countdown')->default(false); // إظهار العداد التنازلي
            $table->integer('display_order')->default(0); // ترتيب العرض
            $table->boolean('is_active')->default(true); // نشط/غير نشط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_events');
    }
};
