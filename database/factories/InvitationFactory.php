<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invite>
 */
class InviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $admin = Role::firstOrCreate(['name' => 'Admin']);

        $adminUser = User::role('Admin')->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($admin);

        $inviteRole = Role::all()->inRandomOrder()->first();

        return [
            'email' => fake()->unique()->safeEmail(),
            'token' => Str::random(10),
            'role' => $inviteRole->id,
            'invited_by' => $adminUser->id
        ];
    }
}
