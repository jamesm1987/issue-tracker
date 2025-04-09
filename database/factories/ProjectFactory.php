<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $role = Role::firstOrCreate(['name' => 'admin']);

        $user = User::role('admin')->inRandomOrder()->first() 
            ?? User::factory()->create()->assignRole($role);


        return [
            'name' => fake()->words(5, true),
            'created_by' => $user->id,
            'created_by_name' => $user->name
        ];
    }
}
