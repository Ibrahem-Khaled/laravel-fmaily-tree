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
        if (Schema::hasTable('team_members')) {
            return;
        }

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->comment('الفريق');
            $table->unsignedBigInteger('user_id')->comment('العضو');
            $table->enum('role', ['captain', 'member'])->default('member')->comment('الدور');
            $table->timestamp('joined_at')->useCurrent()->comment('تاريخ الانضمام');
            $table->timestamps();

            $table->unique(['team_id', 'user_id'], 'unique_team_member');
            $table->index('team_id');
            $table->index('user_id');
        });

        // إضافة foreign keys بشكل منفصل لتجنب مشاكل الـ constraint
        try {
            Schema::table('team_members', function (Blueprint $table) {
                $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // تجاهل الخطأ إذا كان الـ foreign key موجود بالفعل
        }

        try {
            Schema::table('team_members', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // تجاهل الخطأ إذا كان الـ foreign key موجود بالفعل
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
