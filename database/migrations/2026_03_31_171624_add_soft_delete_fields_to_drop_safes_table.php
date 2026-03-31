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
            $table->boolean('is_deleted')->default(false)->after('courier_amount');
            $table->timestamp('deleted_at')->nullable()->after('is_deleted');
            $table->string('deleted_by')->nullable()->after('deleted_at');
            $table->text('delete_reason')->nullable()->after('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drop_safes', function (Blueprint $table) {
            $table->dropColumn(['is_deleted', 'deleted_at', 'deleted_by', 'delete_reason']);
        });
    }
};
