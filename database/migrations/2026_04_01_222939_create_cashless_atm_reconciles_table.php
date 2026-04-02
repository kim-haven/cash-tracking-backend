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
        Schema::create('cashless_atm_reconciles', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('debit_total_sales', 14, 2);
            $table->decimal('blaze_total_cash_less_sales', 14, 2);
            $table->decimal('total_cashless_atm_tendered', 14, 2);
            $table->decimal('total_cash_less_atm_change', 14, 2);
            $table->decimal('total_cash_back', 14, 2);
            $table->text('notes')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by')->nullable();
            $table->text('delete_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashless_atm_reconciles');
    }
};
