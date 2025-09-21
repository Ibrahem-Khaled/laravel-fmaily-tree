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
        Schema::create('breastfeedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nursing_mother_id')->comment('الأم المرضعة');
            $table->unsignedBigInteger('breastfed_child_id')->comment('الطفل المرتضع');
            $table->date('start_date')->nullable()->comment('تاريخ بداية الرضاعة');
            $table->date('end_date')->nullable()->comment('تاريخ انتهاء الرضاعة');
            $table->text('notes')->nullable()->comment('ملاحظات إضافية');
            $table->boolean('is_active')->default(true)->comment('هل العلاقة نشطة');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('nursing_mother_id')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('breastfed_child_id')->references('id')->on('persons')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['nursing_mother_id', 'breastfed_child_id']);
            $table->index('start_date');
            $table->index('is_active');

            // Ensure unique relationship between mother and child
            $table->unique(['nursing_mother_id', 'breastfed_child_id'], 'unique_nursing_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breastfeedings');
    }
};
