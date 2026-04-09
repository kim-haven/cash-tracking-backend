<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_requires_authentication(): void
    {
        $this->getJson('/api/notifications')->assertUnauthorized();
    }

    public function test_notifications_returns_empty_list(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/notifications');

        $response->assertOk()
            ->assertJsonPath('data', [])
            ->assertJsonStructure(['data', 'server_client_version']);
    }

    public function test_mark_read_updates_rows(): void
    {
        $user = User::factory()->create(['role' => UserRole::User]);
        $token = $user->createToken('api')->plainTextToken;

        $n = UserNotification::query()->create([
            'user_id' => $user->id,
            'type' => 'role_changed',
            'body' => 'Your role was updated to Manager.',
            'dedupe_key' => null,
        ]);

        $this->withToken($token)
            ->patchJson('/api/notifications/read', ['ids' => [$n->id]])
            ->assertOk();

        $this->assertNotNull($n->fresh()->read_at);
    }
}
