<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix Spatie activity_log table id (some DBs were created without AUTO_INCREMENT)
        $activityConn = config('activitylog.database_connection') ?: config('database.default');
        $activityTable = config('activitylog.table_name') ?: 'activity_log';

        if (Schema::connection($activityConn)->hasTable($activityTable)) {
            $dbName = DB::connection($activityConn)->getDatabaseName();

            $idCol = DB::connection($activityConn)->selectOne(
                "SELECT EXTRA as extra
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = 'id'
                 LIMIT 1",
                [$dbName, $activityTable]
            );

            if ($idCol && ($idCol->extra ?? '') !== 'auto_increment') {
                // Ensure primary key on id exists (required for AUTO_INCREMENT)
                $pk = DB::connection($activityConn)->selectOne(
                    "SELECT COUNT(*) as cnt
                     FROM information_schema.STATISTICS
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = 'PRIMARY' AND COLUMN_NAME = 'id'
                     LIMIT 1",
                    [$dbName, $activityTable]
                );

                if ((int) ($pk->cnt ?? 0) === 0) {
                    DB::connection($activityConn)->statement("ALTER TABLE `{$activityTable}` ADD PRIMARY KEY (`id`)");
                }

                DB::connection($activityConn)->statement(
                    "ALTER TABLE `{$activityTable}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT"
                );
            }
        }

        // Fix visit_logs table id (created without AUTO_INCREMENT in some environments)
        if (Schema::hasTable('visit_logs')) {
            $dbName = DB::connection()->getDatabaseName();

            $idCol = DB::selectOne(
                "SELECT EXTRA as extra
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'visit_logs' AND COLUMN_NAME = 'id'
                 LIMIT 1",
                [$dbName]
            );

            if ($idCol && ($idCol->extra ?? '') !== 'auto_increment') {
                $pk = DB::selectOne(
                    "SELECT COUNT(*) as cnt
                     FROM information_schema.STATISTICS
                     WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'visit_logs' AND INDEX_NAME = 'PRIMARY' AND COLUMN_NAME = 'id'
                     LIMIT 1",
                    [$dbName]
                );

                if ((int) ($pk->cnt ?? 0) === 0) {
                    DB::statement("ALTER TABLE `visit_logs` ADD PRIMARY KEY (`id`)");
                }

                DB::statement("ALTER TABLE `visit_logs` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            }
        }
    }

    public function down(): void
    {
        // Intentionally left empty (non-destructive hotfix)
    }
};

