<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashlessAtmEntrySeeder extends Seeder
{
    /**
     * Sample row from the "Cashless ATM (Debit)" tab (dummy Belmont 2025 Cash Tracking).
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'date' => '2025-01-01',
                'employee' => 'Chirs',
                'terminal' => 'reg2am',
                'debit_terminal_total_dispensed' => '1165.00',
                'total_tips' => '30.00',
                'debit_total_sales' => '1086.41',
                'total_cash_back' => '48.59',
                'blaze_total_cash_less_sales' => '1086.41',
                'total_cash_less_atm_change' => '48.59',
                'notes' => null,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('cashless_atm_entries')->insert([
                ...$row,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
