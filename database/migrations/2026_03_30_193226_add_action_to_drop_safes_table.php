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
        Schema::table('drop_safes', function (Blueprint $table) {
            $table->string('action', 64)->default('update_courier')->after('courier_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drop_safes', function (Blueprint $table) {
            $table->dropColumn('action');
        });
    }
};
