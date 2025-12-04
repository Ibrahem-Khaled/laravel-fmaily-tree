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
            if (!Schema::hasColumn('images', 'is_proud_of')) {
                $table->boolean('is_proud_of')->default(false)->after('program_is_active')->comment('هل هذه الصورة في قسم نفتخر بهم؟');
                $table->string('proud_of_title')->nullable()->after('is_proud_of')->comment('عنوان العنصر في نفتخر بهم');
                $table->text('proud_of_description')->nullable()->after('proud_of_title')->comment('وصف العنصر في نفتخر بهم');
                $table->integer('proud_of_order')->default(0)->after('proud_of_description')->comment('ترتيب العنصر في نفتخر بهم');
                $table->boolean('proud_of_is_active')->default(true)->after('proud_of_order')->comment('حالة تفعيل العنصر في نفتخر بهم');
                
                $table->index(['is_proud_of', 'proud_of_is_active', 'proud_of_order']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            if (Schema::hasColumn('images', 'is_proud_of')) {
                $table->dropIndex(['is_proud_of', 'proud_of_is_active', 'proud_of_order']);
                $table->dropColumn(['is_proud_of', 'proud_of_title', 'proud_of_description', 'proud_of_order', 'proud_of_is_active']);
            }
        });
    }
};
