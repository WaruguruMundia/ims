<?php

namespace Tests\Feature;

use Database\Seeders\ChecklistTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistTemplateSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_checklist_templates_can_be_seeded(): void
    {
        $this->seed(ChecklistTemplateSeeder::class);

        $this->assertDatabaseHas('t_checklist_templates', [
            'item_text' => 'Submit national ID copy',
            'display_order' => 1,
            'is_required' => true,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('t_checklist_templates', [
            'item_text' => 'Read internship handbook',
            'display_order' => 7,
            'is_required' => false,
            'is_active' => true,
        ]);
    }

    public function test_checklist_template_seeder_is_idempotent(): void
    {
        $this->seed(ChecklistTemplateSeeder::class);
        $this->seed(ChecklistTemplateSeeder::class);

        $this->assertSame(
            1,
            \App\Models\ChecklistTemplate::query()
                ->where('item_text', 'Submit national ID copy')
                ->count()
        );
    }
}
