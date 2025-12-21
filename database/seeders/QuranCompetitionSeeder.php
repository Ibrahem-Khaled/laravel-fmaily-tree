<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuranCompetition;
use App\Models\QuranCompetitionWinner;
use App\Models\QuranCompetitionMedia;
use App\Models\Person;

class QuranCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // جلب أشخاص من قاعدة البيانات لإضافتهم كفائزين
        $persons = Person::inRandomOrder()->take(20)->get();
        
        if ($persons->count() < 9) {
            $this->command->warn('لا يوجد أشخاص كافيين في قاعدة البيانات. يرجى إضافة أشخاص أولاً.');
            return;
        }

        $competitions = [
            [
                'title' => 'مسابقة حفظ القرآن الكريم للأطفال',
                'description' => 'مسابقة حفظ القرآن الكريم للأطفال لعام 1445 هـ. تم تنظيم هذه المسابقة لتشجيع الأطفال على حفظ القرآن الكريم وتلاوته بشكل صحيح. شارك في المسابقة عدد كبير من الأطفال المتميزين.',
                'hijri_year' => '1445',
                'start_date' => '2023-06-01',
                'end_date' => '2023-08-30',
                'is_active' => true,
                'display_order' => 1,
                'winners' => [
                    ['position' => 1, 'category' => 'حفظ كامل', 'notes' => 'تميز بحفظ القرآن الكريم كاملاً مع التجويد'],
                    ['position' => 2, 'category' => 'حفظ كامل', 'notes' => 'حفظ ممتاز مع فهم المعاني'],
                    ['position' => 3, 'category' => 'حفظ جزئي', 'notes' => 'حفظ 20 جزء مع التجويد'],
                ],
                'media' => [
                    ['type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'caption' => 'فيديو حفل تكريم الفائزين 1445'],
                    ['type' => 'image', 'caption' => 'صورة جماعية للفائزين'],
                ],
            ],
            [
                'title' => 'مسابقة حفظ القرآن الكريم للأطفال',
                'description' => 'مسابقة حفظ القرآن الكريم للأطفال لعام 1446 هـ. شهدت هذه المسابقة مشاركة واسعة من الأطفال الموهوبين. تم تنظيم حفل كبير لتكريم الفائزين.',
                'hijri_year' => '1446',
                'start_date' => '2024-06-01',
                'end_date' => '2024-08-30',
                'is_active' => true,
                'display_order' => 2,
                'winners' => [
                    ['position' => 1, 'category' => 'حفظ كامل', 'notes' => 'إنجاز رائع في حفظ القرآن الكريم كاملاً'],
                    ['position' => 2, 'category' => 'حفظ كامل', 'notes' => 'تميز في الحفظ والتجويد'],
                    ['position' => 3, 'category' => 'حفظ جزئي', 'notes' => 'حفظ 15 جزء مع التجويد'],
                    ['position' => 4, 'category' => 'حفظ جزئي', 'notes' => 'حفظ 10 أجزاء'],
                ],
                'media' => [
                    ['type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'caption' => 'فيديو حفل التكريم 1446'],
                    ['type' => 'image', 'caption' => 'صور من المسابقة'],
                ],
            ],
            [
                'title' => 'مسابقة حفظ القرآن الكريم للأطفال',
                'description' => 'مسابقة حفظ القرآن الكريم للأطفال لعام 1447 هـ. أحدث مسابقة في سلسلة مسابقات حفظ القرآن الكريم. تميزت بمشاركة قياسية من الأطفال المبدعين.',
                'hijri_year' => '1447',
                'start_date' => '2025-06-01',
                'end_date' => '2025-08-30',
                'is_active' => true,
                'display_order' => 3,
                'winners' => [
                    ['position' => 1, 'category' => 'حفظ كامل', 'notes' => 'إنجاز استثنائي في حفظ القرآن الكريم'],
                    ['position' => 2, 'category' => 'حفظ كامل', 'notes' => 'تميز في الحفظ والتلاوة'],
                    ['position' => 3, 'category' => 'حفظ جزئي', 'notes' => 'حفظ 25 جزء مع التجويد'],
                ],
                'media' => [
                    ['type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'caption' => 'فيديو المسابقة 1447'],
                    ['type' => 'image', 'caption' => 'لحظات من المسابقة'],
                ],
            ],
        ];

        $personIndex = 0;
        foreach ($competitions as $competitionData) {
            // إنشاء أو تحديث المسابقة
            $competition = QuranCompetition::updateOrCreate(
                [
                    'hijri_year' => $competitionData['hijri_year']
                ],
                [
                    'title' => $competitionData['title'],
                    'description' => $competitionData['description'],
                    'hijri_year' => $competitionData['hijri_year'],
                    'start_date' => $competitionData['start_date'],
                    'end_date' => $competitionData['end_date'],
                    'is_active' => $competitionData['is_active'],
                    'display_order' => $competitionData['display_order'],
                ]
            );

            // حذف الفائزين والوسائط القديمة إذا كانت موجودة
            $competition->winners()->delete();
            $competition->media()->delete();

            // إضافة الفائزين
            if (isset($competitionData['winners'])) {
                foreach ($competitionData['winners'] as $winnerData) {
                    if ($personIndex < $persons->count()) {
                        QuranCompetitionWinner::create([
                            'competition_id' => $competition->id,
                            'person_id' => $persons[$personIndex]->id,
                            'position' => $winnerData['position'],
                            'category' => $winnerData['category'] ?? null,
                            'notes' => $winnerData['notes'] ?? null,
                        ]);
                        $personIndex++;
                    }
                }
            }

            // إضافة الوسائط
            if (isset($competitionData['media'])) {
                $sortOrder = 1;
                foreach ($competitionData['media'] as $mediaData) {
                    QuranCompetitionMedia::create([
                        'competition_id' => $competition->id,
                        'media_type' => $mediaData['type'],
                        'youtube_url' => $mediaData['type'] === 'youtube' ? $mediaData['youtube_url'] : null,
                        'caption' => $mediaData['caption'] ?? null,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        }

        $this->command->info('تم إضافة مسابقات القرآن الكريم بنجاح مع الفائزين والوسائط!');
        $this->command->info('عدد المسابقات: ' . count($competitions));
    }
}
