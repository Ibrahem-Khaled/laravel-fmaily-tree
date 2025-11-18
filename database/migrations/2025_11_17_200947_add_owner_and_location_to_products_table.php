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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('owner_id')->nullable()->after('product_subcategory_id')
                  ->constrained('persons')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->after('owner_id')
                  ->constrained('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn(['owner_id', 'location_id']);
        });
    }
};
