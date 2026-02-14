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
        Schema::table('important_links', function (Blueprint $table) {
            $table->string('type')->default('website')->after('url'); // app | website
            $table->string('image')->nullable()->after('description');
            $table->foreignId('submitted_by_user_id')->nullable()->after('image')->constrained('users')->nullOnDelete();
            $table->string('status')->default('approved')->after('submitted_by_user_id'); // pending | approved
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
            $table->dropForeign(['submitted_by_user_id']);
            $table->dropColumn(['type', 'image', 'submitted_by_user_id', 'status']);
        });
    }
};
