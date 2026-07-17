<?php

namespace Database\Seeders;

use App\Models\ChecklistTemplate;
use Illuminate\Database\Seeder;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['item_text' => 'Submit national ID copy', 'display_order' => 1, 'is_required' => true],
            ['item_text' => 'Sign NDA', 'display_order' => 2, 'is_required' => true],
            ['item_text' => 'Submit bank details', 'display_order' => 3, 'is_required' => true],
            ['item_text' => 'Attend organization orientation', 'display_order' => 4, 'is_required' => true],
            ['item_text' => 'Receive department briefing', 'display_order' => 5, 'is_required' => true],
            ['item_text' => 'Receive system access', 'display_order' => 6, 'is_required' => true],
            ['item_text' => 'Read internship handbook', 'display_order' => 7, 'is_required' => false],
        ];

        foreach ($templates as $template) {
            ChecklistTemplate::firstOrCreate(
                [
                    'dept_id' => null,
                    'item_text' => $template['item_text'],
                ],
                [
                    'display_order' => $template['display_order'],
                    'is_required' => $template['is_required'],
                    'is_active' => true,
                ]
            );
        }
    }
}
