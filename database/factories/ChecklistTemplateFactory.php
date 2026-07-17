<?php

namespace Database\Factories;

use App\Models\ChecklistTemplate;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChecklistTemplate>
 */
class ChecklistTemplateFactory extends Factory
{
    protected $model = ChecklistTemplate::class;

    public function definition(): array
    {
        return [
            'dept_id' => null,
            'item_text' => fake()->sentence(4),
            'display_order' => fake()->numberBetween(1, 20),
            'is_required' => true,
            'is_active' => true,
        ];
    }

    public function forDepartment(?Department $department = null): static
    {
        return $this->state(fn () => [
            'dept_id' => $department?->id ?? Department::factory(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }

    public function optional(): static
    {
        return $this->state(fn () => [
            'is_required' => false,
        ]);
    }
}
