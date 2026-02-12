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
        Schema::dropIfExists('competition_registration_categories');
        
        Schema::create('competition_registration_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_registration_id')->comment('التسجيل في المسابقة');
            $table->unsignedBigInteger('category_id')->comment('التصنيف المختار');
            $table->timestamps();

            $table->foreign('competition_registration_id', 'comp_reg_cat_reg_id_foreign')
                ->references('id')
                ->on('competition_registrations')
                ->onDelete('cascade');
            
            $table->foreign('category_id', 'comp_reg_cat_cat_id_foreign')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->unique(['competition_registration_id', 'category_id'], 'comp_reg_cat_unique');
            $table->index('competition_registration_id', 'comp_reg_cat_reg_id_idx');
            $table->index('category_id', 'comp_reg_cat_cat_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_registration_categories');
    }
};
