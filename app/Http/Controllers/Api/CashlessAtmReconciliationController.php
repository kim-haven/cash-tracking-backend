<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\BlazeAccountingSummary;
use App\Models\CashlessAtmEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CashlessAtmReconciliationController extends Controller
{
    /**
     * Daily reconciliation: sums Cashless ATM entries (debit total sales) vs Blaze Accounting Summary
     * (retail value of sales), with variance % and dollar difference matching the reconciliation UI.
     *
     * Variance % = ((sum_blaze - sum_debit) / sum_blaze) * 100 when sum_blaze ≠ 0.
     * Difference ($) = sum_debit - sum_blaze (positive = debit higher, shown green in UI).
     */
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $debitByDay = CashlessAtmEntry::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->selectRaw('date, SUM(debit_total_sales) as sum_debit_total_sales')
            ->groupBy('date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->date)->format('Y-m-d'));

        $blazeByDay = BlazeAccountingSummary::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->selectRaw('date, SUM(retail_value_of_sales) as sum_blaze_sales')
            ->groupBy('date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->date)->format('Y-m-d'));

        $days = $debitByDay->keys()->merge($blazeByDay->keys())->unique()->sortDesc()->values();

        $rows = $days->map(function (string $day) use ($debitByDay, $blazeByDay) {
            $debit = $debitByDay->get($day);
            $blaze = $blazeByDay->get($day);

            $sumDebit = $this->moneyStr($debit?->sum_debit_total_sales ?? '0');
            $sumBlaze = $this->moneyStr($blaze?->sum_blaze_sales ?? '0');

            return [
                'date' => $day,
                'sum_of_debit_total_sales' => $sumDebit,
                'sum_of_blaze_sales' => $sumBlaze,
                'variance_percent' => $this->variancePercent($sumDebit, $sumBlaze),
                'difference' => bcsub($sumDebit, $sumBlaze, 2),
            ];
        })->values();

        $perPage = max(1, min((int) $request->integer('per_page', 50), 100));
        $page = max(1, (int) $request->integer('page', 1));
        $total = $rows->count();

        $slice = $rows->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'meta_fields' => [
                'debit_source' => 'cashless_atm_entries.debit_total_sales (sum by date)',
                'blaze_source' => 'blaze_accounting_summary.retail_value_of_sales (sum by date)',
            ],
        ]);
    }

    private function moneyStr(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '0.00';
        }

        $s = is_numeric($value) ? (string) $value : '0';

        return bcadd($s, '0', 2);
    }

    private function variancePercent(string $sumDebit, string $sumBlaze): ?string
    {
        if (bccomp($sumBlaze, '0', 4) === 0) {
            return null;
        }

        $diff = bcsub($sumBlaze, $sumDebit, 8);
        $ratio = bcdiv($diff, $sumBlaze, 8);

        return bcmul($ratio, '100', 2);
    }
}
