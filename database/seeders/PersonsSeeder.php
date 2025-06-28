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
        $faker = Faker::create('ar_SA'); // النسخة العربية السعودية

        // أسماء أولاد سعودية
        $maleNames = [
            'حمدان',
            'عبدالعزيز',
            'محمد',
            'عبدالمحسن',
            'ناصر',
            'ابراهيم',
            'عبدالله',
        ];

        // أسماء بنات سعودية
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

        // 1️⃣ إنشاء 7 رجال أجداد
        for ($i = 0; $i < 7; $i++) {
            $birthDate = $faker->dateTimeBetween('-100 years', '-60 years');
            $deathDate = $faker->optional(0.3)->dateTimeBetween('-60 years', 'now');

            Person::create([
                'first_name' => $faker->randomElement($maleNames),
                'birth_date' => $birthDate->format('Y-m-d'),
                'death_date' => $deathDate ? $deathDate->format('Y-m-d') : null,
                'gender' => 'male',
                'biography' => $faker->optional(0.7)->paragraph,
                'occupation' => $faker->optional(0.5)->jobTitle,
                'location' => $faker->city . '، السعودية',
            ]);
        }

        // 2️⃣ إنشاء 10 نساء عشوائيات
        for ($i = 0; $i < 10; $i++) {
            $birthDate = $faker->dateTimeBetween('-80 years', '-20 years');
            $deathDate = $faker->optional(0.2)->dateTimeBetween('-20 years', 'now');

            Person::create([
                'first_name' => $faker->randomElement($femaleNames),
                'birth_date' => $birthDate->format('Y-m-d'),
                'death_date' => $deathDate ? $deathDate->format('Y-m-d') : null,
                'gender' => 'female',
                'biography' => $faker->optional(0.7)->paragraph,
                'occupation' => $faker->optional(0.5)->jobTitle,
                'mother_id' => $faker->optional(0.2)->numberBetween(1, 7),
                'location' => $faker->city . '، السعودية',
            ]);
        }
    }
}
