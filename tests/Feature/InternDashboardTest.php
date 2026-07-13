<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_dashboard_displays_checklists()
    {
        $internRole = Role::create(['name' => 'Intern', 'slug' => 'intern']);
        $supervisorRole = Role::create(['name' => 'Supervisor', 'slug' => 'supervisor']);
        $dept = \App\Models\Department::create(['name' => 'IT']);
        
        $supervisor = User::create([
            'name' => 'Test Supervisor',
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role_id' => $supervisorRole->id,
            'is_active' => true,
        ]);

        $supervisorProfileId = \Illuminate\Support\Facades\DB::table('t_supervisors')->insertGetId([
            'user_id' => $supervisor->id,
            'dept_id' => $dept->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Test Intern',
            'email' => 'intern@example.com',
            'password' => bcrypt('password'),
            'role_id' => $internRole->id,
            'is_active' => true,
        ]);
        
        $intern = Intern::create([
            'user_id' => $user->id,
            'dept_id' => $dept->id,
            'supervisor_id' => $supervisorProfileId,
            'institution' => 'Test Uni',
            'programme' => 'CS',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        $response = $this->actingAs($user)->get(route('intern.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('intern');
    }
}
