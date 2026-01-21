<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dbName = DB::connection()->getDatabaseName();

        $tables = [
            'quran_competitions',
            'quran_competition_winners',
            'quran_competition_media',
            'quran_category_managers',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            $idCol = DB::selectOne(
                "SELECT EXTRA as extra
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = 'id'
                 LIMIT 1",
                [$dbName, $table]
            );

            if (!$idCol) {
                continue;
            }

            if (($idCol->extra ?? '') !== 'auto_increment') {
                // Ensure primary key exists on id (required for AUTO_INCREMENT)
                $pk = DB::selectOne(
                    "SELECT COUNT(*) as cnt
                     FROM information_schema.STATISTICS
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = 'PRIMARY' AND COLUMN_NAME = 'id'
                     LIMIT 1",
                    [$dbName, $table]
                );

                if ((int) ($pk->cnt ?? 0) === 0) {
                    DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
                }

                DB::statement("ALTER TABLE `{$table}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            }
        }

        // Ensure important indexes exist on quran_competitions (some DBs were created without them)
        if (Schema::hasTable('quran_competitions')) {
            $indexes = [
                'quran_competitions_is_active_display_order_index' => "ALTER TABLE `quran_competitions` ADD INDEX `quran_competitions_is_active_display_order_index` (`is_active`, `display_order`)",
                'quran_competitions_hijri_year_index' => "ALTER TABLE `quran_competitions` ADD INDEX `quran_competitions_hijri_year_index` (`hijri_year`)",
                'quran_competitions_category_id_index' => "ALTER TABLE `quran_competitions` ADD INDEX `quran_competitions_category_id_index` (`category_id`)",
            ];

            foreach ($indexes as $indexName => $sql) {
                $exists = DB::selectOne(
                    "SELECT COUNT(*) as cnt
                     FROM information_schema.STATISTICS
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'quran_competitions' AND INDEX_NAME = ?
                     LIMIT 1",
                    [$dbName, $indexName]
                );

                if ((int) ($exists->cnt ?? 0) === 0) {
                    DB::statement($sql);
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally left empty (non-destructive hotfix)
    }
};

