<?php

namespace Tests\Traits;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\Sanctum;

trait CreateUser
{
    public function login($role = 'Admin')
    {
        $user = User::factory()->create();
        $role = Role::where('name', $role)->firstOrFail();

        $user->assignRole($role);

        Sanctum::actingAs($user, ['*']);

        return $user;
    }
}