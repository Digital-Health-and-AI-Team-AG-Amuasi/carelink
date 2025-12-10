<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\VitalType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VitalTypeFactory extends Factory
{
    protected $model = VitalType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'units_of_measurement' => json_encode($this->faker->words()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
