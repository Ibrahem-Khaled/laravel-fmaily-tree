<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionRegistration;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionDemoTeamsSeeder extends Seeder
{
    /** عنوان ثابت لتمييز بيانات السيدر وحذفها عند إعادة التشغيل */
    public const DEMO_TITLE = 'مسابقة تجريبية — سيدر الفرق';

    public function run(): void
    {
        $creator = User::query()->orderBy('id')->first();
        if (!$creator) {
            $creator = User::factory()->create([
                'name' => 'مدير تجريبي',
                'email' => 'demo-admin-' . uniqid() . '@example.test',
            ]);
            $this->command->info('تم إنشاء مستخدم افتراضي للسيدر.');
        }

        DB::transaction(function () use ($creator) {
            Competition::where('title', self::DEMO_TITLE)->delete();

            $competition = Competition::create([
                'title' => self::DEMO_TITLE,
                'description' => 'بيانات وهمية للتجربة — شجرة المجموعات ولوحة التحكم.',
                'game_type' => 'بلوت',
                'team_size' => 2,
                'start_date' => now()->subDays(7)->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'is_active' => true,
                'created_by' => $creator->id,
            ]);

            $teamNames = [
                'فريق الصقور',
                'فريق النجوم',
                'فريق الأمواج',
                'فريق الصحراء',
                'فريق اللؤلؤ',
                'فريق البرق',
            ];

            foreach ($teamNames as $index => $teamName) {
                $captain = User::factory()->create([
                    'name' => 'قائد ' . ($index + 1) . ' — ' . fake()->firstName(),
                ]);
                $member = User::factory()->create([
                    'name' => 'عضو ' . ($index + 1) . ' — ' . fake()->firstName(),
                ]);

                $team = Team::create([
                    'competition_id' => $competition->id,
                    'name' => $teamName,
                    'created_by_user_id' => $captain->id,
                    'is_complete' => true,
                ]);

                $team->members()->attach($captain->id, [
                    'role' => 'captain',
                    'joined_at' => now(),
                ]);
                $team->members()->attach($member->id, [
                    'role' => 'member',
                    'joined_at' => now(),
                ]);

                foreach ([$captain, $member] as $user) {
                    CompetitionRegistration::create([
                        'competition_id' => $competition->id,
                        'user_id' => $user->id,
                        'team_id' => $team->id,
                        'has_brother' => false,
                    ]);
                }
            }
        });

        $competition = Competition::where('title', self::DEMO_TITLE)->firstOrFail();

        $this->command->info(sprintf(
            'تم إنشاء المسابقة #%d «%s» مع %d فرقاً (عضوان لكل فريق).',
            $competition->id,
            $competition->title,
            $competition->teams()->count()
        ));
        $this->command->info('لوحة التحكم: ' . route('dashboard.competitions.show', $competition));
        $this->command->info('JSON: ' . route('competitions.json', $competition));
    }
}
