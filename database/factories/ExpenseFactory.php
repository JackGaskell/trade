<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id' => null,
            'expense_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'supplier' => fake()->company(),
            'description' => fake()->optional()->sentence(),
            'category' => fake()->randomElement(Expense::CATEGORIES),
            'amount' => fake()->randomFloat(2, 10, 500),
            'vat_amount' => null,
        ];
    }

    public function withVat(): static
    {
        return $this->state(function (array $attributes) {
            $amount = $attributes['amount'] ?? fake()->randomFloat(2, 10, 500);

            return [
                'amount' => $amount,
                'vat_amount' => round($amount * 0.2 / 1.2, 2),
            ];
        });
    }
}
