<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pregnancy>
 */
class PregnancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::inRandomOrder()->first()?->id,
            'edd' => $this->faker->dateTimeBetween('now', '+9 months')->format('Y-m-d'),
        ];
    }
}
