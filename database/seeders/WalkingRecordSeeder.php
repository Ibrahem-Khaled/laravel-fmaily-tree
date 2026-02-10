<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WalkingRecord;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WalkingRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('لا يوجد مستخدمون. قم بتشغيل AdminPermissionsSeeder أولاً.');
            return;
        }

        $notes = [
            'مشي صباحي في الحديقة',
            'تمارين مشي مسائية',
            'مشي مع العائلة',
            'جولة في الحي',
            'مشي للمدراس',
            'تنزه على الشاطئ',
            null,
            null,
        ];

        $recordCount = 0;

        foreach ($users as $user) {
            $recordsPerUser = rand(5, 10);

            for ($i = 0; $i < $recordsPerUser; $i++) {
                $date = Carbon::today()->subDays(rand(0, 30));
                $steps = rand(1000, 25000);
                $distanceKm = round($steps * 0.0007, 2);
                $durationMinutes = (int) round($steps / 80);

                $exists = WalkingRecord::where('user_id', $user->id)
                    ->whereDate('date', $date)
                    ->exists();

                if (!$exists) {
                    WalkingRecord::create([
                        'user_id' => $user->id,
                        'date' => $date,
                        'steps' => $steps,
                        'distance_km' => $distanceKm,
                        'duration_minutes' => $durationMinutes,
                        'notes' => $notes[array_rand($notes)],
                    ]);
                    $recordCount++;
                }
            }
        }

        $this->command->info("تم إنشاء {$recordCount} سجل مشي وهمي.");
    }
}
