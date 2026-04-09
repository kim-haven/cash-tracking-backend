<?php

namespace App\Http\Requests;

use App\Http\Validation\PhysicalStoreIdRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreChangeBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(PhysicalStoreIdRules::requiredAttribute(), [
            'date' => 'required|date',
            'count_amount' => 'nullable|numeric',
            'change_in' => 'nullable|numeric',
            'change_out' => 'nullable|numeric',
            'description' => 'nullable|string|max:500',
            'deposit' => 'nullable|numeric',
            'picked_up' => 'nullable|numeric',
            'sum_of_pickups' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'difference' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('notes') && $this->input('notes') === '') {
            $this->merge(['notes' => null]);
        }
        if ($this->has('description') && $this->input('description') === '') {
            $this->merge(['description' => null]);
        }
    }
}
