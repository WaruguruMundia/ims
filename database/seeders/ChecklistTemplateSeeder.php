<?php

namespace Database\Seeders;

use App\Models\ChecklistTemplate;
use App\Models\Department;
use Illuminate\Database\Seeder;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $globalItems = [
            ['item_text' => 'Sign NDA / confidentiality agreement', 'display_order' => 1, 'is_required' => true],
            ['item_text' => 'IT account and email provisioned', 'display_order' => 2, 'is_required' => true],
            ['item_text' => 'Review employee handbook', 'display_order' => 3, 'is_required' => false],
            ['item_text' => 'Introduced to supervisor', 'display_order' => 4, 'is_required' => true],
        ];

        foreach ($globalItems as $item) {
            ChecklistTemplate::create($item + ['dept_id' => null, 'is_active' => true]);
        }

        // Adjust department names to match what you actually seeded
        $departmentItems = [
            'IT' => [
                ['item_text' => 'Development environment set up', 'display_order' => 1, 'is_required' => true],
            ],
            'HR' => [
                ['item_text' => 'Reviewed HR policies and procedures', 'display_order' => 1, 'is_required' => true],
            ],
        ];

        foreach ($departmentItems as $deptName => $items) {
            $department = Department::where('name', $deptName)->first();
            if (!$department) continue;

            foreach ($items as $item) {
                ChecklistTemplate::create($item + ['dept_id' => $department->id, 'is_active' => true]);
            }
        }
    }
}
