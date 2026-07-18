<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => Role::firstOrCreate(['slug' => 'intern'], ['name' => 'Intern'])->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrator'])->id,
        ]);
    }

    public function supervisor(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::firstOrCreate(['slug' => 'supervisor'], ['name' => 'Supervisor'])->id,
        ]);
    }

    public function intern(): static
    {
        return $this->state(fn () => [
            'role_id' => Role::firstOrCreate(['slug' => 'intern'], ['name' => 'Intern'])->id,
        ]);
    }
}
