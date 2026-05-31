<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'address' => fake()->optional()->address(),
            'scheduled_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
            'status' => fake()->randomElement(Job::STATUSES),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
