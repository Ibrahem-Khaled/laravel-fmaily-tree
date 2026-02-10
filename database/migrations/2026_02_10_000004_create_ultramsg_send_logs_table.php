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
        Schema::create('ultramsg_send_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->nullable()->constrained('notifications')->onDelete('set null');
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->string('to_number');
            $table->text('message_sent')->nullable();
            $table->string('media_type')->nullable();
            $table->string('media_path')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->string('ultramsg_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultramsg_send_logs');
    }
};
