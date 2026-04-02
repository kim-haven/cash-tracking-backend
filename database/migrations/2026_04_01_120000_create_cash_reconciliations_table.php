<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('controller');
            $table->decimal('cash_in', 12, 2)->default(0);
            $table->decimal('cash_refunds', 12, 2)->default(0);
            $table->decimal('cashless_atm_cash_back', 12, 2)->default(0);
            $table->decimal('reported_cash_collected', 12, 2)->default(0);
            $table->decimal('cash_collected', 12, 2)->default(0);
            $table->decimal('cash_difference', 12, 2)->default(0);
            $table->decimal('credit_difference', 12, 2)->default(0);
            $table->decimal('cashless_atm_difference', 12, 2)->default(0);
            $table->decimal('cash_vs_cashless_atm_difference', 12, 2)->default(0);
            $table->text('reason_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_reconciliations');
    }
};
