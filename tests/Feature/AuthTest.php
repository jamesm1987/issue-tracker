<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Tests\Traits\CreateUser;


class AuthTest extends TestCase
{
    use RefreshDatabase, CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
    }
    
    #[Test]
    public function admin_can_add_user(): void
    {
        $user = $this->login();
        
        $role = Role::all()->pluck('name')->random();
        
        $response = $this->postJson('/api/register', [
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => $role
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => 'token']);

        
        $this->assertDatabaseHas('users', [
            'email' => 'user@user.com'
        ]);
    }

    #[Test]
    public function non_admin_cannot_add_user(): void
    {

        $userRole = Role::whereNot('name', 'Admin')->pluck('name')->random();

        $user = $this->login($userRole);
        
        $role = Role::all()->pluck('name')->random();
        
        $response = $this->postJson('/api/register', [
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => $role
        ]);

        $response->assertStatus(403)
        ->assertJson(['message' => 'Unauthorized']);
    }

    #[Test]
    public function user_cannot_be_registered_with_existing_email(): void
    {
        $user = $this->login();
    
        $role = Role::all()->pluck('name')->random();

        $existingUser = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
        ]);
    

        $existingUser->assignRole($role);
    
        $response = $this->postJson('/api/register', [
            'name' => 'New User',
            'email' => 'user@user.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => $role
        ]);


        $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
    }
   
    #[Test]
    public function user_can_login()
    {
        
        $user = User::factory()->create([
            'email' => 'user@user.com',
           'password' => bcrypt('password'),
       ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@user.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    #[Test]
    public function user_cannot_login_with_unvalid_credentials()
    {

        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    #[Test]
    public function user_can_logout()
    {
        $this->login();

        $response = $this->postJson('/api/logout');

    
        $response->assertOk();
    }
}
