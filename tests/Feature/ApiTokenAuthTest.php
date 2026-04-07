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
}
