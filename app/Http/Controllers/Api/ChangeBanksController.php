<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChangeBankRequest;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\ChangeBank;
use Illuminate\Http\Request;

class ChangeBanksController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;

        $rows = ChangeBank::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $rows,
        ]);
    }

    public function store(StoreChangeBankRequest $request)
    {
        $row = ChangeBank::create($request->validated());

        return response()->json([
            'message' => 'Change bank entry created successfully',
            'data' => $row,
        ], 201);
    }

    public function show(ChangeBank $change_bank)
    {
        return response()->json([
            'data' => $change_bank,
        ]);
    }

    public function update(StoreChangeBankRequest $request, ChangeBank $change_bank)
    {
        $change_bank->update($request->validated());

        return response()->json([
            'message' => 'Change bank entry updated successfully',
            'data' => $change_bank->refresh(),
        ]);
    }

    public function destroy(ChangeBank $change_bank)
    {
        $change_bank->delete();

        return response()->json([
            'message' => 'Change bank entry deleted successfully',
        ]);
    }
}
