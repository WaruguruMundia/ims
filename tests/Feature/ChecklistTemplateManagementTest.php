<?php

namespace Tests\Feature;

use App\Models\ChecklistTemplate;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistTemplateManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_checklist_templates(): void
    {
        $admin = User::factory()->admin()->create();

        ChecklistTemplate::factory()->create([
            'item_text' => 'Submit national ID',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.checklist-templates.index'));

        $response->assertOk();
        $response->assertSee('Submit national ID');
    }

    public function test_admin_can_create_checklist_template(): void
    {
        $admin = User::factory()->admin()->create();
        $department = Department::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.checklist-templates.store'), [
                'dept_id' => $department->id,
                'item_text' => 'Attend orientation',
                'display_order' => 10,
                'is_required' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.checklist-templates.index'));

        $this->assertDatabaseHas('t_checklist_templates', [
            'dept_id' => $department->id,
            'item_text' => 'Attend orientation',
            'display_order' => 10,
            'is_required' => true,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_checklist_template(): void
    {
        $admin = User::factory()->admin()->create();

        $template = ChecklistTemplate::factory()->create([
            'item_text' => 'Old item',
            'display_order' => 1,
        ]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.checklist-templates.update', $template), [
                'dept_id' => null,
                'item_text' => 'Updated item',
                'display_order' => 5,
                'is_required' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.checklist-templates.index'));

        $this->assertDatabaseHas('t_checklist_templates', [
            'id' => $template->id,
            'item_text' => 'Updated item',
            'display_order' => 5,
        ]);
    }

    public function test_admin_can_deactivate_checklist_template(): void
    {
        $admin = User::factory()->admin()->create();

        $template = ChecklistTemplate::factory()->create([
            'is_active' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.checklist-templates.destroy', $template));

        $response->assertRedirect(route('admin.checklist-templates.index'));

        $this->assertDatabaseHas('t_checklist_templates', [
            'id' => $template->id,
            'is_active' => false,
        ]);
    }

    public function test_non_admin_cannot_manage_checklist_templates(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this
            ->actingAs($intern)
            ->get(route('admin.checklist-templates.index'));

        $response->assertForbidden();
    }
}
