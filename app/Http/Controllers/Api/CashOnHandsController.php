<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegisterDrop;
use App\Models\Expenses;
use App\Models\DropSafe;

class CashOnHandsController extends Controller
{
    public function index()
    {
        // Step 1: Get distinct dates from all three tables
        $dates = RegisterDrop::select('date')
            ->union(Expenses::select('date'))
            ->union(DropSafe::select('prepared_date as date'))
            ->orderBy('date', 'desc')
            ->pluck('date');

        $results = [];

        foreach ($dates as $date) {

            // AM register drops: time_start before 12:00 PM
            $amDrops = RegisterDrop::where('date', $date)
                ->whereTime('time_start', '<', '12:00:00')
                ->get();

            // PM register drops: time_start at or after 12:00 PM
            $pmDrops = RegisterDrop::where('date', $date)
                ->whereTime('time_start', '>=', '12:00:00')
                ->get();

            // Just the initials as comma-separated strings
            $amInitials = $amDrops->pluck('initials')->implode(', ');
            $pmInitials = $pmDrops->pluck('initials')->implode(', ');

            // Totals
            $registerDropsTotal = $amDrops->sum('cash_in') + $pmDrops->sum('cash_in');

            // Expenses cash-in / cash-out totals for the date
            $expensesCashIn = Expenses::where('date', $date)->sum('cash_in');
            $expensesCashOut = Expenses::where('date', $date)->sum('cash_out');

            // Balance = register drops total - expenses (cash in and cash out)
            $balance = $registerDropsTotal - $expensesCashIn - $expensesCashOut;

            // Drop safe deposit for the date
            $dropSafeDeposit = DropSafe::where('prepared_date', $date)->sum('prepared_amount');

            // Drop safe courier info for the date
            $dropSafeCourier = DropSafe::where('courier_date', $date)->first();

            $courierDetails = $dropSafeCourier->courier_received_by ?? null;

            // Cumulative deposit balance up to current date
            $cumulativeDeposit = DropSafe::where('prepared_date', '<=', $date)->sum('prepared_amount');

            $results[] = [
                'date' => $date,
                'amController' => $amInitials,
                'pmController' => $pmInitials,
                'registerDrops' => $registerDropsTotal,
                'expenses' => $expensesCashIn,
                'expensesCashOut' => $expensesCashOut,
                'balance' => $balance,
                'deposit' => $dropSafeDeposit,
                'courier' => $courierDetails,
                'finalBalance' => $cumulativeDeposit,
            ];
        }

        return response()->json($results);
    }
}