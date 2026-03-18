<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('quiz_questions', 'prize')) {
            return;
        }

        // Store JSON (array) safely; VARCHAR(255) is too small.
        DB::statement('ALTER TABLE `quiz_questions` MODIFY `prize` LONGTEXT NULL');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('quiz_questions', 'prize')) {
            return;
        }

        DB::statement('ALTER TABLE `quiz_questions` MODIFY `prize` VARCHAR(255) NULL');
    }
};

