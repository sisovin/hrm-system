<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'hired_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['HR', 'Engineering', 'Sales', 'Support']),
            'salary' => $this->faker->randomFloat(2, 2000, 15000),
            'status' => 'active',
        ];
    }
}
