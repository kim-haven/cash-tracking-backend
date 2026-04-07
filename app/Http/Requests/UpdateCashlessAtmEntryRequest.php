<?php

namespace App\Http\Requests;

use App\Http\Validation\PhysicalStoreIdRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCashlessAtmEntryRequest extends FormRequest
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
        return array_merge(PhysicalStoreIdRules::optionalAttribute(), [
            'date' => 'sometimes|required|date',
            'employee' => 'sometimes|required|string|max:255',
            'terminal' => 'sometimes|required|string|max:255',
            'debit_terminal_total_dispensed' => 'sometimes|required|numeric',
            'total_tips' => 'sometimes|required|numeric',
            'debit_total_sales' => 'sometimes|required|numeric',
            'total_cash_back' => 'sometimes|required|numeric',
            'blaze_total_cash_less_sales' => 'sometimes|required|numeric',
            'total_cash_less_atm_change' => 'sometimes|required|numeric',
            'notes' => 'sometimes|nullable|string',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('notes') && $this->input('notes') === '') {
            $this->merge(['notes' => null]);
        }
    }
}
