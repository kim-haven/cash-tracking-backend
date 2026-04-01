<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->string('initials');
            $table->decimal('cash_tip_amount', 12, 2)->default(0);
            $table->decimal('end_of_pay_period_total', 12, 2)->default(0);
            $table->decimal('cash_balance', 12, 2)->default(0);
            $table->date('date');
            $table->decimal('cash_tip', 12, 2)->default(0);
            $table->decimal('credit_tips', 12, 2)->default(0);
            $table->decimal('ach_tips', 12, 2)->default(0);
            $table->decimal('debit_tips', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};
