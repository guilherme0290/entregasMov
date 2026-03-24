<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'trade_name' => fake()->company(),
            'tax_id' => fake()->unique()->numerify('##############'),
            'phone' => fake()->numerify('(67) 99999-####'),
            'email' => fake()->companyEmail(),
            'is_active' => true,
        ];
    }
}
