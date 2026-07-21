<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,              // no dependencies
            DepartmentSeeder::class,        // no dependencies
            CompetencyCriteriaSeeder::class,// no dependencies
            UserSeeder::class,              // needs roles
            SupervisorProfileSeeder::class, // needs users + departments
            InternProfileSeeder::class,     // needs users + departments + supervisors
            ChecklistTemplateSeeder::class,
            DummyDataSeeder::class
        ]);
    }
}
