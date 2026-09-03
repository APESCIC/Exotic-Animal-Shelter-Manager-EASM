<?php

namespace Database\Factories;

use App\Models\Animal;
use App\Models\AnimalObservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnimalObservation>
 */
class AnimalObservationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => Animal::factory(),
            'observed_on' => fake()->date(),
            'body' => fake()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
