<?php

namespace Database\Factories;

use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessProfile>
 */
class BusinessProfileFactory extends Factory
{
    protected $model = BusinessProfile::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'business_type' => BusinessProfile::TYPE_SOLE_TRADER,
            'trading_name' => fake()->company(),
            'address' => fake()->streetAddress()."\n".fake()->city()."\n".fake()->postcode(),
            'vat_registered' => false,
            'vat_number' => null,
            'utr' => fake()->numerify('##########'),
            'accounting_year_start_month' => 4,
            'accounting_year_start_day' => 6,
            'cis_registered' => false,
        ];
    }

    public function vatRegistered(): static
    {
        return $this->state(fn (array $attributes) => [
            'vat_registered' => true,
            'vat_number' => 'GB'.fake()->numerify('#########'),
        ]);
    }

    public function cisRegistered(): static
    {
        return $this->state(fn (array $attributes) => [
            'cis_registered' => true,
        ]);
    }
}
