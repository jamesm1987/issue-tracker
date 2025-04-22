<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\RoleNames;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $admin = Role::create(['name' => RoleNames::ADMIN]);
        $projectManager = Role::create(['name' => RoleNames::PROJECT_MANAGER]);
        $developer = Role::create(['name' => RoleNames::DEVELOPER]);
        $client = Role::create(['name' => RoleNames::CLIENT]);

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
            'view own project',

            // Issue permissions
            'create issue',
            'edit issue',
            'view issue',
            'delete issue',

            // Client permission
            'invite client',
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
            'invite client',
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
