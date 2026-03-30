<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Throwable;

/**
 * Sets the same time_out (stored as time_end) on multiple register_drops rows.
 *
 * Expects JSON: { "ids": [1, 2, 3], "time_out": "20:00" | "8:00 PM" } from the React app.
 */
class BulkTimeOutUpdateRequest extends FormRequest
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
            'ids' => 'required|array|min:2',
            'ids.*' => 'integer|distinct|exists:register_drops,id',
            'time_out' => 'required|date_format:H:i:s',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('time_out') && is_string($this->input('time_out'))) {
            $this->merge([
                'time_out' => self::normalizeTimeOut($this->input('time_out')),
            ]);
        }
    }

    private static function normalizeTimeOut(string $value): string
    {
        $value = trim($value);

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (Throwable) {
            return self::padTimeString($value);
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
