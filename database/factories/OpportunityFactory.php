<?php

namespace Database\Factories;

use App\Models\{Customer, Opportunity};
use App\Traits\Factory\HasDeleted;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opportunity>
 */
class OpportunityFactory extends Factory
{
    use HasDeleted;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title'       => $this->faker->sentence,
            'status'      => $this->faker->randomElement(['open', 'won', 'lost']),
            'amount'      => $this->faker->numberBetween(1000, 10000),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
