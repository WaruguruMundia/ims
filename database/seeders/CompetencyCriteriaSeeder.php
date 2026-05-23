<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetencyCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            [
                'name'        => 'Technical Skills',
                'description' => 'Ability to apply relevant technical knowledge to assigned tasks.',
                'max_score'   => 10,
            ],
            [
                'name'        => 'Communication',
                'description' => 'Clarity and professionalism in written and verbal communication.',
                'max_score'   => 10,
            ],
            [
                'name'        => 'Initiative',
                'description' => 'Willingness to take on tasks proactively without being directed.',
                'max_score'   => 10,
            ],
            [
                'name'        => 'Punctuality and Attendance',
                'description' => 'Consistency in reporting on time and meeting deadlines.',
                'max_score'   => 10,
            ],
            [
                'name'        => 'Teamwork and Collaboration',
                'description' => 'Ability to work effectively within a team environment.',
                'max_score'   => 10,
            ],
            [
                'name'        => 'Problem Solving',
                'description' => 'Capacity to identify issues and propose or implement solutions.',
                'max_score'   => 10,
            ],
        ];

        foreach ($criteria as $criterion) {
            DB::table('t_competency_criteria')->updateOrInsert(
                ['name' => $criterion['name']],
                array_merge($criterion, [
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
