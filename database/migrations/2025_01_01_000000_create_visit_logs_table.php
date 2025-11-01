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
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 1000);
            $table->string('method', 10)->default('GET');
            $table->string('route_name')->nullable();
            $table->string('referer', 1000)->nullable();
            $table->json('metadata')->nullable(); // بيانات إضافية مثل: country, city, device, browser, etc.
            $table->string('session_id', 255)->nullable();
            $table->string('request_id', 36)->nullable();
            $table->decimal('response_time', 8, 2)->nullable(); // وقت الاستجابة بالميلي ثانية
            $table->integer('status_code')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
    }
};

