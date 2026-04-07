<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoftDeleteCashlessAtmEntryRequest;
use App\Http\Requests\StoreCashlessAtmEntryRequest;
use App\Http\Requests\UpdateCashlessAtmEntryRequest;
use App\Http\Resources\CashlessAtmEntryResource;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\CashlessAtmEntry;
use Illuminate\Http\Request;

class CashlessAtmEntriesController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $query = CashlessAtmEntry::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('employee')) {
            $query->where('employee', $request->string('employee'));
        }

        if ($request->filled('terminal')) {
            $query->where('terminal', $request->string('terminal'));
        }

        return CashlessAtmEntryResource::collection($query->paginate(50));
    }

    public function store(StoreCashlessAtmEntryRequest $request)
    {
        $entry = CashlessAtmEntry::create($request->validated());

        return (new CashlessAtmEntryResource($entry->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    public function show(CashlessAtmEntry $cashless_atm_entry)
    {
        return new CashlessAtmEntryResource($cashless_atm_entry);
    }

    public function update(UpdateCashlessAtmEntryRequest $request, CashlessAtmEntry $cashless_atm_entry)
    {
        $cashless_atm_entry->update($request->validated());

        return new CashlessAtmEntryResource($cashless_atm_entry->refresh());
    }

    public function destroy(SoftDeleteCashlessAtmEntryRequest $request, CashlessAtmEntry $cashless_atm_entry)
    {
        $data = $request->validated();

        $cashless_atm_entry->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => (string) $data['deleted_by'],
            'delete_reason' => $data['delete_reason'] ?? null,
        ]);

        return response()->noContent();
    }
}
