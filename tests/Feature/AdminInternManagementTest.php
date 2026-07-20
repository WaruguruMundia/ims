<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Intern;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInternManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_render_intern_edit_page(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = Intern::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.interns.edit', $intern));

        $response->assertOk();
        $response->assertSee($intern->user->name);
    }

    public function test_admin_can_update_intern_details(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();
        $department = Department::factory()->create();
        $intern = Intern::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.interns.update', $intern), [
            'name' => 'Updated Intern Name',
            'email' => 'updated_intern@example.com',
            'dept_id' => $department->id,
            'supervisor_id' => $supervisor->id,
            'institution' => 'New University',
            'programme' => 'New Degree',
            'start_date' => '2026-08-01',
            'end_date' => '2026-11-01',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('t_users', [
            'id' => $intern->user_id,
            'name' => 'Updated Intern Name',
            'email' => 'updated_intern@example.com',
        ]);

        $this->assertDatabaseHas('t_interns', [
            'id' => $intern->id,
            'dept_id' => $department->id,
            'supervisor_id' => $supervisor->id,
            'institution' => 'New University',
            'programme' => 'New Degree',
            'start_date' => '2026-08-01 00:00:00',
            'end_date' => '2026-11-01 00:00:00',
        ]);
    }

    public function test_admin_can_delete_intern(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = Intern::factory()->create();
        $userId = $intern->user_id;
        $internId = $intern->id;

        $response = $this->actingAs($admin)->delete(route('admin.interns.destroy', $intern));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseMissing('t_users', ['id' => $userId]);
        $this->assertDatabaseMissing('t_interns', ['id' => $internId]);
    }

    public function test_non_admin_cannot_access_intern_management(): void
    {
        $internUser = User::factory()->intern()->create();
        $intern = Intern::factory()->create();

        $this->actingAs($internUser)
            ->get(route('admin.interns.edit', $intern))
            ->assertForbidden();

        $this->actingAs($internUser)
            ->put(route('admin.interns.update', $intern), [])
            ->assertForbidden();

        $this->actingAs($internUser)
            ->delete(route('admin.interns.destroy', $intern))
            ->assertForbidden();
    }
}
