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
        if (Schema::hasTable('teams')) {
            return;
        }

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id')->comment('المسابقة');
            $table->string('name')->comment('اسم الفريق');
            $table->unsignedBigInteger('created_by_user_id')->comment('منشئ الفريق');
            $table->boolean('is_complete')->default(false)->comment('هل الفريق مكتمل');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['competition_id', 'is_complete']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
