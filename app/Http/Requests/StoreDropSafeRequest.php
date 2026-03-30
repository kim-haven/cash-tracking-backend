<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesDropSafeInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDropSafeRequest extends FormRequest
{
    use NormalizesDropSafeInput;

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
            'bag_no' => 'required|string|max:32',
            'prepared_date' => 'required|date',
            'prepared_time' => 'nullable|date_format:H:i:s',
            'prepared_by' => 'required|string|max:255',
            'prepared_amount' => 'required|numeric',
            'courier_date' => 'nullable|date',
            'courier_time' => 'nullable|date_format:H:i:s',
            'courier_given_by' => 'nullable|string|max:255',
            'courier_received_by' => 'nullable|string|max:255',
            'courier_amount' => 'nullable|numeric',
            'action' => 'nullable|string|max:64|in:update_courier',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeNormalizedDropSafeInput();
    }
}
