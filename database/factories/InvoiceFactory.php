<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
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
            'due_date' => fake()->optional()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(Invoice::STATUSES),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
