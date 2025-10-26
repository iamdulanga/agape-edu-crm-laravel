<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(18, 35),
            'city' => $this->faker->city(),
            'passport' => $this->faker->randomElement(['yes', 'no']),
            'inquiry_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'study_level' => $this->faker->randomElement(['foundation', 'diploma', 'bachelor', 'master', 'phd']),
            'priority' => $this->faker->randomElement(['very_high', 'high', 'medium', 'low', 'very_low']),
            'preferred_universities' => $this->faker->sentence(),
            'special_notes' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['new', 'contacted', 'qualified', 'converted', 'rejected']),
        ];
    }
}
