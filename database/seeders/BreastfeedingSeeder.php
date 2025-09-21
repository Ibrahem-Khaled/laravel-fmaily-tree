<?php

namespace Database\Seeders;

use App\Models\Breastfeeding;
use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BreastfeedingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some females to be nursing mothers
        $nursingMothers = Person::where('gender', 'female')->take(3)->get();

        // Get some persons to be breastfed children
        $breastfedChildren = Person::take(5)->get();

        if ($nursingMothers->count() >= 2 && $breastfedChildren->count() >= 3) {
            // Create some breastfeeding relationships
            $relationships = [
                [
                    'nursing_mother_id' => $nursingMothers[0]->id,
                    'breastfed_child_id' => $breastfedChildren[0]->id,
                    'start_date' => Carbon::now()->subMonths(12),
                    'end_date' => Carbon::now()->subMonths(6),
                    'notes' => 'رضاعة طبيعية لمدة 6 أشهر',
                    'is_active' => false,
                ],
                [
                    'nursing_mother_id' => $nursingMothers[0]->id,
                    'breastfed_child_id' => $breastfedChildren[1]->id,
                    'start_date' => Carbon::now()->subMonths(8),
                    'end_date' => null,
                    'notes' => 'رضاعة مستمرة',
                    'is_active' => true,
                ],
                [
                    'nursing_mother_id' => $nursingMothers[1]->id,
                    'breastfed_child_id' => $breastfedChildren[2]->id,
                    'start_date' => Carbon::now()->subMonths(15),
                    'end_date' => Carbon::now()->subMonths(9),
                    'notes' => 'رضاعة مكملة مع الحليب الصناعي',
                    'is_active' => false,
                ],
            ];

            foreach ($relationships as $relationship) {
                Breastfeeding::create($relationship);
            }

            $this->command->info('تم إنشاء ' . count($relationships) . ' علاقة رضاعة تجريبية');
        } else {
            $this->command->warn('لا يوجد أشخاص كافيين لإنشاء علاقات رضاعة تجريبية');
        }
    }
}
