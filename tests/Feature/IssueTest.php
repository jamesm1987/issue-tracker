<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Traits\CreateUser;
use App\Models\Project;
use App\Models\Issue;


class IssueTest extends TestCase
{

    use RefreshDatabase, CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
    }

    #[Test]
    public function a_user_can_view_an_issue(): void
    {
        $user = $this->login();

        $project = Project::factory()
            ->has(Issue::factory()->count(1))
            ->create();

        $issueId = $project->issues()->first()->id;

        $response = $this->getJson("/api/projects/$project->id/issues/$issueId");
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'status',
                'priority',
                'created_at',
                'updated_at',
            ],
        ]);
    
        $response->assertJsonPath('data.id', $issueId);
    }

    #[Test]
    public function a_user_can_create_an_issue(): void
    {
        $user = $this->login();
        $fixer = $this->login();
        $tester = $this->login();

        $project = Project::factory()->create();

        $payload = [
            'title' => 'Test Issue',
            'description' => 'This is a test issue',
            'status' => 'open',
            'priority' => 'medium',
            'created_by' => $user->id,
            'created_by_name' => $user->name,
            'fix_by' => $fixer->id,
            'test_by' => $tester->id,
        ];

        $response = $this->postJson("/api/projects/$project->id/issues", $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('issues', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'project_id' => $project->id,
        ]);

        
    }

    
}