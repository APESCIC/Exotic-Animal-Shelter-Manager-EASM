<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\Animal;
use App\Models\Movement;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movement>
 */
class MovementFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => Animal::factory(),
            'type' => MovementType::Intake,
            'moved_at' => fake()->date(),
            'person_id' => null,
            'reason' => fake()->optional()->sentence(3),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function forPerson(?Person $person = null): static
    {
        return $this->state(fn (): array => [
            'person_id' => $person?->id ?? Person::factory(),
        ]);
    }

    public function ofType(MovementType $type): static
    {
        return $this->state(fn (): array => ['type' => $type]);
    }
}
