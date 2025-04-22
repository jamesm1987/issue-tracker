<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Project;
use App\Enums\RoleNames;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $role = Role::firstOrCreate(['name' => RoleNames::ADMIN]);
        $developer = Role::firstOrCreate(['name' => RoleNames::DEVELOPER]);

        $user = User::role(RoleNames::ADMIN)->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($role);

        $fixer = User::role(RoleNames::DEVELOPER)->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($developer);

        return [
            'title' => fake()->words(5, true),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed', 'fixed', 'fix_not_confirmed', 'duplicate']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'project_id' => Project::factory(),
            'created_by' => $user->id,
            'created_by_name' => $user->name,
            'fix_by' => $fixer->id,
            'test_by' => $user->id,
        ];
    }
}
