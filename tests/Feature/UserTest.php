<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Traits\CreateUser;
use App\Models\Project;
use App\Models\User;


class UserTest extends TestCase
{

    use RefreshDatabase, CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
    }

    #[Test]
    public function a_user_is_assigned_to_projects()
    {
        $adminUser = $this->login();
        
        $clientUser = User::factory()->create();


        $projects = Project::factory()->count(5)->create();


        $response = $this->postJson("/api/users/{$clientUser->id}/projects", [
            'project_ids' => $projects->pluck('id')->toArray()
        ]);

        foreach ($projects as $project) {
            $this->assertDatabaseHas('project_user', [
                'user_id' => $clientUser->id,
                'project_id' => $project->id,
            ]);
        }
    }

    #[Test]
    public function a_user_project_assignement_can_be_updated()
    {
        $adminUser = $this->login();
        
        $clientUser = User::factory()->create();
    
        $projects = Project::factory()->count(5)->create();
    
        $this->postJson("/api/users/{$clientUser->id}/projects", [
            'project_ids' => $projects->pluck('id')->toArray()
        ]);
    
        foreach ($projects as $project) {
            $this->assertDatabaseHas('project_user', [
                'user_id' => $clientUser->id,
                'project_id' => $project->id,
            ]);
        }
    
        $updatedProjectIds = $projects->pluck('id')->slice(0, -1)->toArray();
    
        $this->postJson("/api/users/{$clientUser->id}/projects", [
            'project_ids' => $updatedProjectIds
        ]);
    
        $this->assertDatabaseMissing('project_user', [
            'user_id' => $clientUser->id,
            'project_id' => $projects->last()->id
        ]);
    
        foreach ($updatedProjectIds as $projectId) {
            $this->assertDatabaseHas('project_user', [
                'user_id' => $clientUser->id,
                'project_id' => $projectId,
            ]);
        }
    }

}