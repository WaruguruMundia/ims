<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Finance',                'code' => 'FIN'],
            ['name' => 'Human Resources',        'code' => 'HR'],
            ['name' => 'Operations',             'code' => 'OPS'],
        ];

        foreach ($departments as $dept) {
            DB::table('t_departments')->updateOrInsert(
                ['code' => $dept['code']],
                array_merge($dept, [
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
