<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use Faker\Factory as Faker;

class PersonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('ar_SA'); // استخدام النسخة العربية للسعودية

        // أسماء أولاد سعودية شائعة
        $maleNames = [
            'محمد',
            'أحمد',
            'علي',
            'خالد',
            'سعود',
            'عبدالله',
            'فيصل',
            'تركي',
            'نايف',
            'بندر'
        ];

        // أسماء بنات سعودية شائعة
        $femaleNames = [
            'نورة',
            'سارة',
            'أمينة',
            'لطيفة',
            'هند',
            'فاطمة',
            'الجوهرة',
            'لمى',
            'شريفة',
            'موضي'
        ];

        // إنشاء 10 أجداد (بدون آباء)
        $ancestors = [];
        for ($i = 0; $i < 10; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male'
                ? $faker->randomElement($maleNames)
                : $faker->randomElement($femaleNames);

            $birthDate = $faker->dateTimeBetween('-100 years', '-60 years');
            $deathDate = $faker->optional(0.3)->dateTimeBetween('-60 years', 'now');

            $ancestor = Person::create([
                'first_name' => $firstName,
                'last_name' => 'السريع',
                'birth_date' => $birthDate->format('Y-m-d'),
                'death_date' => $deathDate ? $deathDate->format('Y-m-d') : null,
                'gender' => $gender,
                'biography' => $faker->optional(0.7)->paragraph,
                'occupation' => $faker->optional(0.5)->jobTitle,
                'location' => $faker->city . '، السعودية',
            ]);

            $ancestors[] = $ancestor;
        }

        foreach ($ancestors as $ancestor) {
            // إنشاء عدد عشوائي من الأبناء لكل جد (بين 1 و 5)
            $numberOfChildren = rand(1, 5);
            $this->createChildren($ancestor, $numberOfChildren, $faker, 3, $maleNames, $femaleNames);
        }
    }

    protected function createChildren($parent, $count, $faker, $maxDepth, $maleNames, $femaleNames, $currentDepth = 1)
    {
        if ($currentDepth > $maxDepth) {
            return;
        }

        for ($i = 0; $i < $count; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male'
                ? $faker->randomElement($maleNames)
                : $faker->randomElement($femaleNames);

            $birthDate = $this->getChildBirthDate($parent->birth_date, $currentDepth, $faker);
            $deathDate = $faker->optional(0.1)->dateTimeBetween('-18 years', 'now');

            $child = Person::create([
                'first_name' => $firstName,
                'last_name' => 'السريع',
                'birth_date' => $birthDate,
                'death_date' => $deathDate ? $deathDate->format('Y-m-d') : null,
                'gender' => $gender,
                'biography' => $faker->optional(0.5)->paragraph,
                'occupation' => $faker->optional(0.4)->jobTitle,
                'location' => $faker->city . '، السعودية',
            ]);

            // إضافة الطفل تحت الأب باستخدام nested set
            $child->parent_id = $parent->id;
            $child->save();

            // إنشاء أحفاد (أبناء للأبناء)
            $numberOfGrandChildren = rand(0, 3);
            if ($numberOfGrandChildren > 0) {
                $this->createChildren($child, $numberOfGrandChildren, $faker, $maxDepth, $maleNames, $femaleNames, $currentDepth + 1);
            }
        }
    }

    protected function getChildBirthDate($parentBirthDate, $currentDepth, $faker)
    {
        $parentBirthYear = date('Y', strtotime($parentBirthDate));
        $minYear = $parentBirthYear + 18 + ($currentDepth * 20);
        $maxYear = $parentBirthYear + 45 + ($currentDepth * 20);

        return $faker->dateTimeBetween("$minYear-01-01", "$maxYear-12-31")->format('Y-m-d');
    }
}
