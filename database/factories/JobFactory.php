<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
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
            'title' => fake()->jobTitle, // Use a more appropriate method for job titles
            'user_id' =>'3',
            'type_job_id' => rand(1, 5),
            'category_id' => rand(1, 5),
            'location' => fake()->city,
            'company_name' => fake()->company, // Use company name for company_name
            'vacancy' => rand(1, 5),
            'description' => fake()->text,
            'experience' => rand(1, 10),
        ];
    }
}