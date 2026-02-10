<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminPermissionsSeeder::class,
            // ProductStoreSeeder::class,
            QuranCompetitionSeeder::class,
            WalkingRecordSeeder::class,
            // يمكن إضافة سيدرات أخرى هنا
        ]);
    }
}
