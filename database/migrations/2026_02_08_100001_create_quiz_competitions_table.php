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
        Schema::create('quiz_competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان المسابقة');
            $table->text('description')->nullable()->comment('وصف المسابقة');
            $table->boolean('is_active')->default(true)->comment('حالة التفعيل');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->dateTime('start_at')->nullable()->comment('بداية المسابقة');
            $table->dateTime('end_at')->nullable()->comment('نهاية المسابقة');
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_competitions');
    }
};
