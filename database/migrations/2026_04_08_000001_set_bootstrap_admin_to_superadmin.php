<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Promote the seeded bootstrap account (username `admin` in `users.name`) to superadmin.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereRaw('LOWER(TRIM(name)) = ?', ['admin'])
            ->update(['role' => 'superadmin']);
    }

    /**
     * Reverse the promotions (best-effort; only rows still superadmin and name admin).
     */
    public function down(): void
    {
        DB::table('users')
            ->whereRaw('LOWER(TRIM(name)) = ?', ['admin'])
            ->where('role', 'superadmin')
            ->update(['role' => 'admin']);
    }
};
