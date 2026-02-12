<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * السماح بتسجيل أكثر من مستخدم بنفس رقم الهاتف (للمسابقات).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['phone']);
        });
    }
};
