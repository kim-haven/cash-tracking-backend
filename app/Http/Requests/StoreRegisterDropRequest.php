<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates register drop create payloads from the React app.
 *
 * Times may be sent as "HH:mm" or "HH:mm:ss" (24-hour). Dates may be "Y-m-d" or ISO-8601 strings.
 */
class StoreRegisterDropRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'register' => 'required|string|max:255',
            'time_start' => 'required|date_format:H:i:s',
            'time_end' => 'nullable|date_format:H:i:s',
            'action' => 'required|string|max:255',
            'cash_in' => 'required|numeric',
            'initials' => 'required|string|max:16',
            'notes' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('time_start') && is_string($this->input('time_start'))) {
            $merge['time_start'] = self::padTimeString($this->input('time_start'));
        }

        if ($this->has('time_end')) {
            $end = $this->input('time_end');
            if ($end === '' || $end === null) {
                $merge['time_end'] = null;
            } elseif (is_string($end)) {
                $merge['time_end'] = self::padTimeString($end);
            }
        }

        if ($this->has('notes') && $this->input('notes') === '') {
            $merge['notes'] = null;
        }

        if ($merge !== []) {
            $this->merge($merge);
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
