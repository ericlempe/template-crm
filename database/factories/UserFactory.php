<?php

namespace Database\Factories;

use App\Enums\Can;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => 'password',
            'remember_token'    => Str::random(10),
            'created_at'        => fake()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withPermission(Can $key): static
    {
        return $this->afterCreating(function (User $user) use ($key) {
            $user->givePermissionTo($key);
        });
    }

    public function withValidationCode(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
            'validation_code'   => random_int(100000, 999999),
        ]);
    }

    public function admin(): static
    {
        return $this->afterCreating(fn (User $user) => $user->givePermissionTo(Can::BE_AN_ADMIN));
    }

    public function deleted($deleted_by = null): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
            'deleted_by' => $deleted_by ?? User::factory()->admin()->create()->id,
        ]);
    }
}
