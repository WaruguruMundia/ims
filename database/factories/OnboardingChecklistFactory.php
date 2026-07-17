<?php

namespace Database\Factories;

use App\Models\Intern;
use Illuminate\Database\Eloquent\Factories\Factory;

class OnboardingChecklistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'intern_id' => Intern::factory(),
            'checklist_template_id' => null,
            'item' => fake()->sentence(3),
            'is_required' => true,
            'is_completed' => false,
            'completed_at' => null,
            'completed_by' => null,
        ];
    }
}
