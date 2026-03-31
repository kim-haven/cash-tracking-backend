<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRegisterDropRequest extends FormRequest
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
            'date' => 'sometimes|required|date',
            'register' => 'sometimes|required|string|max:255',
            'time_start' => 'sometimes|required|date_format:H:i:s',
            'time_end' => 'sometimes|nullable|date_format:H:i:s',
            'action' => 'sometimes|required|string|max:255',
            'cash_in' => 'sometimes|required|numeric',
            'initials' => 'sometimes|required|string|max:16',
            'notes' => 'sometimes|nullable|string',
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
