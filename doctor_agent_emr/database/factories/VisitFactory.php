<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pregnancy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pregnancy_id' => Pregnancy::inRandomOrder()->first()?->id,
            'staff_id' => User::inRandomOrder()->first()?->id,
            'reason' => $this->faker->optional()->paragraph,
        ];
    }
}
