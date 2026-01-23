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
        Schema::create('competition_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id')->comment('المسابقة');
            $table->unsignedBigInteger('user_id')->comment('المستخدم');
            $table->unsignedBigInteger('team_id')->nullable()->comment('الفريق (إذا كان معه خوي)');
            $table->boolean('has_brother')->default(false)->comment('هل كان معه خوي');
            $table->timestamps();

            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->unique(['competition_id', 'user_id'], 'unique_competition_user');
            $table->index('competition_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_registrations');
    }
};
