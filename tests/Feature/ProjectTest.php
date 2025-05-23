<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Traits\CreateUser;
use App\Models\Project;
use App\Models\Issue;


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
        $user = $this->login();
        
        $response = $this->postJson('/api/projects', [
            'name' => 'New Project',
            'created_by' => $user->id,
            'created_by_name' => $user->name
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure(['project' => 'project']);
        
        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'created_by' => $user->id,
            'created_by_name' => $user->name,
        ]);
    }

    #[Test]
    public function an_admin_can_edit_a_project(): void
    {
        $this->login();
        
        $project = Project::factory()->create();

        $updatedProject = [
            'name' => 'Updated project',
        ];
        
        $response = $this->putJson('/api/projects/' . $project->id, $updatedProject);
        
        $response->assertStatus(200)
            ->assertJsonStructure(['project' => 'project']);
        
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated project'
        ]);
    }

    #[Test]
    public function an_admin_can_delete_a_project(): void
    {
        $this->login();
        
        $project = Project::factory()->create();
        
        $this->assertDatabaseHas('projects', [
            'name' => $project->name
        ]);
    
        $response = $this->deleteJson('/api/projects/' . $project->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('projects', [
            'name' => $project->name
        ]);
    }

    #[test]
    public function non_client_user_can_view_all_projects(): void
    {
        $this->login();

        Project::factory()->count(10)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200);

        $response->assertJsonCount(10, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'created_at', 'deleted_at', 'updated_at'],
            ]
        ]);
    }

    #[test]
    public function view_all_project_issues()
    {
        $this->login();

        $project = Project::factory()
            ->has(Issue::factory()->count(3))
            ->create();
        
        $response = $this->getJson("/api/projects/$project->id");
        $response->assertStatus(200);

        $response->assertJsonPath('data.id', $project->id);
        $response->assertJsonCount(3, 'data.issues');
        
        foreach ($project->issues as $issue) {
            $response->assertJsonFragment(['id' => $issue->id]);
        }
    }
}