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
        Schema::create('person_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('label')->nullable(); // تسمية اختيارية (مثل: مكان الإقامة، مكان العمل، إلخ)
            $table->boolean('is_primary')->default(false); // تحديد الموقع الأساسي
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('person_id');
            $table->index('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_locations');
    }
};
