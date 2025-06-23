<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $birthDate = $this->faker->dateTimeBetween('-100 years', '-20 years');
        
        // 20% chance of having a death date
        $deathDate = null;
        if ($this->faker->boolean(20)) {
            $deathDate = $this->faker->dateTimeBetween($birthDate, 'now');
        }
        
        return [
            'first_name' => $gender === 'male' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $birthDate,
            'death_date' => $deathDate,
            'gender' => $gender,
            'photo_url' => null, // We'll handle this separately if needed
            'biography' => $this->faker->paragraphs(3, true),
            'occupation' => $this->faker->jobTitle(),
            'location' => $this->faker->city() . ', ' . $this->faker->country(),
        ];
    }
}
