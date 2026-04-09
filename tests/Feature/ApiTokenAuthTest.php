<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_bearer_token_authenticates_cashtrack_route(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        $store = Store::query()->where('is_all_stores', false)->firstOrFail();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/cashtrack/daily-summaries?store_id='.$store->id);

        $response->assertOk();
    }

    public function test_register_creates_user_with_token_and_user_role(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'New User',
            'email' => 'new@example.com',
            'password' => 'secretpass',
            'password_confirmation' => 'secretpass',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.name', 'New User')
            ->assertJsonPath('user.email', 'new@example.com')
            ->assertJsonPath('user.role', 'user')
            ->assertJsonStructure(['token', 'access_token', 'user']);

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'role' => 'user',
        ]);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'username' => 'Other',
            'email' => 'taken@example.com',
            'password' => 'secretpass',
            'password_confirmation' => 'secretpass',
        ]);

        $response->assertUnprocessable();
    }

    public function test_change_password_requires_valid_current_password(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/auth/change-password', [
            'current_password' => 'wrong',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertUnprocessable();
    }

    public function test_change_password_updates_password(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->withToken($token)->postJson('/api/auth/change-password', [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])->assertOk();

        $this->postJson('/api/auth/login', [
            'username' => $user->email,
            'password' => 'newpassword',
        ])->assertOk();
    }
}
