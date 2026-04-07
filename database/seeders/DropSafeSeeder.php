<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropSafeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data from the "Drop Safe" tab (dummy Belmont 2025 Cash Tracking).
     */
    public function run(): void
    {
        $now = now();

        $storeId = Store::query()->where('name', 'Belmont')->value('id')
            ?? Store::query()->where('is_all_stores', false)->orderBy('id')->value('id');

        $rows = [
            [
                'bag_no' => '5807165',
                'prepared_date' => '2025-01-01',
                'prepared_time' => '22:15:00',
                'prepared_by' => 'Carissa',
                'prepared_amount' => '5702.00',
                'courier_date' => '2025-01-06',
                'courier_time' => '12:00:00',
                'courier_given_by' => null,
                'courier_received_by' => 'Marvin Avalos',
                'courier_amount' => '5702.00',
            ],
            [
                'bag_no' => '240179181',
                'prepared_date' => '2025-01-08',
                'prepared_time' => null,
                'prepared_by' => 'Carissa',
                'prepared_amount' => '4967.00',
                'courier_date' => '2025-01-14',
                'courier_time' => '11:23:00',
                'courier_given_by' => null,
                'courier_received_by' => 'Steven Lelona',
                'courier_amount' => '4967.00',
            ],
            [
                'bag_no' => '240179186',
                'prepared_date' => '2025-01-10',
                'prepared_time' => '22:30:00',
                'prepared_by' => 'Mark S.',
                'prepared_amount' => '3682.00',
                'courier_date' => '2025-01-14',
                'courier_time' => '11:23:00',
                'courier_given_by' => null,
                'courier_received_by' => 'Steven Lelona',
                'courier_amount' => '3682.00',
            ],
            [
                'bag_no' => '240179192',
                'prepared_date' => '2025-01-14',
                'prepared_time' => '22:15:00',
                'prepared_by' => 'Jaycob',
                'prepared_amount' => '5172.00',
                'courier_date' => '2025-01-21',
                'courier_time' => '13:08:00',
                'courier_given_by' => null,
                'courier_received_by' => 'Brian Martinez',
                'courier_amount' => '5172.00',
            ],
        ];

        foreach ($rows as $row) {
            DB::table('drop_safes')->insert([
                'store_id' => $storeId,
                ...$row,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
