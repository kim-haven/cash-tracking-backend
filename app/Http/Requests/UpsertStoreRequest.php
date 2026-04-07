<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $storeId = $this->route('store');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('stores', 'name')->ignore($storeId),
            ],
            'is_all_stores' => 'boolean',
        ];
    }
}
