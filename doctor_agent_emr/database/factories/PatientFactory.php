<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstNameFemale,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->unique()->numerify('05########'),
            'lhims_number' => $this->faker->unique()->bothify('LHIMS###'),
            'notes' => $this->faker->optional()->sentence,
            'address' => $this->faker->address,
            'dob' => $this->faker->date(),
            'gender' => 'Female'
        ];
    }
}
