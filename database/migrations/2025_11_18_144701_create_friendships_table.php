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
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('persons')->onDelete('cascade');
            $table->text('description')->nullable(); // وصف/نبذة عن الصداقة
            $table->text('friendship_story')->nullable(); // قصة الصداقة
            $table->timestamps();

            // منع تكرار نفس الصداقة
            $table->unique(['person_id', 'friend_id']);
            // فهرس للبحث السريع
            $table->index('person_id');
            $table->index('friend_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
