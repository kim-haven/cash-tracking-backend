<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoftDeleteCashlessAtmReconcileRequest;
use App\Http\Requests\StoreCashlessAtmReconcileRequest;
use App\Http\Requests\UpdateCashlessAtmReconcileRequest;
use App\Http\Resources\CashlessAtmReconcileResource;
use App\Models\CashlessAtmReconcile;
use Illuminate\Http\Request;

class CashlessAtmReconcilesController extends Controller
{
    public function index(Request $request)
    {
        $query = CashlessAtmReconcile::query()
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        return CashlessAtmReconcileResource::collection($query->paginate(50));
    }

    public function store(StoreCashlessAtmReconcileRequest $request)
    {
        $reconcile = CashlessAtmReconcile::create($request->validated());

        return (new CashlessAtmReconcileResource($reconcile->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CashlessAtmReconcile $cashless_atm_reconcile)
    {
        return new CashlessAtmReconcileResource($cashless_atm_reconcile);
    }

    public function update(UpdateCashlessAtmReconcileRequest $request, CashlessAtmReconcile $cashless_atm_reconcile)
    {
        $cashless_atm_reconcile->update($request->validated());

        return new CashlessAtmReconcileResource($cashless_atm_reconcile->refresh());
    }

    public function destroy(SoftDeleteCashlessAtmReconcileRequest $request, CashlessAtmReconcile $cashless_atm_reconcile)
    {
        $data = $request->validated();

        $cashless_atm_reconcile->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => (string) $data['deleted_by'],
            'delete_reason' => $data['delete_reason'] ?? null,
        ]);

        return response()->noContent();
    }
}
