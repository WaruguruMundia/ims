<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('t_roles')->pluck('id', 'slug');

        $users = [
            [
                'role_id'  => $roles['admin'],
                'name'     => 'System Administrator',
                'email'    => 'admin@ims.test',
                'password' => Hash::make('Admin@1234'),
                'is_active' => true,
            ],
            [
                'role_id'  => $roles['supervisor'],
                'name'     => 'Jane Supervisor',
                'email'    => 'supervisor@ims.test',
                'password' => Hash::make('Super@1234'),
                'is_active' => true,
            ],
            [
                'role_id'  => $roles['intern'],
                'name'     => 'John Intern',
                'email'    => 'intern@ims.test',
                'password' => Hash::make('Intern@1234'),
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            DB::table('t_users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
