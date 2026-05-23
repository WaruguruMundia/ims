<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupervisorProfileSeeder extends Seeder
{
    public function run(): void
    {
        $supervisorUser = DB::table('t_users')
            ->where('email', 'supervisor@ims.test')
            ->first();

        $itDept = DB::table('t_departments')
            ->where('code', 'IT')
            ->first();

        if (!$supervisorUser || !$itDept) {
            $this->command->warn('SupervisorProfileSeeder: required user or department not found. Run UserSeeder and DepartmentSeeder first.');
            return;
        }

        DB::table('t_supervisors')->updateOrInsert(
            ['user_id' => $supervisorUser->id],
            [
                'dept_id'              => $itDept->id,
                'employee_number'      => 'EMP-001',
                'max_intern_capacity'  => 5,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]
        );
    }
}
