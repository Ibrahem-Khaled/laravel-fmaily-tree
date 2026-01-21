<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('persons')) {
            return;
        }

        $dbName = DB::connection()->getDatabaseName();

        $idCol = DB::selectOne(
            "SELECT EXTRA as extra
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'persons' AND COLUMN_NAME = 'id'
             LIMIT 1",
            [$dbName]
        );

        if (!$idCol) {
            return;
        }

        if (($idCol->extra ?? '') !== 'auto_increment') {
            $pk = DB::selectOne(
                "SELECT COUNT(*) as cnt
                 FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'persons' AND INDEX_NAME = 'PRIMARY' AND COLUMN_NAME = 'id'
                 LIMIT 1",
                [$dbName]
            );

            if ((int) ($pk->cnt ?? 0) === 0) {
                DB::statement("ALTER TABLE `persons` ADD PRIMARY KEY (`id`)");
            }

            DB::statement("ALTER TABLE `persons` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        }
    }

    public function down(): void
    {
        // Intentionally left empty (non-destructive hotfix)
    }
};

