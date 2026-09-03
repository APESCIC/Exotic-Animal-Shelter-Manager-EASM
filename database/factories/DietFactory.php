<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\Diet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Diet>
 */
class DietFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => Animal::factory(),
            'name' => fake()->randomElement(['Insectivore mix', 'Leafy greens', 'Pellet + fresh veg', 'Nectar feed']),
            'details' => fake()->optional()->sentence(),
            'started_on' => fake()->optional()->date(),
            'ended_on' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
