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
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('husband_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('wife_id')->constrained('persons')->onDelete('cascade');
            $table->date('married_at')->nullable();
            $table->date('divorced_at')->nullable();
            $table->timestamps();

            $table->unique(['husband_id', 'wife_id']); // لتفادي التكرار
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
