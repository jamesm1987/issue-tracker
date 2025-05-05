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
        $projectManager = Role::create(['name' => 'Project Manager']);
        $developer = Role::create(['name' => 'Developer']);
        $client = Role::create(['name' => 'Client']);



        $permissions = [
            'create user',
            'view all users',
            'view own profile',
            'edit all users',
            'edit own profile',
            'delete all user',
            'invite user',

            // Project permissions
            'create project',
            'edit project',
            'delete project',
            'view all projects',
            'view own project',

            // Issue permissions
            'create issue',
            'edit issue',
            'view issue',
            'delete issue',

            // Client permission
            'edit client'
        ];


        $projectManagerPermissions = [
            'view all projects',
            'view all users',
            'view own profile',
            'edit all users',
            'edit own profile',
            'create project',
            'edit project',
            'delete project',
            'view all projects',
            'view own project',
            'invite user',
            'edit client',
        ];
        $developerPermissions = [
            'view all projects',
            'view all users',
            'view own profile',
            'edit own profile',
            'view all projects',
            'view own project',
            'create issue',
            'edit issue',
            'view issue',
        ];
        $clientPermissions = [
            'view own project',
            'edit own profile',
            'view own profile',
            'create issue on assigned projects',
            'edit issue on assigned projects',
            'view issue on assigned projects'
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin->givePermissionTo($permissions);
        
        $projectManager->givePermissionTo([
            'view all projects',
            'view all users',
            'invite user',
            'view own profile',
            'edit all users',
            'edit own profile',
            'create project',
            'edit project',
            'delete project',
            'view all projects',
            'view own project'
        ]);

        $developer->givePermissionTo([
            'view all projects',
            'view all users',
            'view own profile',
            'edit own profile',
            'view all projects',
            'view own project'
        ]);

        $client->givePermissionTo([
            'view own project',
            'edit own profile',
            'view own profile',
        ]);
    }
}
