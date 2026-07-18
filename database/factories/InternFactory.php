<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Intern;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Intern>
 */
class InternFactory extends Factory
{
    protected $model = Intern::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->intern(),
            'dept_id' => Department::factory(),
            'supervisor_id' => User::factory()->supervisor(),
            'institution' => fake()->company(),
            'programme' => fake()->jobTitle(),
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ];
    }
}
