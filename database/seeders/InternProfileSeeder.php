<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternProfileSeeder extends Seeder
{
    public function run(): void
    {
        $internUser = DB::table('t_users')
            ->where('email', 'intern@ims.test')
            ->first();

        $itDept = DB::table('t_departments')
            ->where('code', 'IT')
            ->first();

        $supervisorProfile = DB::table('t_supervisors')
            ->where('employee_number', 'EMP-001')
            ->first();

        if (!$internUser || !$itDept || !$supervisorProfile) {
            $this->command->warn('InternProfileSeeder: missing dependency. Check users, departments, and supervisors.');
            return;
        }

        DB::table('t_interns')->updateOrInsert(
            ['user_id' => $internUser->id],
            [
                'dept_id'        => $itDept->id,
                'supervisor_id'  => $supervisorProfile->id,
                'institution'    => 'Kabarak University',
                'programme'      => 'Bachelor of Science in Computer Science',
                'student_number' => 'CS/2021/001',
                'start_date'     => '2025-01-06',
                'end_date'       => '2025-04-04',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        );
    }
}

