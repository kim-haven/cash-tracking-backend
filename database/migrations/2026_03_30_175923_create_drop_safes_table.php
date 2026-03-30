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
        Schema::create('drop_safes', function (Blueprint $table) {
            $table->id();
            $table->string('bag_no', 32);
            $table->date('prepared_date');
            $table->time('prepared_time')->nullable();
            $table->string('prepared_by');
            $table->decimal('prepared_amount', 12, 2);
            $table->date('courier_date')->nullable();
            $table->time('courier_time')->nullable();
            $table->string('courier_received_by')->nullable();
            $table->decimal('courier_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drop_safes');
    }
};
