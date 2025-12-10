<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medication>
 */
class MedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visit_id' => Visit::inRandomOrder()->first()?->id,
            'drug_id' => Drug::inRandomOrder()->first()?->id,
            'frequency' => $this->faker->randomElement(['3 times daily', 'once a day', 'twice a day']),
            'duration' => $this->faker->randomElement([
                '3 days',
                '5 days',
                '1 week',
                '2 weeks',
                '1 month',
                'Until delivery',
                'As needed',
            ]),
        ];
    }
}
