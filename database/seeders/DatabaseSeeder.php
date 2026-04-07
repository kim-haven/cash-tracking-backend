<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => UserRole::User,
        ]);

        // Plain password: User model casts `password` as `hashed` (single hash on save).
        // updateOrCreate so re-seeding resets name/password if the admin row already exists.
        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'admin'),
                'password' => env('ADMIN_PASSWORD', 'admin'),
                'role' => UserRole::Admin,
            ]
        );

        $this->call(RegisterDropSeeder::class);
        $this->call(DropSafeSeeder::class);
        $this->call(CashlessAtmEntrySeeder::class);
        $this->call(BlazeAccountingSummarySeeder::class);
    }
}
