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
            $table->integer('display_order')->default(0); // ترتيب الشخص في الشجرة العائلية
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('death_date')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->boolean('from_outside_the_family')->default(false);
            $table->string('photo_url')->nullable();
            $table->text('biography')->nullable();
            $table->string('occupation')->nullable();
            $table->string('location')->nullable();
            // Nested Set Model columns
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->nestedSet();

            $table->timestamps();

            // Indexes for better performance
            $table->index(['first_name', 'last_name']);
            $table->index('birth_date');
            $table->index('gender');
            $table->foreign('partner_id')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('mother_id')->references('id')->on('persons')->onDelete('set null');
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
