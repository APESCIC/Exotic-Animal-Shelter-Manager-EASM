<?php

namespace Database\Factories;

use App\Enums\MedicalRecordType;
use App\Models\Animal;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'animal_id' => Animal::factory(),
            'type' => fake()->randomElement(MedicalRecordType::cases()),
            'name' => fake()->randomElement(['Parasite screen', 'Ivermectin course', 'Vitamin A boost', 'Cloacal swab']),
            'due_on' => fake()->optional()->date(),
            'given_on' => null,
            'expires_on' => fake()->optional()->date(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function ofType(MedicalRecordType $type): static
    {
        return $this->state(fn (): array => ['type' => $type]);
    }

    public function given(): static
    {
        return $this->state(fn (): array => [
            'given_on' => fake()->date(),
        ]);
    }
}
