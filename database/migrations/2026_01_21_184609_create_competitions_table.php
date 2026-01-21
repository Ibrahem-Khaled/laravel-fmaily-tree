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
        if (Schema::hasTable('competitions')) {
            return;
        }

        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('عنوان المسابقة');
            $table->text('description')->nullable()->comment('وصف المسابقة');
            $table->string('game_type')->comment('نوع اللعبة');
            $table->integer('team_size')->comment('عدد الأعضاء المطلوب للفريق');
            $table->string('registration_token', 64)->unique()->comment('رمز فريد للتسجيل');
            $table->date('start_date')->nullable()->comment('تاريخ بداية التسجيل');
            $table->date('end_date')->nullable()->comment('تاريخ نهاية التسجيل');
            $table->boolean('is_active')->default(true)->comment('حالة المسابقة');
            $table->unsignedBigInteger('created_by')->nullable()->comment('منشئ المسابقة');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['is_active', 'start_date', 'end_date']);
            $table->index('registration_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
