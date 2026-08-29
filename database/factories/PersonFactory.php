<?php

namespace Database\Factories;

use App\Enums\PersonCategory;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(PersonCategory::cases());

        return [
            'name' => fake()->name(),
            'category' => $category,
            'category_custom' => $category === PersonCategory::Custom ? fake()->word() : null,
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->e164PhoneNumber(),
            'address_line1' => fake()->optional()->streetAddress(),
            'address_line2' => null,
            'town_city' => fake()->optional()->city(),
            'county' => fake()->optional()->randomElement(['Greater London', 'Kent', 'Surrey', 'Essex']),
            'postcode' => fake()->optional()->postcode(),
            'banned' => false,
            'homechecked' => fake()->boolean(30),
            'flags' => fake()->optional()->word(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function banned(): static
    {
        return $this->state(fn (): array => ['banned' => true]);
    }
}
