<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\Expenses;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * GET all expenses
     */
    public function index(Request $request)
    {
        $validated = $request->validate(PhysicalStoreIdRules::optionalQueryParameter());
        $storeId = $validated['store_id'] ?? null;
        $expenses = Expenses::query()
            ->when($storeId !== null, fn ($q) => $q->where('store_id', $storeId))
            ->latest()
            ->get();

        return response()->json([
            'data' => $expenses,
        ]);
    }

    /**
     * STORE a new expense
     */
    public function store(StoreExpenseRequest $request)
    {
        $expense = Expenses::create($request->validated());

        return response()->json([
            'message' => 'Expense created successfully',
            'data' => $expense,
        ], 201);
    }

    /**
     * SHOW single expense
     */
    public function show($id)
    {
        $expense = Expenses::findOrFail($id);

        return response()->json([
            'data' => $expense,
        ]);
    }

    /**
     * UPDATE an expense
     */
    public function update(StoreExpenseRequest $request, $id)
    {
        $expense = Expenses::findOrFail($id);
        $expense->update($request->validated());

        return response()->json([
            'message' => 'Expense updated successfully',
            'data' => $expense,
        ]);
    }

    /**
     * DELETE an expense
     */
    public function destroy($id)
    {
        $expense = Expenses::findOrFail($id);
        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }
}
