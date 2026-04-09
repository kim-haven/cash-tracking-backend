<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->nullable()
                ->constrained('stores')
                ->restrictOnDelete();
            $table->date('date');
            $table->decimal('count_amount', 12, 2)->default(0);
            $table->decimal('change_in', 12, 2)->default(0);
            $table->decimal('change_out', 12, 2)->default(0);
            $table->string('description')->nullable();
            $table->decimal('deposit', 12, 2)->default(0);
            $table->decimal('picked_up', 12, 2)->default(0);
            $table->decimal('sum_of_pickups', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('difference', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_banks');
    }
};
