<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => \App\Models\Wallet::factory(),
            'amount' => fake()->randomFloat(8, 0, 1000000),
            'tx_id' => fake()->sha256,
            'type' => fake()->randomElement(['deposit', 'withdrawal']),
        ];
    }
}
