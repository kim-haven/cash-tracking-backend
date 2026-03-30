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
            $table->string('courier_given_by')->nullable()->after('courier_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drop_safes', function (Blueprint $table) {
            $table->dropColumn('courier_given_by');
        });
    }
};
