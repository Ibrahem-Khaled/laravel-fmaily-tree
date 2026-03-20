<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('competitions', 'bracket_round_winners')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->json('bracket_round_winners')->nullable()->comment('فائزون حسب [الجولة][رقم المباراة]');
            });
        }

        if (Schema::hasColumn('competitions', 'bracket_group_winners')) {
            $rows = DB::table('competitions')->whereNotNull('bracket_group_winners')->get();
            foreach ($rows as $row) {
                $decoded = json_decode($row->bracket_group_winners, true);
                if (is_array($decoded) && $decoded !== []) {
                    $wrapped = ['1' => $decoded];
                    DB::table('competitions')->where('id', $row->id)->update([
                        'bracket_round_winners' => json_encode($wrapped),
                    ]);
                }
            }
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('bracket_group_winners');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('competitions', 'bracket_round_winners')) {
            Schema::table('competitions', function (Blueprint $table) {
                $table->json('bracket_group_winners')->nullable();
            });
            $rows = DB::table('competitions')->whereNotNull('bracket_round_winners')->get();
            foreach ($rows as $row) {
                $decoded = json_decode($row->bracket_round_winners, true);
                if (is_array($decoded) && isset($decoded['1'])) {
                    DB::table('competitions')->where('id', $row->id)->update([
                        'bracket_group_winners' => json_encode($decoded['1']),
                    ]);
                }
            }
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('bracket_round_winners');
            });
        }
    }
};
