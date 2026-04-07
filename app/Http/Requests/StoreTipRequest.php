<?php

namespace App\Http\Requests;

use App\Http\Validation\PhysicalStoreIdRules;
use App\Models\Expenses;
use Illuminate\Foundation\Http\FormRequest;

class StoreTipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(PhysicalStoreIdRules::requiredAttribute(), [
            'initials' => 'required|string|max:255',
            'cash_tip_amount' => 'nullable|numeric|min:0',
            'end_of_pay_period_total' => 'nullable|numeric|min:0',
            'cash_balance' => 'nullable|numeric',
            'date' => 'required|date',
            'cash_tip' => 'nullable|numeric|min:0',
            'credit_tips' => 'nullable|numeric|min:0',
            'ach_tips' => 'nullable|numeric|min:0',
            'debit_tips' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            'expense_id' => [
                'nullable',
                'exists:expenses,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value === null) {
                        return;
                    }
                    $expense = Expenses::query()->find($value);
                    if ($expense !== null && (int) $expense->store_id !== (int) $this->input('store_id')) {
                        $fail('The linked expense must belong to the same store.');
                    }
                },
            ],
        ]);
    }
}
