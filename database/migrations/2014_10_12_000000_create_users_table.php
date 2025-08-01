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
        Schema::create('users', function (Blueprint $table) {
          $table->id();
            $table->string('name');
            $table->string('email')->nullable(); // بدون unique هنا
            $table->string('phone')->nullable(); // بدون unique هنا
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();

            $table->unsignedBigInteger('role_id')->default(1);
            $table->boolean('status')->default(0);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unique(['email']);
            $table->unique(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
