<?php

use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_all_stores')->default(false);
            $table->timestamps();

            $table->unique('name');
        });

        $now = now();

        foreach (Store::NAMES as $name) {
            DB::table('stores')->insert([
                'name' => $name,
                'is_all_stores' => $name === 'All Stores',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
