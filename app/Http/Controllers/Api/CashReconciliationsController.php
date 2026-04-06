<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashReconciliationRequest;
use App\Models\CashReconciliation;
use App\Models\RegisterDrop;
use Carbon\CarbonInterface;

class CashReconciliationsController extends Controller
{
    public function index()
    {
        $rows = CashReconciliation::latest('date')->latest('id')->get();

        $data = $rows->map(function (CashReconciliation $row) {
            return array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date)
            );
        });

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(StoreCashReconciliationRequest $request)
    {
        $row = CashReconciliation::create($request->validated());

        return response()->json([
            'message' => 'Cash reconciliation created successfully',
            'data' => array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date)
            ),
        ], 201);
    }

    public function show($id)
    {
        $row = CashReconciliation::findOrFail($id);

        return response()->json([
            'data' => array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date)
            ),
        ]);
    }

    public function update(StoreCashReconciliationRequest $request, $id)
    {
        $row = CashReconciliation::findOrFail($id);
        $row->update($request->validated());

        return response()->json([
            'message' => 'Cash reconciliation updated successfully',
            'data' => array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date)
            ),
        ]);
    }

    public function destroy($id)
    {
        $row = CashReconciliation::findOrFail($id);
        $row->delete();

        return response()->json([
            'message' => 'Cash reconciliation deleted successfully',
        ]);
    }

    /**
     * AM/PM register drops for a day (same rules as CashOnHandsController).
     *
     * @return array{amController: string, pmController: string, registerDrops: float|int|string}
     */
    private function registerDropsForDate(CarbonInterface|string $date): array
    {
        $dateString = $date instanceof CarbonInterface
            ? $date->format('Y-m-d')
            : (string) $date;

        $amDrops = RegisterDrop::where('date', $dateString)
            ->whereTime('time_start', '<', '12:00:00')
            ->get();

        $pmDrops = RegisterDrop::where('date', $dateString)
            ->whereTime('time_start', '>=', '12:00:00')
            ->get();

        return [
            'amController' => $amDrops->pluck('initials')->implode(', '),
            'pmController' => $pmDrops->pluck('initials')->implode(', '),
            'registerDrops' => $amDrops->sum('cash_in') + $pmDrops->sum('cash_in'),
        ];
    }
}
