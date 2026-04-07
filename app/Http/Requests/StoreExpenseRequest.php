<?php

namespace App\Http\Requests;

use App\Http\Validation\PhysicalStoreIdRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(PhysicalStoreIdRules::requiredAttribute(), [
            'date' => 'required|date',
            'paid_by' => 'required|string|max:255',
            'pay_to' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'receipt_uploaded' => 'boolean',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cash_in' => 'nullable|numeric|min:0',
            'cash_out' => 'nullable|numeric|min:0',
        ]);
    }
}
