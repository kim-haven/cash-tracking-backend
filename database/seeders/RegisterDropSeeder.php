<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegisterDropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data from the "Register Drops" tab (dummy Belmont 2025 Cash Tracking).
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['date' => '2025-01-01', 'register' => 'reg2am', 'time_start' => '08:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg4am', 'time_start' => '08:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg2am', 'time_start' => '08:00:00', 'time_end' => '14:00:00', 'action' => 'mid out', 'cash_in' => '2098.56', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg4am', 'time_start' => '08:00:00', 'time_end' => '14:30:00', 'action' => 'mid out', 'cash_in' => '1511.38', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg2pm', 'time_start' => '15:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg4pm', 'time_start' => '15:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'jm', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg2pm', 'time_start' => '15:00:00', 'time_end' => '21:30:00', 'action' => 'z out', 'cash_in' => '1307.77', 'initials' => 'ce', 'notes' => null],
            ['date' => '2025-01-01', 'register' => 'reg3pm', 'time_start' => '15:00:00', 'time_end' => '22:00:00', 'action' => 'z out', 'cash_in' => '1464.03', 'initials' => 'ce', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg2am', 'time_start' => '08:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'cl', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg3am', 'time_start' => '08:00:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'cl', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg2am', 'time_start' => '08:00:00', 'time_end' => '21:30:00', 'action' => 'mid out', 'cash_in' => '550.75', 'initials' => 'ms', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg3am', 'time_start' => '08:00:00', 'time_end' => '13:55:00', 'action' => 'mid out', 'cash_in' => '1245.96', 'initials' => 'cl', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg3pm', 'time_start' => '14:45:00', 'time_end' => null, 'action' => 'reg open', 'cash_in' => '-170.00', 'initials' => 'ms', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg3pm', 'time_start' => '14:45:00', 'time_end' => '21:30:00', 'action' => 'z out', 'cash_in' => '1888.27', 'initials' => 'ms', 'notes' => null],
            ['date' => '2025-01-02', 'register' => 'reg2am', 'time_start' => '20:30:00', 'time_end' => null, 'action' => 'Reg Drop', 'cash_in' => '3360.00', 'initials' => 'ms', 'notes' => null],
        ];

        foreach ($rows as $row) {
            DB::table('register_drops')->insert([
                ...$row,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
