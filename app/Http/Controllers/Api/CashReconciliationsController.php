<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashReconciliationRequest;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\CashReconciliation;
use App\Models\RegisterDrop;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class CashReconciliationsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $rows = CashReconciliation::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->latest('date')
            ->latest('id')
            ->get();

        $data = $rows->map(function (CashReconciliation $row) {
            return array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date, $row->store_id)
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
                $this->registerDropsForDate($row->date, $row->store_id)
            ),
        ], 201);
    }

    public function show($id)
    {
        $row = CashReconciliation::findOrFail($id);

        return response()->json([
            'data' => array_merge(
                $row->toArray(),
                $this->registerDropsForDate($row->date, $row->store_id)
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
                $this->registerDropsForDate($row->date, $row->store_id)
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
    private function registerDropsForDate(CarbonInterface|string $date, ?int $storeId = null): array
    {
        $dateString = $date instanceof CarbonInterface
            ? $date->format('Y-m-d')
            : (string) $date;

        $amDrops = RegisterDrop::query()
            ->where('date', $dateString)
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->whereTime('time_start', '<', '12:00:00')
            ->get();

        $pmDrops = RegisterDrop::query()
            ->where('date', $dateString)
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->whereTime('time_start', '>=', '12:00:00')
            ->get();

        return [
            'amController' => $amDrops->pluck('initials')->implode(', '),
            'pmController' => $pmDrops->pluck('initials')->implode(', '),
            'registerDrops' => $amDrops->sum('cash_in') + $pmDrops->sum('cash_in'),
        ];
    }
}
