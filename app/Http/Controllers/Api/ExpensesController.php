<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expenses;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExpenseRequest;

class ExpensesController extends Controller
{
    /**
     * GET all expenses
     */
    public function index()
    {
        $expenses = Expenses::latest()->get();

        return response()->json([
            'data' => $expenses
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
            'data' => $expense
        ], 201);
    }

    /**
     * SHOW single expense
     */
    public function show($id)
    {
        $expense = Expenses::findOrFail($id);

        return response()->json([
            'data' => $expense
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
            'data' => $expense
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
            'message' => 'Expense deleted successfully'
        ]);
    }
}