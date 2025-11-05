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
        Schema::table('images', function (Blueprint $table) {
            $table->boolean('is_program')->default(false)->after('category_id')->comment('هل هذه الصورة برنامج؟');
            $table->string('program_title')->nullable()->after('is_program')->comment('عنوان البرنامج');
            $table->text('program_description')->nullable()->after('program_title')->comment('وصف البرنامج');
            $table->integer('program_order')->default(0)->after('program_description')->comment('ترتيب البرنامج');
            
            $table->index(['is_program', 'program_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex(['is_program', 'program_order']);
            $table->dropColumn(['is_program', 'program_title', 'program_description', 'program_order']);
        });
    }
};
