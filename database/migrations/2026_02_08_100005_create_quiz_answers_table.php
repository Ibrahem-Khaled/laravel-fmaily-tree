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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_question_id');
            $table->unsignedBigInteger('user_id');
            $table->text('answer')->comment('الإجابة (choice_id أو نص مخصص)');
            $table->string('answer_type', 50)->comment('نوع الإجابة: choice أو custom');
            $table->boolean('is_correct')->default(false)->comment('هل الإجابة صحيحة');
            $table->timestamps();

            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['quiz_question_id', 'user_id']);
            $table->index('quiz_question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
