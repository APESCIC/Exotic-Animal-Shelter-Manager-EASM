<?php

namespace Database\Factories;

use App\Enums\LostFoundType;
use App\Models\LostFoundReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LostFoundReport>
 */
class LostFoundReportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(LostFoundType::cases()),
            'species' => fake()->randomElement(['Bearded dragon', 'Corn snake', 'African grey parrot', 'Sulcata tortoise']),
            'colour' => fake()->optional()->safeColorName(),
            'markings' => fake()->optional()->sentence(3),
            'identifying_code' => fake()->optional()->bothify('LF-####'),
            'location_area' => fake()->optional()->city(),
            'reported_at' => fake()->date(),
            'person_id' => null,
            'matched_animal_id' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function lost(): static
    {
        return $this->state(fn (): array => ['type' => LostFoundType::Lost]);
    }

    public function found(): static
    {
        return $this->state(fn (): array => ['type' => LostFoundType::Found]);
    }
}
