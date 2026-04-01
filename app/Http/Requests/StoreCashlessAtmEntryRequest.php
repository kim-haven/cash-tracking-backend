<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashlessAtmEntryRequest extends FormRequest
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
            'date' => 'required|date',
            'employee' => 'required|string|max:255',
            'terminal' => 'required|string|max:255',
            'debit_terminal_total_dispensed' => 'required|numeric',
            'total_tips' => 'required|numeric',
            'debit_total_sales' => 'required|numeric',
            'total_cash_back' => 'required|numeric',
            'blaze_total_cash_less_sales' => 'required|numeric',
            'total_cash_less_atm_change' => 'required|numeric',
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
