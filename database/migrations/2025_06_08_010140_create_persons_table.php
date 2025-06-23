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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('photo_url')->nullable();
            $table->text('biography')->nullable();
            $table->string('occupation')->nullable();
            $table->string('location')->nullable();

            // Nested Set Model columns
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->nestedSet();

            $table->timestamps();

            // Indexes for better performance
            $table->index(['first_name', 'last_name']);
            $table->index('birth_date');
            $table->index('gender');

            $table->foreign('partner_id')->references('id')->on('persons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
