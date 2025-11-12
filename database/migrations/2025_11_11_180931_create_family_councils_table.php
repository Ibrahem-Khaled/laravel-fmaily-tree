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
        Schema::create('family_councils', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المكان
            $table->text('description')->nullable(); // وصف المكان
            $table->text('address')->nullable(); // عنوان المكان
            $table->string('google_map_url')->nullable(); // رابط جوجل ماب
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
        Schema::dropIfExists('family_councils');
    }
};
