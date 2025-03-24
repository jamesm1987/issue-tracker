<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Traits\CreateUser;


class ProjectTest extends TestCase
{

    use RefreshDatabase, CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
    }

    #[Test]
    public function an_admin_can_create_a_project(): void
    {
        $this->login();
        
        $response = $this->postJson('/api/projects', [
            'name' => 'New Project'
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure(['project' => 'project']);
        
        $this->assertDatabaseHas('projects', [
            'name' => 'New Project'
        ]);
    }
}
