<?php

namespace Database\Factories;

use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opportunity>
 */
class OpportunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'  => $this->faker->sentence,
            'status' => $this->faker->randomElement(['open', 'won', 'lost']),
            'amount' => $this->faker->numberBetween(1000, 10000),
        ];
    }

    public function deleted($deleted_by = null): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }
}
