<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashlessAtmReconcileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                Rule::unique('cashless_atm_reconciles', 'date')->where(fn ($query) => $query->where('is_deleted', false)),
            ],
            'debit_total_sales' => 'required|numeric',
            'blaze_total_cash_less_sales' => 'required|numeric',
            'total_cashless_atm_tendered' => 'required|numeric',
            'total_cash_less_atm_change' => 'required|numeric',
            'total_cash_back' => 'required|numeric',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('notes') && $this->input('notes') === '') {
            $this->merge(['notes' => null]);
        }
    }
}
