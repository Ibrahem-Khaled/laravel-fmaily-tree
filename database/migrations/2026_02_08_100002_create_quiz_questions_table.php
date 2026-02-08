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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_competition_id')->constrained()->onDelete('cascade');
            $table->text('question_text')->comment('نص السؤال');
            $table->string('answer_type', 50)->comment('نوع الإجابة: multiple_choice أو custom_text');
            $table->integer('winners_count')->default(1)->comment('عدد الفائزين المطلوب');
            $table->integer('display_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();

            $table->index('quiz_competition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
