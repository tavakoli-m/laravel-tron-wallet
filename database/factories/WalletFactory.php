<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => fake()->unique()->regexify('[A-Za-z0-9]{34}'),
            'key' => fake()->sha256,
            'user_id' => \App\Models\User::factory(),
            'balance' => fake()->randomFloat(8, 0, 1000000),
        ];
    }
}
