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
        Schema::create('program_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('gallery_order')->default(0);
            $table->timestamps();

            $table->foreign('program_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');

            $table->index(['program_id', 'gallery_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_galleries');
    }
};
