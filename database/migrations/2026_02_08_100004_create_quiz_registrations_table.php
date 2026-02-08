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
        Schema::create('quiz_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_competition_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('quiz_competition_id')->references('id')->on('quiz_competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['quiz_competition_id', 'user_id']);
            $table->index('quiz_competition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_registrations');
    }
};
