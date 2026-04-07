<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Scope cash-tracking rows to a physical store (not the "All Stores" aggregate row).
     */
    public function up(): void
    {
        $tables = [
            'register_drops',
            'drop_safes',
            'expenses',
            'cashless_atm_entries',
            'cashless_atm_reconciles',
            'cash_reconciliations',
            'tips',
            'blaze_accounting_summary',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('store_id')
                    ->nullable()
                    ->constrained('stores')
                    ->restrictOnDelete();
            });
        }

        $physicalId = DB::table('stores')
            ->where('is_all_stores', false)
            ->orderBy('id')
            ->value('id');

        if ($physicalId === null) {
            return;
        }

        foreach ($tables as $table) {
            DB::table($table)->whereNull('store_id')->update(['store_id' => $physicalId]);
        }
    }

    public function down(): void
    {
        $tables = [
            'register_drops',
            'drop_safes',
            'expenses',
            'cashless_atm_entries',
            'cashless_atm_reconciles',
            'cash_reconciliations',
            'tips',
            'blaze_accounting_summary',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('store_id');
            });
        }
    }
};
