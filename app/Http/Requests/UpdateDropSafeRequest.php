<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesDropSafeInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDropSafeRequest extends FormRequest
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
            'bag_no' => 'sometimes|required|string|max:32',
            'prepared_date' => 'sometimes|required|date',
            'prepared_time' => 'sometimes|nullable|date_format:H:i:s',
            'prepared_by' => 'sometimes|required|string|max:255',
            'prepared_amount' => 'sometimes|required|numeric',
            'courier_date' => 'sometimes|nullable|date',
            'courier_time' => 'sometimes|nullable|date_format:H:i:s',
            'courier_given_by' => 'sometimes|nullable|string|max:255',
            'courier_received_by' => 'sometimes|nullable|string|max:255',
            'courier_amount' => 'sometimes|nullable|numeric',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeNormalizedDropSafeInput();
    }
}
