<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = Role::create(['name' => 'Admin']);
        $employee = Role::create(['name' => 'Employee']);
        $client = Role::create(['name' => 'Client']);

        $permissions = [
            'create user',
            'view all users',
            'view own profile',
            'edit all users',
            'edit own profile',
            'delete all user',

            // Project permissions
            'create project',
            'edit project',
            'delete project',
            'view all projects',
            'view own project'
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin->givePermissionTo($permissions);
        
        $employee->givePermissionTo([
            'view all projects',
        ]);

        $client->givePermissionTo([
            'view own project',
        ]);
    }
}
