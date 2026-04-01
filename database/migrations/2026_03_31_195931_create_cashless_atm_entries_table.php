<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cashless_atm_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('employee');
            $table->string('terminal');
            $table->decimal('debit_terminal_total_dispensed', 12, 2);
            $table->decimal('total_tips', 12, 2);
            $table->decimal('debit_total_sales', 12, 2);
            $table->decimal('total_cash_back', 12, 2);
            $table->decimal('blaze_total_cash_less_sales', 12, 2);
            $table->decimal('total_cash_less_atm_change', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashless_atm_entries');
    }
};
