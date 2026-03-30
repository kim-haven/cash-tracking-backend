<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Sets end time (stored as time_end) for an existing register drop row.
 *
 * Expects JSON: { "id": number, "time_out": "HH:mm" | "HH:mm:ss" } from the React app.
 */
class AddTimeOutRequest extends FormRequest
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
            'id' => 'required|integer|exists:register_drops,id',
            'time_out' => 'required|date_format:H:i:s',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('time_out') && is_string($this->input('time_out'))) {
            $this->merge([
                'time_out' => self::padTimeString($this->input('time_out')),
            ]);
        }
    }

    private static function padTimeString(string $value): string
    {
        $value = trim($value);

        if (preg_match('/^(\d{1,2}):(\d{2})$/', $value, $m)) {
            return sprintf('%02d:%02d:00', (int) $m[1], (int) $m[2]);
        }

        if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $value, $m)) {
            return sprintf('%02d:%02d:%02d', (int) $m[1], (int) $m[2], (int) $m[3]);
        }

        return $value;
    }
}
