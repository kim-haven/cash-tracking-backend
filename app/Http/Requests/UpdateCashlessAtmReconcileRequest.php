<?php

namespace App\Http\Requests;

use App\Models\CashlessAtmReconcile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCashlessAtmReconcileRequest extends FormRequest
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
        /** @var CashlessAtmReconcile $reconcile */
        $reconcile = $this->route('cashless_atm_reconcile');

        return [
            'date' => [
                'sometimes',
                'required',
                'date',
                Rule::unique('cashless_atm_reconciles', 'date')
                    ->ignore($reconcile->id)
                    ->where(fn ($query) => $query->where('is_deleted', false)),
            ],
            'debit_total_sales' => 'sometimes|required|numeric',
            'blaze_total_cash_less_sales' => 'sometimes|required|numeric',
            'total_cashless_atm_tendered' => 'sometimes|required|numeric',
            'total_cash_less_atm_change' => 'sometimes|required|numeric',
            'total_cash_back' => 'sometimes|required|numeric',
            'notes' => 'sometimes|nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('notes') && $this->input('notes') === '') {
            $this->merge(['notes' => null]);
        }
    }
}
