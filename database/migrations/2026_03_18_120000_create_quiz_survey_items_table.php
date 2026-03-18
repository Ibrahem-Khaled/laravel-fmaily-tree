<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_survey_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('block_type', 20); // image, video, text
            $table->text('body_text')->nullable();
            $table->string('media_path')->nullable();
            $table->string('response_kind', 20); // rating, number, text
            $table->unsignedTinyInteger('rating_max')->default(10);
            $table->integer('number_min')->nullable();
            $table->integer('number_max')->nullable();
            $table->timestamps();

            $table->index(['quiz_question_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_survey_items');
    }
};
