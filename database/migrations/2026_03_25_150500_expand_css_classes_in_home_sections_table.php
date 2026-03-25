<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('home_sections') || !Schema::hasColumn('home_sections', 'css_classes')) {
            return;
        }

        $driver = DB::getDriverName();

        // MySQL: allow large CSS blocks without truncation.
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `home_sections` MODIFY `css_classes` LONGTEXT NULL');
            return;
        }

        // Fallback for other drivers (may require DBAL depending on environment).
        Schema::table('home_sections', function (Blueprint $table) {
            $table->text('css_classes')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('home_sections') || !Schema::hasColumn('home_sections', 'css_classes')) {
            return;
        }

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `home_sections` MODIFY `css_classes` VARCHAR(255) NULL');
            return;
        }

        Schema::table('home_sections', function (Blueprint $table) {
            $table->string('css_classes')->nullable()->change();
        });
    }
};

