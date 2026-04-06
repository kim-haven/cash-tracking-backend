<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashReconciliationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'controller' => 'required|string|max:255',
            'cash_in' => 'nullable|numeric',
            'cash_refunds' => 'nullable|numeric',
            'cashless_atm_cash_back' => 'nullable|numeric',
            'reported_cash_collected' => 'nullable|numeric',
            'cash_collected' => 'nullable|numeric',
            'cash_difference' => 'nullable|numeric',
            'credit_difference' => 'nullable|numeric',
            'cashless_atm_difference' => 'nullable|numeric',
            'cash_vs_cashless_atm_difference' => 'nullable|numeric',
            'reason_notes' => 'nullable|string',
        ];
    }
}
