<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'description' => fake()->optional()->paragraph(),
            'valid_until' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(Quote::STATUSES),
        ];
    }
}
