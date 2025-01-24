<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),

            'linkedin'  => 'https://www.linkedin.com/in/' . $this->faker->name(),
            'facebook'  => 'https://www.facebook.com/' . $this->faker->name(),
            'twitter'   => 'https://x.com/' . $this->faker->name(),
            'instagram' => 'https://www.instagram.com/' . $this->faker->name(),

            'address' => $this->faker->address(),
            'city'    => $this->faker->city(),
            'state'   => $this->faker->state(),
            'country' => $this->faker->country,
            'zip'     => $this->faker->postcode,

            'birthday' => $this->faker->date(),
            'gender'   => $this->faker->randomElement(['male', 'female', 'other']),

            'company'  => $this->faker->company,
            'position' => $this->faker->jobTitle,
        ];
    }
}
