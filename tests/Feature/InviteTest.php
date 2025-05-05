<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Traits\CreateUser;
use Spatie\Permission\Models\Role;
use App\Models\Invite;
use Carbon\Carbon;
use App\Mail\InviteUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteTest extends TestCase
{

    use RefreshDatabase, CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
    }

    #[Test]
    public function an_admin_user_can_create_an_invite(): void
    {
        $admin = $this->login();

        $inviteEmail = 'newuser@example.com';

        $response = $this->postJson('/api/invites', [
            'email' => $inviteEmail,
            'role_id' => Role::where('name', 'Client')->first()->id,
            'invited_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('invitations', [
            'email' => $inviteEmail
        ]);
    }

    #[Test]
    public function it_sends_an_email_when_an_invite_is_created()
    {
        Mail::fake();

        $admin = $this->login();
    
        $inviteEmail = 'newuser@example.com';
    
        $response = $this->postJson('/api/invites', [
            'email' => $inviteEmail,
            'role_id' => Role::where('name', 'Client')->first()->id,
            'invited_by' => $admin->id,
        ]);
    
        $this->assertDatabaseHas('invitations', [
            'email' => $inviteEmail
        ]);
    
        Mail::assertSent(InviteUserMail::class, function ($mail) use ($inviteEmail) {
            return $mail->hasTo($inviteEmail);
        });
    }

    #[Test]
    public function it_validates_invite_token()
    {
        $admin = $this->login();

        $inviteEmail = 'newuser@example.com';

        $this->postJson('/api/invites', [
            'email' => $inviteEmail,
            'role_id' => Role::where('name', 'Client')->first()->id,
            'invited_by' => $admin->id, 
        ]);

        $invite = Invite::where('email', $inviteEmail)->first();

        $response = $this->getJson("/api/invites/{$invite->token}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'email',
            'expires_at',
        ]);
    }

    #[Test]
    public function it_rejects_an_expired_invite_token()
    {
        $admin = $this->login();

        $invite = Invite::create([
            'email' => 'expired@example.com',
            'role_id' => Role::where('name', 'Client')->first()->id,
            'invited_by' => $admin->id,
            'token' => Str::random(10),
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->getJson("/api/invites/{$invite->token}");

        $response->assertStatus(410);

    }
    
    #[Test]
    public function it_rejects_an_invalid_invite_token()
    {
        $admin = $this->login();

        $invite = Invite::create([
            'email' => 'expired@example.com',
            'role_id' => Role::where('name', 'Client')->first()->id,
            'invited_by' => $admin->id,
            'token' => Str::random(10),
            'expires_at' => now()->addDays(7),
        ]);

        $invalidToken = Str::random(8);

        $response = $this->getJson("/api/invites/{$invalidToken}");

        $response->assertStatus(404);

    }

}
