<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Project;

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

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $developerRole = Role::firstOrCreate(['name' => 'Developer']);

        $adminUser = User::role('Admin')->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($adminRole);

        $fixerUser = User::role('Developer')->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($developerRole);

        return [
            'title' => fake()->words(5, true),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed', 'fixed', 'fix_not_confirmed', 'duplicate']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'project_id' => Project::factory(),
            'created_by' => $adminUser->id,
            'created_by_name' => $adminUser->name,
            'fix_by' => $fixerUser->id,
            'test_by' => $adminUser->id,
        ];
    }
}
